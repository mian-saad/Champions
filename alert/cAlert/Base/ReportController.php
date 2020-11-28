<?php
/**
 * @package  TakedownstatesPlugin
 */
namespace Cover\Base;

use TCPDF;
use \Cover\Base\StateTypes;

class ReportController extends BaseController {
    public $language;
    public $flp_id;
    public $alert_id; // id of report, which is being taken
    public $current_state_code; // id of the current state (which is currently presented to the viewer)
    public $oldstate;

    public $state_file; // associative array, contains the states json file
    public $string_file; // associative array, contains all strings with code as key
    public $isValidated;
    public $state_list;
    public $current_step_counter; // counter for iterating through the state list

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

    // initializes the controller object
    public function init($alert_id, $language) {
        $this->alert_id = $alert_id;
        $this->language = $language;

        # DEBUG: here you can chose the first state for debugging, in production the first state is M0.0
        $this->current_state_code = "M0.0";

        $this->report_answers = [];
        $this->state_list = [];
        $this->oldstate = null;
        $this->current_step_counter = 0;

        // read json
        $this->state_file = json_decode(file_get_contents($this->plugin_path . "assets/base/" . $language . "/alert_states.json"), true);
        $this->string_file = json_decode(file_get_contents($this->plugin_path . "assets/base/" . $language . "/alert_strings.json"), true);
    }

