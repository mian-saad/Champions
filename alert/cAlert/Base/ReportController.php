<?php
/**
 * @package  TakedownstatesPlugin
 */
namespace Cover\Base;

use TCPDF;
use \Cover\Base\BaseController;
use \Cover\Base\StateTypes;

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
        $this->state_file = json_decode(file_get_contents($this->plugin_path . "assets/base/" . $language . "/alert_states.json"), true);
        $this->string_file = json_decode(file_get_contents($this->plugin_path . "assets/base/" . $language . "/alert_strings.json"), true);
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
//            $this->contact_proposals = $this->generate_contact_proposals();
            $pdfurl = $this->generate_pdf($answers);
            $state_obj = new StateTypes\TraFinal($this->report_id, $state_code, $answers, $this->string_file['pdf_download'], $this->string_file['back'], $this->string_file['crime_location_proposals'], $this->string_file['language_pref_proposals'], $this->string_file['residence_state_proposals'], $this->string_file['no_results'],$pdfurl);
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

        if ($state_obj->validate($response) === 'Unregistered') {
            echo "<h3 style='color: #FFFFFF; background: #b43e36; padding: 10px;text-align: center; '>You Need to register before you can generate Alert</h3>";
        }

        else if ($state_obj->validate($response)) { // if answer is valid, lets advance the state
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
            return "M1.2";
        } else if ($this->current_state_code == "M1.2") {
            return "M1.3";
        } else if ($this->current_state_code == "M1.3") {
            return "M1.4";
        } else if ($this->current_state_code == "M1.4") {
            return "M1.5";
        }
        return "ERROR";
    }

    public function generate_pdf($answers)
    {
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false); // create TCPDF object with default constructor args
        $pdf->setFont('freeserif');
        $pdf->AddPage(); // pretty self-explanatory

        header('Content-Type: text/html; charset=utf-8');

        $html = "<h1>ALERT REPORT</h1><br>";

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

        $alert_reports_db_name = $wpdb->prefix . 'alert';


        // lets delete entry if it already exists in the db
        $entries = $wpdb->get_results("SELECT report_id FROM ".$alert_reports_db_name." WHERE report_id=\"".$this->report_id."\"");
        if(sizeof($entries)!= 0){
            $wpdb->get_results("DELETE FROM ".$alert_reports_db_name." WHERE report_id=\"".$this->report_id."\"");
        }


        $answers = [
            'report_id' => $this->report_id,
            'report_locale' => $this->language,
            'report_time' => date("Y/m/d H:i"),
            'report_ip' => $_SERVER['REMOTE_ADDR'],
        ];



        foreach ($this->state_list as $code => $state) {
            if ($code != "M1.5") { // M1.8 doesnt have a response
                if (is_array($state->response)) {

                    if (!isset($state->response[$state->state['id']])){          // only the case for composed questions
                        $response = $state->response;
                    }

                    else if (is_array($state->response[$state->state['id']])) {      // is only the case for checkbox question
                        if(in_array('Other',$state->response[$state->state['id']]) AND isset($state->response['other_text_input'])){

                            foreach($state->response[$state->state['id']] as &$str) {
                                $str = str_replace('Other', $state->response['other_text_input'], $str);
                            }
                            unset($state->response['other_text_input']);
                        }
                        $response = array($state->state['id'] => implode(",", $state->response[$state->state['id']]));
                    }

                    else {  // main response is set and is not an array

                        if($state->response[$state->state['id']]=='Other'){
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
        $wpdb->insert($alert_reports_db_name, $answers);
        echo "";
    }

    public function send_mail($report_id) {
        global $wpdb;
        $alert_reports_db_name = $wpdb->prefix . 'alert';
        $entries = $wpdb->get_results("SELECT reporter_email, reporter_fName, reporter_lName, reporter_residence FROM $alert_reports_db_name WHERE report_id='$report_id'");

        $password = $this->randomPassword();
        echo $password;
        $first_name = null;
        $last_name = null;
        $country = null;
        $email = null;
        $aAlert = $report_id;
        $tempID = rand(0, 999);
        if(!empty($entries)){
            foreach($entries as $row){
//                sending email with credentials to the the one who generates alert
//                wp_mail( "$row->reporter_email", "Arena Login Module", $this->string_file['your_email1'] . $password . "<br>" . $this->string_file['your_email1'], array('Content-Type: text/html; charset=UTF-8'));
                $first_name = $row->reporter_fName;
                $last_name = $row->reporter_lName;
                $country = $row->reporter_residence;
                $email = $row->reporter_email;
            }
            echo "email sent";
        }
        $this->FLPChecker($email, $report_id);
        $data = array(
            'first_name' => $first_name,
            'last_name' => $last_name,
            'country' => $country,
            'email' => $email,
            'password' => $password,
            'associatedAlert' => $aAlert,
            'arenaTempID' => $tempID
        );
//        for generating alert without registering
//        $wpdb->insert("wp_arena", $data);
//        $wpdb->update("wp_alert_reports", array('tempID' => $tempID), array('report_id' => $report_id));
    }

    // this function will go in register module
    public function FLPChecker($Email, $report_id) {
        global $wpdb;
        $ArenaRegisteredEmail = $wpdb->get_results( "SELECT email FROM {$wpdb->prefix}arena", OBJECT );
        $ArenaRegisteredReportId = $wpdb->get_results( "SELECT associatedAlert FROM {$wpdb->prefix}arena WHERE email='$Email'", OBJECT );
        if ($ArenaRegisteredReportId[0]->associatedAlert === null) {
            $ArenaRegisteredReportId = $report_id;
        }
        else {
            $ArenaRegisteredReportId = $ArenaRegisteredReportId[0]->associatedAlert . "," . $report_id;
        }
        // append associated alert id when generating new alert
        for ($Counter = 0; $Counter<count($ArenaRegisteredEmail); $Counter++) {
            $ArenaEmail = $ArenaRegisteredEmail[$Counter]->email;
            if ($ArenaEmail === $Email) {
                // if matches then update entry in associatedalert i.e. add report_id of alert in associatedalert
                $wpdb -> update('wp_arena', array('associatedAlert' => $ArenaRegisteredReportId), array('email' => $ArenaEmail));
                $wpdb -> update('wp_arena', array('expert_type' => 'FLP'), array('email' => $ArenaEmail));
            }
        }
    }

    public function randomPassword() {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }

}
