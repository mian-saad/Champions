<?php
/**
 * '@package AlertstatesPlugin
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

    // initializes the controller object
    public function init($alert_id, $language) {
        $this->alert_id = $alert_id;
        $this->language = $language;

        # DEBUG: here you can chose the first state for debugging, in production the first state is M0.0
        $this->current_state_code = "M0.0";

        $this->state_list = [];
        $this->oldstate = null;
        $this->current_step_counter = 0;

        // reads json
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

//        $_SESSION['state_code'] = $this->oldstate;
        // IMPORTANT: GENERATES THE HTML
        $html = $state->generate_html();
        return $html;
    }

    // taking the new state code string, it takes it from the states and initiates a new object
    public function initialize_state($state_code) {
        $state = $this->state_file[$state_code];

        if ($state['state_type'] == 'composed') {
            $state_obj = new StateTypes\TraComposedQuestion($this->string_file, $this->alert_id, $state_code, $state, $this->string_file['continue'], $this->string_file['back'], $this->string_file['field_warning'], $this->string_file['warning']);
        } else if ($state['state_type'] == 'EventComposed') {
            $state_obj = new StateTypes\TraDatetimeQuestion($this->alert_id, $state_code, $state, $this->language, $this->string_file['continue'], $this->string_file['back'], $this->string_file['field_warning'], $this->string_file['wrong_time_warning']);
        } else if ($state['state_type'] == 'checkbox') {
            $state_obj = new StateTypes\TraCheckboxQuestion($this->string_file, $this->alert_id, $state_code, $state, $this->string_file['continue'], $this->string_file['back'], $this->string_file['field_warning'], $this->string_file['warning']);
        } else if ($state['state_type'] == 'composed_checkbox') {
            $state_obj = new StateTypes\TraComposedCheckboxQuestion($this->alert_id, $state_code, $state, $this->string_file['continue'], $this->string_file['back'], $this->string_file['field_warning'], $this->string_file['warning'], $this->string_file['other']);
        } else if ($state['state_type'] == 'verification') {
            $state_obj = new StateTypes\VerificationCode($this->alert_id, $state_code, $state, $this->string_file['continue'], $this->string_file['back'], $this->string_file['field_warning'], $this->string_file['warning'], $this->string_file['complete_registration_string']);
        } else if ($state['state_type'] == 'description') {
            $state_obj = new StateTypes\TraDescriptionQuestion($this->alert_id, $state_code, $state, $this->string_file['continue'], $this->string_file['back'], $this->string_file['field_warning'], $this->string_file['warning']);
        } else if ($state['state_type'] == 'result') {
            // Pass all the recorded answers
            $answers = [];
            $eng_answers = [];
            foreach ($this->state_list as $code => $state) {
                if ($code == "M1.2") {
                    $answers += $state->generate_readable_response_array();
                    $eng_answers += $state->generate_readable_response_array_eng();
                }
                else {
                    $answers += $state->generate_readable_response_array();
                }
            }
            $pdfurl = $this->generate_pdf($answers);
            $state_obj = new StateTypes\TraFinal($eng_answers, $this->string_file, $this->string_file['summary'], $this->alert_id, $state_code, $answers, $this->string_file['pdf_download'], $this->string_file['back'],  $this->string_file['no_results'],$pdfurl, $this->isValidated);
        } else if ($state['state_type'] == 'gdpr') {
            $state_obj = new StateTypes\TraGDPR($this->alert_id, $state_code, $state, $this->string_file['gdpr_accept'], $this->string_file['gdpr_warning']);
        } else {
            // else we are experiencing an error
            $state_obj = new StateTypes\TraError($this->alert_id, $state_code, $state['state_text'], $this->string_file['continue']);
        }

        $this->state_list[$state_code] = $state_obj;
        return $state_obj;
    }

    // unset the current step if we go back
    public function step_back() {
        $this->current_step_counter = $this->current_step_counter - 1;

        // Reload page if our step counter < 0
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

            // IMPORTANT PLACE, HERE WE ADVANCE THE STATE CODE
            $this->current_state_code = $this->get_next_state();
            $this->current_step_counter = $this->current_step_counter + 1;

            // lets see if we are trying to change our questionnaire tree, if so, lets unset old states
            if ($this->current_step_counter < count($this->state_list)) {

                // in the statelist
                if ($this->current_state_code != array_keys($this->state_list)[$this->current_step_counter]) {

                    // Changing the tree
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
        // else printing some warning
        else {
            $state_obj->show_warning = true;
        }
    }

    // state advancing logic, happens after a state answer is validated
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

        // create TCPDF object with default constructor args
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->setFont('freeserif');

        // pretty self-explanatory
        $pdf->AddPage();
        header('Content-Type: text/html; charset=utf-8');
        $html = "<h1>".$this->string_file['alert_report']."</h1><br>";
        foreach ($answers as $short_text => $value) {
            $html .= "<p>" . $short_text . " : " . $value . "</p>";
        }

        // output the HTML content
        $pdf->writeHTML($html, true, false, true, false, '');
        ob_clean();
        $pdf->Output($this->plugin_path . 'pdf/' . $this->alert_id . '.pdf', 'F');
        return $this->plugin_url . 'pdf/' . $this->alert_id . '.pdf';
    }

    public function delete_entry($wpdb, $alert_db) {

        $entries = $wpdb->get_results("SELECT alert_id FROM ".$alert_db." WHERE alert_id=\"".$this->alert_id."\"");
        if(sizeof($entries)!= 0){
            $wpdb->get_results("DELETE FROM ".$alert_db." WHERE alert_id=\"".$this->alert_id."\"");
        }
    }

    public function alert_db_store() {

        global $wpdb;
        $alert_db = $wpdb->prefix . 'alert';

        $answers = $this->sort_alert();
        $wpdb->insert($alert_db, $answers);

        $this->alert_mail($wpdb, $answers);
    }

    public function sort_alert() {

        $answers = [
            'alert_id' => $this->alert_id,
            'alert_report_time' => date("Y/m/d H:i"),
            'alert_report_locale' => $this->language,
            'alert_report_ip' => $_SERVER['REMOTE_ADDR']
        ];

        foreach ($this->state_list as $code => $state) {
            if ($code == "M1.1" || $code == "M1.2" || $code == "M1.3" || $code == "M1.5" && $this->oldstate !== "M1.9") {
                foreach ($state->response as $entry => $value) {
                    if (is_array($value)){
                        $answers[$entry] = implode('~~~', $value);
                    }
                    else {
                        $answers[$entry] = $value;
                    }
                }
            }
        }
        if ($this->flp_id) {
            $answers = $answers + array('flp_id' => $this->flp_id);
        }
        return $answers;
    }

    public function alert_mail($wpdb, $answers) {
        $flp = $answers['flp_id'];
        $current_email = $wpdb->get_results("SELECT flp_email FROM wp_arena WHERE flp_id='{$flp}'");
        wp_mail( $current_email[0]->flp_email, $this->string_file['alert_submitted'], $this->string_file['your_alert_submitted']);
    }

    public function flp_db_store($flp_id) {

        global $wpdb;
        $arena_db = $wpdb->prefix . 'arena';

        $answers = $this->sort_flp($flp_id);
        $wpdb->insert($arena_db, $answers);

        $this->flp_mail($answers);
    }

    public function sort_flp($flp_id) {
        $answers = [
            'flp_id' => $flp_id,
            'flp_registration_time' => date("Y/m/d H:i"),
            'flp_locale' => $this->language,
            'flp_reporting_ip' => $_SERVER['REMOTE_ADDR'],
            'alert_id' => $this->alert_id,
            'flp_associatedAlert' => $this->alert_id
        ];

        foreach ($this->state_list as $code => $state) {
            if ($code == "M1.6" || $code == "M1.7" || $code == "M1.8") {
                foreach ($state->response as $key => $value) {
                    if (is_array($value)) {
                        $answers[$key] = implode('~~~', $value);
                    }
                    else {
                        $answers[$key] = $value;
                    }
                }
            }
        }
        return $answers;
    }

    public function flp_mail($answers) {
        wp_mail( $answers['flp_email'], $this->string_file['registration_confirmed'], $this->string_file['your_registration_confirmed']);
    }

    public function alert_confirmation() {
        $confirmation = new StateTypes\Done($_SESSION['language']);
        $confirmation->show_message();
    }

    /* END OF CLASS */
}
