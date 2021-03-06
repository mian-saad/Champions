<?php
/**
 * @package  TakedownstatesPlugin
 */
namespace Inc\Base;

use TCPDF;
use \Inc\Base\BaseController;
use \Inc\Base\StateTypes;

class ReportController extends BaseController
{
    public $language;
    public $report_id; // id of report, which is being taken
    public $current_state_code; // id of the current state (which is currently presented to the viewer)

    public $state_file; // associative array, contains the states json file
    public $string_file; // associative array, contains all strings with code as key

    public $state_list;
    public $current_step_counter; // counter for iterating through the state list
    public $contact_proposals; // array for contact proposals, based on 1-user language, 2-citizenship, 3-crime location

    public $timepicker_map = [
        "en"=>"en",
        "it"=>"it",
        "ge"=>"de",
        "spa"=>"es",
        "ro"=>"ro",
        "no"=>"no",
        "pol"=>"pl",
        "cz"=>"en",
        "sl"=>"sl",
        "ne"=>"en",
        "is"=>"he",
        "fr"=>"fr",
        "gr"=>"el",
        "bu"=>"bg",
        "por"=>"pt"
        ];

    public $lang_country_map = [
        "en"=>"England",
        "it"=>"Italy",
        "ge"=>"Germany",
        "spa"=>"Spain",
        "ro"=>"Romania",
        "no"=>"Norway",
        "pol"=>"Poland",
        "cz"=>"Czech Republic",
        "sl"=>"Slovakia",
        "ne"=>"Netherlands",
        "is"=>"Israel",
        "fr"=>"France",
        "gr"=>"Greek",
        "bu"=>"Bulgaria",
        "por"=>"Portugal"
    ];

    // initializes the controller object
    public function init($report_id, $language)
    {
        $this->report_id = $report_id;
        $this->language = $language;

        # DEBUG: here you can chose the first state for debugging, in production the first state is M0.0
        $this->current_state_code = "M0.0";

        $this->report_answers = [];
        $this->state_list = [];
        $this->current_step_counter = 0;

        // read json
        $this->state_file = json_decode(file_get_contents($this->plugin_path . "assets/base/" . $language . "/tra_states.json"), true);
        $this->string_file = json_decode(file_get_contents($this->plugin_path . "assets/base/" . $language . "/tra_strings.json"), true);
    }

    // generates the content for the current state
    public function generate_content()
    {

        if (array_key_exists($this->current_state_code, $this->state_list)) {
            $state = $this->state_list[$this->current_state_code];
        } else {
            $state = $this->initialize_state($this->current_state_code);
        }

        $html = $state->generate_html(); // IMPORTANT: GENERATES THE HTML

        $html .= $this->generate_post_js();
        return $html;
    }

    public function generate_post_js(){
        return "<script>jQuery.datetimepicker.setLocale('".$this->timepicker_map[$this->language]."');</script>";
    }