    // generates the content for the current state
    public function generate_content() {
        if (array_key_exists($this->current_state_code, $this->state_list)) {
            $state = $this->state_list[$this->current_state_code];
        }
        else {
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
    public function initialize_state($state_code) {
        $state = $this->state_file[$state_code];

        if ($state['state_type'] == 'composed') {
            $state_obj = new StateTypes\TraComposedQuestion($this->string_file, $this->alert_id, $state_code, $state, $this->string_file['continue'], $this->string_file['back'], $this->string_file['field_warning'], $this->string_file['warning']);
        } else if ($state['state_type'] == 'EventComposed') {
            $state_obj = new StateTypes\TraDatetimeQuestion($this->alert_id, $state_code, $state, $this->language, $this->string_file['continue'], $this->string_file['back'], $this->string_file['field_warning'], $this->string_file['wrong_time_warning']);
        } else if ($state['state_type'] == 'checkbox') {
            $state_obj = new StateTypes\TraCheckboxQuestion($this->alert_id, $state_code, $state, $this->string_file['continue'], $this->string_file['back'], $this->string_file['field_warning'], $this->string_file['warning']);
        } else if ($state['state_type'] == 'composed_checkbox') {
            $state_obj = new StateTypes\TraComposedCheckboxQuestion($this->alert_id, $state_code, $state, $this->string_file['continue'], $this->string_file['back'], $this->string_file['field_warning'], $this->string_file['warning'], $this->string_file['other']);
        } else if ($state['state_type'] == 'verification') {
            $state_obj = new StateTypes\VerificationCode($this->alert_id, $state_code, $state, $this->string_file['continue'], $this->string_file['back'], $this->string_file['field_warning'], $this->string_file['warning'], $this->string_file['complete_registration_string']);
//            $state_obj = new StateTypes\TraFinal        ($this->alert_id, $state_code, $this->string_file['back']);
        } else if ($state['state_type'] == 'description') {
            $state_obj = new StateTypes\TraDescriptionQuestion($this->alert_id, $state_code, $state, $this->string_file['continue'], $this->string_file['back'], $this->string_file['field_warning'], $this->string_file['warning']);
        } else if ($state['state_type'] == 'result') {
            // here we need to pass all the recorded answers
            $answers = [];
            foreach ($this->state_list as $code => $state) {
                $answers += $state->generate_readable_response_array();
            }
            $pdfurl = $this->generate_pdf($answers);
            $state_obj = new StateTypes\TraFinal($this->string_file, $this->string_file['summary'], $this->alert_id, $state_code, $answers, $this->string_file['pdf_download'], $this->string_file['back'], $this->string_file['crime_location_proposals'], $this->string_file['language_pref_proposals'], $this->string_file['residence_state_proposals'], $this->string_file['no_results'],$pdfurl, $this->isValidated);
        } else if ($state['state_type'] == 'gdpr') {
            $state_obj = new StateTypes\TraGDPR($this->alert_id, $state_code, $state, $this->string_file['gdpr_accept'], $this->string_file['gdpr_warning']);
        } else { // else we are expiriencing an error
            $state_obj = new StateTypes\TraError($this->alert_id, $state_code, $state['state_text'], $this->string_file['continue']);
        }

        $this->state_list[$state_code] = $state_obj;
        return $state_obj;
    }

    // lets unset the current step if we go back
    public function step_back() {
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
    public function process_response($response) {
        $state_obj = $this->state_list[$this->current_state_code];
        $validation_response = $state_obj->validate($response);

        if (($validation_response || $response=='register') && $validation_response !== 'NoUser') { // if answer is valid, lets advance the state
            $state_obj->show_warning = false;

            $this->oldstate = $this->current_state_code;
            $this->current_state_code = $this->get_next_state(); // <=== IMPORTANT PLACE, HERE WE ADVANCE THE STATE CODE
            $this->current_step_counter = $this->current_step_counter + 1;
            // lets see if we are trying to change our questionnaire tree, if so, lets unset old states
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
        }
        else if($validation_response == 'NoUser') {
            $state_obj->show_nouser = true;
        }
        // else we should be printing some warning
        else {
            $state_obj->show_warning = true;
        }
    }

    // state advancing logic, hapens after a state answer was validated
    public function get_next_state() {

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
        } else if ($this->current_state_code == "M1.5") {
            if ($_GET['validate'] == 'validate') {
                return "M1.4";
            }
            return "M1.6";
        } else if ($this->current_state_code == "M1.6") {
            return "M1.7";
        } else if ($this->current_state_code == "M1.7") {
            return "M1.8";
        } else if ($this->current_state_code == "M1.8") {
            return "M1.9";
        } else if ($this->current_state_code == "M1.9") {
            return "M1.4";
        }

        return "ERROR";
    }

    public function generate_pdf($answers) {
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false); // create TCPDF object with default constructor args
        $pdf->setFont('freeserif');
        $pdf->AddPage(); // pretty self-explanatory

        header('Content-Type: text/html; charset=utf-8');

        $html = "<h1>".$this->string_file['alert_report']."</h1><br>";

        foreach ($answers as $short_text => $value) {
            $html .= "<p>" . $short_text . " : " . $value . "</p>";
        }

        // output the HTML content
        $pdf->writeHTML($html, true, false, true, false, '');

        ob_clean();

        $pdf->Output($this->plugin_path . 'reports/' . $this->alert_id . '.pdf', 'F');
        return $this->plugin_url . 'reports/' . $this->alert_id . '.pdf';
    }

    public function db_store() {
        global $wpdb;
        $alert_db = $wpdb->prefix . 'alert';

        // lets delete entry if it already exists in the db
        $entries = $wpdb->get_results("SELECT alert_id FROM ".$alert_db." WHERE alert_id=\"".$this->alert_id."\"");
        if(sizeof($entries)!= 0){
            $wpdb->get_results("DELETE FROM ".$alert_db." WHERE alert_id=\"".$this->alert_id."\"");
        }

        if ($this->oldstate == "M1.9") {
            $answers = [
                'alert_id' => $this->alert_id,
                'alert_report_time' => date("Y/m/d H:i"),
                'alert_report_locale' => $this->language,
                'alert_report_ip' => $_SERVER['REMOTE_ADDR'],
                'flp_id' => $this->flp_id
            ];
        }
        else {
            $answers = [
                'alert_id' => $this->alert_id,
                'alert_report_time' => date("Y/m/d H:i"),
                'alert_report_locale' => $this->language,
                'alert_report_ip' => $_SERVER['REMOTE_ADDR']
            ];
        }

        foreach ($this->state_list as $code => $state) {
            if ($code != "M1.4" ) {

                if ($this->oldstate == "M1.9") {
                    if ($code == "M1.5" || $code == "M1.6" || $code == "M1.7" || $code == "M1.8" || $code == "M1.9") {
                        echo "Not to be Included !";
                    }
                    else {

                        if (is_array($state->response)) {
                            if (!isset($state->response[$state->state['id']])) {  // only the case for composed questions
                                $response = $state->response;
                            }
                            else if (is_array($state->response[$state->state['id']])) {      // is only the case for checkbox question
                                if (in_array('Other', $state->response[$state->state['id']]) AND isset($state->response['other_text_input'])) {
                                    foreach ($state->response[$state->state['id']] as &$str) {
                                        $str = str_replace('Other', $state->response['other_text_input'], $str);
                                    }
                                    unset($state->response['other_text_input']);
                                }
                                $response = array($state->state['id'] => implode(",", $state->response[$state->state['id']]));
                            }
                            else {  // main response is set and is not an array
                                if ($state->response[$state->state['id']] == 'Other') {
                                    $state->response[$state->state['id']] = $state->response['other_text_input'];
                                }
                                unset($state->response['other_text_input']);
                                $response = $state->response;
                            }
                            $answers = $answers + $response;
                        }
                        else {  // if response is some text, lets create an array with its state id and add it to answers array
                            $answers = $answers + array($state->state['id'] => $state->response);
                        }
                    }
                }
                else {
                    if (is_array($state->response)) {
                        if (!isset($state->response[$state->state['id']])) {  // only the case for composed questions

                            foreach ($state->state['state_answers'] as $type) {
                                if (is_array($state->response[$type['id']])) {
                                    $state->response[$type['id']] = implode(", ", $state->response[$type['id']]);
                                }
                            }

                            $response = $state->response;
                        }
                        /*else if (is_array($state->response[$state->state['id']])) {      // is only the case for checkbox question
                            if (in_array('Other', $state->response[$state->state['id']]) AND isset($state->response['other_text_input'])) {
                                foreach ($state->response[$state->state['id']] as &$str) {
                                    $str = str_replace('Other', $state->response['other_text_input'], $str);
                                }
                                unset($state->response['other_text_input']);
                            }
                            $response = array($state->state['id'] => implode(",", $state->response[$state->state['id']]));
                        }
                        else {  // main response is set and is not an array
                            if ($state->response[$state->state['id']] == 'Other') {
                                $state->response[$state->state['id']] = $state->response['other_text_input'];
                            }
                            unset($state->response['other_text_input']);
                            $response = $state->response;
                        }*/
                        $answers = $answers + $response;
                    }
                    else {  // if response is some text, lets create an array with its state id and add it to answers array
                        $answers = $answers + array($state->state['id'] => $state->response);
                    }
                }


            }
        }
        $wpdb->insert($alert_db, $answers);
        wp_mail( $_SESSION['flp'], "ALERT Submitted", "Your ALERT has been submitted. You will be able to see the ALERT in ARENA panel once it has been accepted by the Moderator.");
    }

    public function flp_db_store($flp_id) {
        global $wpdb;
        $arena_db = $wpdb->prefix . 'arena';

        // lets delete entry if it already exists in the db
        // ToDo: Change this as well
        $entries = $wpdb->get_results("SELECT flp_id FROM ".$arena_db." WHERE flp_id=\"".$flp_id."\"");
        if(sizeof($entries)!= 0){
            $wpdb->get_results("DELETE FROM ".$arena_db." WHERE flp_id=\"".$flp_id."\"");
        }

        // toDo: change according to database arena
        $answers = [
            'flp_id' => $flp_id,
            'flp_registration_time' => date("Y/m/d H:i"),
            'flp_locale' => $this->language,
            'flp_reporting_ip' => $_SERVER['REMOTE_ADDR'],
            'alert_id' => $this->alert_id,

        ];

        foreach ($this->state_list as $code => $state) {
            if ($code == "M1.6" || $code == "M1.7" || $code == "M1.8") {

                // --- make flp_title better
                if (count($state->response['flp_title']) > 1) {
                    $state->response['flp_title'] = implode(',', $state->response['flp_title']);
                }
                if (!empty($state->response['other_text_input'])) {
                    $state->response['flp_title'] = $state->response['flp_title'] .','. $state->response['other_text_input'];
                }
                unset($state->response['other_text_input']);
                // ---

//                if ($this->oldstate == "M1.9") {
//                    if ($code == "M1.5" || $code == "M1.6" || $code == "M1.7" || $code == "M1.8" || $code == "M1.9") {
//                        echo "Not to be Included !";
//                    }
//                    else {
                        if (is_array($state->response)) {
                            if (!isset($state->response[$state->state['id']])) {  // only the case for composed questions
                                $response = $state->response;
                            }
                            else if (is_array($state->response[$state->state['id']])) {      // is only the case for checkbox question
                                if (in_array('Other', $state->response[$state->state['id']]) AND isset($state->response['other_text_input'])) {
                                    foreach ($state->response[$state->state['id']] as &$str) {
                                        $str = str_replace('Other', $state->response['other_text_input'], $str);
                                    }
                                    unset($state->response['other_text_input']);
                                }
                                $response = array($state->state['id'] => implode(",", $state->response[$state->state['id']]));
                            }
                            else {  // main response is set and is not an array
                                if ($state->response[$state->state['id']] == 'Other') {
                                    $state->response[$state->state['id']] = $state->response['other_text_input'];
                                }
                                unset($state->response['other_text_input']);
                                $response = $state->response;
                            }
                            $answers = $answers + $response;
                        }
                        else {  // if response is some text, lets create an array with its state id and add it to answers array
                            $answers = $answers + array($state->state['id'] => $state->response);
                        }
//                    }
//                }
//                else {
//                    if (is_array($state->response)) {
//                        if (!isset($state->response[$state->state['id']])) {  // only the case for composed questions
//                            $response = $state->response;
//                        }
//                        else if (is_array($state->response[$state->state['id']])) {      // is only the case for checkbox question
//                            if (in_array('Other', $state->response[$state->state['id']]) AND isset($state->response['other_text_input'])) {
//                                foreach ($state->response[$state->state['id']] as &$str) {
//                                    $str = str_replace('Other', $state->response['other_text_input'], $str);
//                                }
//                                unset($state->response['other_text_input']);
//                            }
//                            $response = array($state->state['id'] => implode(",", $state->response[$state->state['id']]));
//                        }
//                        else {  // main response is set and is not an array
//                            if ($state->response[$state->state['id']] == 'Other') {
//                                $state->response[$state->state['id']] = $state->response['other_text_input'];
//                            }
//                            unset($state->response['other_text_input']);
//                            $response = $state->response;
//                        }
//                        $answers = $answers + $response;
//                    }
//                    else {  // if response is some text, lets create an array with its state id and add it to answers array
//                        $answers = $answers + array($state->state['id'] => $state->response);
//                    }
//                }

            }
        }
        $wpdb->insert($arena_db, $answers);
        $_SESSION['flp'] = $answers['flp_email'];
        wp_mail( $answers['flp_email'], "Registration Confirmed", "Your registration has been confirmed. You will be able to login once the Moderator accepts your request.");
    }

    /* create one more db function in process response check if its at the state M1.9
    if yes then call the new db function and slice the array in it
    and when the original db_store is called slice the array there if some other logic is not implemented
    */
}