    // taking the new state code string, it takes it from the states and initiates a new object
    public function initialize_state($state_code)
    {
        $state = $this->state_file[$state_code];

        if ($state['state_type'] == 'yesno') {
            $state_obj = new StateTypes\TraYesNoQuestion($this->report_id, $state_code, $state, $this->string_file['yes'], $this->string_file['no'], $this->string_file['back'], $this->string_file['field_warning'], $this->string_file['warning']);
        } else if ($state['state_type'] == 'composed') {
            $state_obj = new StateTypes\TraComposedQuestion($this->report_id, $state_code, $state, $this->string_file['continue'], $this->string_file['back'], $this->string_file['field_warning'], $this->string_file['warning']);
        } else if ($state['state_type'] == 'datetime') {
            $state_obj = new StateTypes\TraDatetimeQuestion($this->report_id, $state_code, $state, $this->language, $this->string_file['continue'], $this->string_file['back'], $this->string_file['field_warning'], $this->string_file['wrong_time_warning']);
        } else if ($state['state_type'] == 'checkbox') {
            $state_obj = new StateTypes\TraCheckboxQuestion($this->report_id, $state_code, $state, $this->string_file['continue'], $this->string_file['back'], $this->string_file['field_warning'], $this->string_file['warning']);
        } else if ($state['state_type'] == 'description') {
            $state_obj = new StateTypes\TraDescriptionQuestion($this->report_id, $state_code, $state, $this->string_file['continue'], $this->string_file['back'], $this->string_file['field_warning'], $this->string_file['warning']);
        } else if ($state['state_type'] == 'result') {
            // here we need to pass all the recorded answers
            $answers = [];

            foreach ($this->state_list as $code => $state) {
                $answers += $state->generate_readable_response_array();
            }
            // lets generate contact propasls array here
            $this->contact_proposals = $this->generate_contact_proposals();
            $pdfurl = $this->generate_pdf($answers);
            $state_obj = new StateTypes\TraFinal($this->report_id, $state_code, $answers, $this->contact_proposals, $this->string_file['pdf_download'], $this->string_file['back'], $this->string_file['crime_location_proposals'], $this->string_file['language_pref_proposals'], $this->string_file['residence_state_proposals'], $this->string_file['no_results'],$pdfurl);
        } else if ($state['state_type'] == 'radio') {
            $state_obj = new StateTypes\TraRadioQuestion($this->report_id, $state_code, $state, $this->string_file['continue'], $this->string_file['back'], $this->string_file['field_warning'], $this->string_file['warning']);
        } else if ($state['state_type'] == 'text') {
            $state_obj = new StateTypes\TraTextQuestion($this->report_id, $state_code, $state, $this->string_file['continue'], $this->string_file['back'], $this->string_file['field_warning'], $this->string_file['warning']);
        } else if ($state['state_type'] == 'police_call') {
            $state_obj = new StateTypes\TraPoliceCall($this->report_id, $state_code, $state['state_text'], $this->string_file['continue'], $this->string_file['phone_number']);
        } else if ($state['state_type'] == 'gdpr') {
            $state_obj = new StateTypes\TraGDPR($this->report_id, $state_code, $state, $this->string_file['gdpr_accept'], $this->string_file['gdpr_warning']);
        } else { // else we are expiriencing an error
            $state_obj = new StateTypes\TraError($this->report_id, $state_code, $state['state_text'], $this->string_file['continue']);
        }

        $this->state_list[$state_code] = $state_obj;
        return $state_obj;

    }

    // lets unset the current step if we go back
    public function step_back()
    {

        // unset($this->state_list[$this->current_state_code]);
        $state_obj = $this->state_list[$this->current_state_code];

        $this->current_step_counter = $this->current_step_counter - 1;
        // lets reload page if our step counter < 0
        if ($this->current_step_counter < 0) {
            echo "<script type='text/javascript'>
            window.location=document.location.href;
            </script>";
            return;
        } else {
            $this->current_state_code = array_keys($this->state_list)[$this->current_step_counter];
        }
    }

    // validates and processes the response, advances state in case the response is valid
    public function process_response($response)
    {
        $state_obj = $this->state_list[$this->current_state_code];

        if ($state_obj->validate($response)) { // if answer is valid, lets advance the state
            $state_obj->show_warning = false;

            $oldstate = $this->current_state_code;
            $this->current_state_code = $this->get_next_state(); // <=== IMPORTANT PLACE, HERE WE ADVANCE THE STATE CODE
            $this->current_step_counter = $this->current_step_counter + 1;

            // lets see if we are trying to change our questionaire tree, if so, lets unset old states
            if ($this->current_step_counter < count($this->state_list)) {
                // we are in the statelist
                if ($this->current_state_code != array_keys($this->state_list)[$this->current_step_counter]) {
                    // we are changing the tree
                    $counter = count($this->state_list);

                    while ($this->current_step_counter <= $counter) {
                        $key = array_keys($this->state_list)[$counter];
                        unset($this->state_list[$key]);
                        $counter--;
                    }
                }
            }
        } // else we should be printing some warning
        else {
            $state_obj->show_warning = true;
        }
    }

    // state advancing logic, hapens after a state answer was validated
    public function get_next_state()
    {
        if ($this->current_state_code == "M0.0") {
            $this->current_step_counter--;
            unset($this->state_list["M0.0"]);
            return "M1.1";
        } else if ($this->current_state_code == "M1.1") {
            if ($this->state_list["M1.1"]->response == "false") {
                return "M1.2";
            } else {
                return "M1.3";
            }
        } else if ($this->current_state_code == "M1.2") {
            return "M1.4";
        } else if ($this->current_state_code == "M1.4") {
            return "M1.5";
        } else if ($this->current_state_code == "M1.5") {
            if (in_array('infection_of_systems', $this->state_list["M1.5"]->response['event_category'])
                or in_array('identity_theft', $this->state_list["M1.5"]->response['event_category'])
                or in_array('cyber_fraud', $this->state_list["M1.5"]->response['event_category'])) {
                return "M2.10";
            } else if (in_array('cyber_abuse', $this->state_list["M1.5"]->response['event_category'])) {
                return "M2.1";
            } else {
                return "M1.9";
            }
        } else if ($this->current_state_code == "M1.9") {
            return "M1.6";
        } else if ($this->current_state_code == "M1.6") {
            if ($this->state_list["M1.2"]->response["reporter_involvement"] != "victim") {
                return "M1.6a";
            } else {
                return "M1.7";
            }
        } else if ($this->current_state_code == "M1.6a") {
            return "M1.7";
        } else if ($this->current_state_code == "M1.7") {
            // unset it to trigger recreation of the state
            unset($this->state_list["M1.8"]);
            return "M1.8";
        } else if ($this->current_state_code == "M2.1") {
            if ($this->state_list["M2.1"]->response["contact_type"] == "email") {
                return "M2.2";
            } else if ($this->state_list["M2.1"]->response["contact_type"] == "phone_sms") {
                return "M2.4";
            } else if ($this->state_list["M2.1"]->response["contact_type"] == "sn_app") {
                return "M2.9";
            } else if ($this->state_list["M2.1"]->response["contact_type"] == "website") {
                return "M2.15";
            } else if ($this->state_list["M2.1"]->response["contact_type"] == "software") {
                return "M2.8";
            } else if ($this->state_list["M2.1"]->response["contact_type"] == "other") {
                // if we are in cyber fraud workflow, go to M2.7, else go to M1.6
                if (in_array('infection_of_systems', $this->state_list["M1.5"]->response['event_category'])
                    or in_array('identity_theft', $this->state_list["M1.5"]->response['event_category'])
                    or in_array('cyber_fraud', $this->state_list["M1.5"]->response['event_category'])) {
                    return "M2.7";
                } else {
                    return "M1.6";
                }
            }
        } else if ($this->current_state_code == "M2.2") {
            return "M2.3";
        } else if ($this->current_state_code == "M2.3") {
            if (in_array('infection_of_systems', $this->state_list["M1.5"]->response['event_category'])
                or in_array('identity_theft', $this->state_list["M1.5"]->response['event_category'])
                or in_array('cyber_fraud', $this->state_list["M1.5"]->response['event_category'])) {
                return "M2.7";
            } else {
                return "M1.6";
            }
        } else if ($this->current_state_code == "M2.4") {
            return "M2.5";
        } else if ($this->current_state_code == "M2.5") {
            if (in_array('infection_of_systems', $this->state_list["M1.5"]->response['event_category'])
                or in_array('identity_theft', $this->state_list["M1.5"]->response['event_category'])
                or in_array('cyber_fraud', $this->state_list["M1.5"]->response['event_category'])) {
                return "M2.7";
            } else {
                return "M1.6";
            }
        } else if ($this->current_state_code == "M2.9") {
            if (in_array('infection_of_systems', $this->state_list["M1.5"]->response['event_category'])
                or in_array('identity_theft', $this->state_list["M1.5"]->response['event_category'])
                or in_array('cyber_fraud', $this->state_list["M1.5"]->response['event_category'])) {
                return "M2.7";
            } else {
                return "M1.6";
            }
        } else if ($this->current_state_code == "M2.8") {
            if (in_array('infection_of_systems', $this->state_list["M1.5"]->response['event_category'])
                or in_array('identity_theft', $this->state_list["M1.5"]->response['event_category'])
                or in_array('cyber_fraud', $this->state_list["M1.5"]->response['event_category'])) {
                return "M2.7";
            } else {
                return "M1.6";
            }
        } else if ($this->current_state_code == "M2.15") {
            if (in_array('infection_of_systems', $this->state_list["M1.5"]->response['event_category'])
                or in_array('identity_theft', $this->state_list["M1.5"]->response['event_category'])
                or in_array('cyber_fraud', $this->state_list["M1.5"]->response['event_category'])) {
                return "M2.7";
            } else {
                return "M1.6";
            }
        } else if ($this->current_state_code == "M2.10") { // in state M.1.1 depending on the answer parameter
            if ($this->state_list["M2.10"]->response == "false") {
                return "M2.6";
            } else {
                return "M2.1";
            }
        } else if ($this->current_state_code == "M2.7") {
            return "M2.6";
        } else if ($this->current_state_code == "M2.6") {
            if ($this->state_list["M2.6"]->response["request_type"] == "download_open") {
                return "M2.11";
            } else if ($this->state_list["M2.6"]->response["request_type"] == "link_sensible_data") {
                return "M2.13";
            } else if ($this->state_list["M2.6"]->response["request_type"] == "other") {
                return "M1.6";
            }
        } else if ($this->current_state_code == "M2.11") {
            return "M2.12";
        } else if ($this->current_state_code == "M2.12") {
            return "M1.6";
        } else if ($this->current_state_code == "M2.13") {
            return "M2.14";
        } else if ($this->current_state_code == "M2.14") {
            return "M1.6";
        }
        return "ERROR";
    }

    public function generate_contact_proposals(){
        global $wpdb;


        // first lets do state of origin
        $reporter_residence = $this->state_list["M1.2"]->response['reporter_residence'];
        $lang_pref = $this->lang_country_map[$this->language];

        if(isset($this->state_list["M1.9"])){
            $crime_loc_country = $this->state_list["M1.9"]->response['crime_loc_country'];
            $crime_loc_address = $this->state_list["M1.9"]->response['crime_loc_address'];
            $crime_loc_city = $this->state_list["M1.9"]->response['crime_loc_city'];
            $crime_loc_postal = $this->state_list["M1.9"]->response['crime_loc_postal'];
        }else{  // else we have a cyber crime
            $crime_loc_country = "cyber";
            $crime_loc_address = "cyber";
            $crime_loc_city = "cyber";
            $crime_loc_postal = "cyber";
        }


        /*
        $crime_loc_country = "Germany";
        return [
            "crime_location"=> json_decode(json_encode($wpdb->get_results("SELECT a.name AS agency_name, b.url, b.email AS email, b.phone, c.name FROM `helplines` a, `helpline_contacts` b, `countries` c WHERE a.contact_id = b.id AND b.country_id = c.id AND c.name LIKE '$crime_loc_country'")), True),
            "language_pref"=> json_decode(json_encode($wpdb->get_results("SELECT a.name AS agency_name, b.url, b.email AS email, b.phone, c.name FROM `helplines` a, `helpline_contacts` b, `countries` c WHERE a.contact_id = b.id AND b.country_id = c.id AND c.name LIKE '$lang_pref'")), True),
            "residence_state"=> json_decode(json_encode($wpdb->get_results("SELECT a.name AS agency_name, b.url, b.email AS email, b.phone, c.name FROM `helplines` a, `helpline_contacts` b, `countries` c WHERE a.contact_id = b.id AND b.country_id = c.id AND c.name LIKE '$reporter_residence'")), True),
        ];


        */

        $db = \Inc\DB::getInstance();

        $pdo = $db->getConnection();

        $helplines_crime_location = $pdo->query( "SELECT a.name AS agency_name, b.url, b.email AS email, b.phone, c.name FROM `helplines` a, `helpline_contacts` b, `countries` c WHERE a.contact_id = b.id AND b.country_id = c.id AND c.name LIKE '%$crime_loc_country%'" )->fetchAll();
        $helplines_lang_pref      = $pdo->query( "SELECT a.name AS agency_name, b.url, b.email AS email, b.phone, c.name FROM `helplines` a, `helpline_contacts` b, `countries` c WHERE a.contact_id = b.id AND b.country_id = c.id AND c.name LIKE '%$lang_pref%'" )->fetchAll();
        $helplines_resident_state = $pdo->query( "SELECT a.name AS agency_name, b.url, b.email AS email, b.phone, c.name FROM `helplines` a, `helpline_contacts` b, `countries` c WHERE a.contact_id = b.id AND b.country_id = c.id AND c.name LIKE '%$reporter_residence%'" )->fetchAll();


        return [
            "crime_location"  => $helplines_crime_location,
            "language_pref"   => $helplines_lang_pref,
            "residence_state" => $helplines_resident_state
        ];

    }

    public function generate_pdf($answers)
    {
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false); // create TCPDF object with default constructor args
        $pdf->setFont('freeserif');
        $pdf->AddPage(); // pretty self-explanatory

        header('Content-Type: text/html; charset=utf-8');

        $html = "<h1>TAKEDOWN REPORT</h1><br>";

        foreach ($answers as $short_text => $value) {
            $html .= "<p>" . $short_text . " : " . $value . "</p>";
        }

        // output the HTML content
        $pdf->writeHTML($html, true, false, true, false, '');

        ob_clean();

        $pdf->Output($this->plugin_path . 'reports/' . $this->report_id . '.pdf', 'F');
        return $this->plugin_url . 'reports/' . $this->report_id . '.pdf';
    }

    public function db_store()
    {
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();

        $tra_reports_db_name = $wpdb->prefix . 'tra_reports';


        // lets delete entry if it already exists in the db
        $entries = $wpdb->get_results("SELECT report_id FROM ".$tra_reports_db_name." WHERE report_id=\"".$this->report_id."\"");
        if(sizeof($entries)!= 0){
            $wpdb->get_results("DELETE FROM ".$tra_reports_db_name." WHERE report_id=\"".$this->report_id."\"");
        }


        $answers = [
            'report_id' => $this->report_id,
            'report_locale' => $this->language,
            'report_time' => date("Y/m/d H:i"),
            'report_ip' => $_SERVER['REMOTE_ADDR'],
        ];



        foreach ($this->state_list as $code => $state) {
            if ($code != "M1.8") { // M1.8 doesnt have a response
                if (is_array($state->response)) {

                    if (!isset($state->response[$state->state['id']])){          // only the case for composed questions
                        $response = $state->response;
                    }

                    else if (is_array($state->response[$state->state['id']])) {      // is only the case for checkbox question
                        if(in_array('other',$state->response[$state->state['id']]) AND isset($state->response['other_text_input'])){

                            foreach($state->response[$state->state['id']] as &$str) {
                                $str = str_replace('other', $state->response['other_text_input'], $str);
                            }
                            unset($state->response['other_text_input']);
                        }
                        $response = array($state->state['id'] => implode(",", $state->response[$state->state['id']]));
                    }

                    else {  // main response is set and is not an array

                        if($state->response[$state->state['id']]=='other'){
                            $state->response[$state->state['id']] = $state->response['other_text_input'];
                        }
                        unset($state->response['other_text_input']);
                        $response = $state->response;
                    }

                    $answers = $answers + $response;

                } else {            // if response is some text, lets create an array with its state id and add it to answers array
                    $answers = $answers + array($state->state['id'] => $state->response);
                }
            }
        }

        $wpdb->insert($tra_reports_db_name, $answers);



    }
}
