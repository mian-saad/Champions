<?php

namespace Cover\Base\StateTypes;

use Cover\Base\ReportController;
use function WPMailSMTP\Vendor\GuzzleHttp\Psr7\str;

class TraComposedQuestion extends TraState {
    public $continue_string;
    public $show_nouser;
    public $string_file;

    public function __construct($string_file, $alert_id, $state_code, $state, $continue_string, $back_string, $field_warning, $warning) {
        $this->field_warning = $field_warning;
        $this->back_string = $back_string;
        $this->alert_id = $alert_id;
        $this->state_code = $state_code;
        $this->state = $state;
        $this->continue_string = $continue_string;
        $this->warning = $warning;
        $this->string_file = $string_file;
    }

    public function generate_html() {
        $html="";
        $html = $this->NoUser();
        if($this->state['show_header']=="true"){
            $html .= "<h3 class='alert_question'>" . $this->state['state_text'] . "</h3>";
        }
        $html .= $this->generate_hidden_fields($this->alert_id);
        $html .= "<form id='alert_question_form'>";

        $totalItemPerLine = 1;
        $totalItem = 1;
        for($i = 0; $i < $totalItem; $i++) {
            if($i % $totalItemPerLine == 0) {
                $html .= '<div class="row">'; // OPEN ROW
            }
            foreach ($this->state['state_answers'] as $answer) {
                if ($answer['type'] == 'text') {
                    $html .= "<div class='col-6'>";
                    $html .= $this->generate_field_warning($answer['id']);
                    $html .= $this->generate_question_text($answer['text']);
                    $html .= "<input type='text' name='" . $answer['id'] . "' value='" . $this->response[$answer['id']] . "'><br>";
                    $html .= "</div>";
                }
                else if ($answer['type'] == 'select') {
                    $html .= "<div class='col-12'>";
                    $html .= $this->generate_question_text($answer['text']);
                    $html .= $this->generate_select_question($answer);
                    $html .= "</div>";
                }
                else if ($answer['type'] == 'checkbox') {
                    $html .= "<div class='col-12'>";
                    if ($answer['id'] == 'flp_title') {
                        $html .= "<div style='font-size: 22px'><b>".$answer['short_text']."</b></div>";
                    }
                    $html .= $this->generate_question_text($answer['text']);
                    if ($answer['text_description']) {
                        $html .= $this->generate_question_text($answer['text_description']);
                    }
                    $html .= $this->generate_checkbox_question($answer, $answer['id']);
                    $html .= "</div>";
                }
                else if ($answer['type'] == 'password') {
                    $html .= "<div class='col-6'>";
                    $html .= $this->generate_field_warning($answer['id']);
                    $html .= $this->generate_question_text($answer['text']);
                    $html .= "<input type='password' name='" . $answer['id'] . "' value='" . $this->response[$answer['id']] . "'><br>";
                    $html .= "</div>";
                }
            }
            if($i % $totalItemPerLine == ($totalItemPerLine-1)) {
                $html .= '</div>'; // CLOSE ROW
            }
        }

        $html .= "</form>";
        $html .= $this->generate_buttons();
        $html .= $this->generate_warning();
        return $html;
    }

    // if every answer id is in response, and is not empty, return true
    public function validate($resp) {
        $this->response = $resp;

        foreach ($this->state['state_answers'] as $state_answer) {
            if ($state_answer['optional']) {
                if ($state_answer['optional'] == 'true') {
                    return true;
                }
            }

            if (!(array_key_exists($state_answer['id'], $resp) and $resp[$state_answer['id']] != "")) {
                return false;
            }
        }

        if ($this->state['id'] == 'flp_login_infos') {
            global $wpdb;
            $arenaData = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}arena", OBJECT );
            for ($counter = 0; $counter<count($arenaData); $counter++) {
                if ($arenaData[$counter]->flp_email === $resp['flp_email']) {
                    if ($arenaData[$counter]->flp_password === $resp['flp_password']) {
//                        $report_controller = new ReportController();
                        $this->response = null;
//                        $report_controller->flp_id = $arenaData[$counter]->flp_id;
                        $this->response['flp_id'] = $arenaData[$counter]->flp_id;
                        $_SESSION['validate'] = true;
                        $this->alert_id = $arenaData[$counter]->alert_id .",". $this->alert_id;
                        $wpdb->update("wp_arena", array('alert_id' => $this->alert_id), array('flp_email' => $arenaData[$counter]->flp_email));
                        $wpdb->update("wp_arena", array('flp_associatedAlert' => $this->alert_id), array('flp_email' => $arenaData[$counter]->flp_email));
                        return true;
                    }
                }
            }
            return "NoUser";
        }
        elseif ($this->state['id'] == 'flp_register_infos') {
            $verifyCode = rand(1111, 9999);
            $_SESSION["verifyCode"] = $verifyCode;

            wp_mail( $resp['flp_email'], $this->string_file['verification'], $this->string_file['your_verification_code'] . $verifyCode );
            return true;
        }
    }

    public function NoUser() {
        if ($this->show_nouser) {
            return "<p class='NoUser'>".$this->string_file['no_user']."</p>";
        }
        else {
            return "";
        }

    }

    public function generate_buttons() {
        $html = "";
        if ($this->state['id'] == 'flp_login_infos') {
            $html .= "<div id='alert_button_pane'><a class='button' id='alert_back' href='#' onclick='return false;'>$this->back_string</a> <a class='button' id='authenticate' href='#' onclick='return false;'>$this->continue_string</a></div>";
            $html .= "<br>";
            $html .= "<p>".$this->string_file['register_now']."</p>";
            $html .= "<button id='alert_register' class='button'>".$this->string_file['register_btn']."</button>";
        }
        else {
            $html .=  "<div id='alert_button_pane'><a class='button' id='alert_back' href='#' onclick='return false;'>$this->back_string</a> <a class='button' id='alert_continue' href='#' onclick='return false;'>$this->continue_string</a></div>";
        }
        return $html;
    }

    public function generate_checkbox_question($answer_array, $name_string) {
        $html = '<div class="register_checkbox_answers">';

        if (is_string($this->response[$name_string])) {
            $this->response[$name_string] = str_split($this->response[$name_string], strlen($this->response[$name_string]));
        }

        foreach ($answer_array['answers'] as $answer_option) {
            if (!empty($this->response) and in_array($answer_option['id'], $this->response[$name_string])) {
                // this is a checked answer
                if ($answer_option['id'] == 'Other') {
                    $html .= '<div class="register_horizontal_choice"><input type="checkbox" class="register_checkbox" name="' . $answer_array['id'] . '" id="' . $answer_option['id'] . '" value="' . $answer_option['id'] . '" checked required><label for="' . $answer_option['id'] . '">' . $answer_option['text'] . '</label> ' . $this->generate_other_text_input($this->response['other_text_input']) . '</div>';
                } else {
                    $html .= '<div class="register_horizontal_choice"><input type="checkbox" class="register_checkbox" name="' . $answer_array['id'] . '" id="' . $answer_option['id'] . '" value="' . $answer_option['id'] . '" checked required><label for="' . $answer_option['id'] . '">' . $answer_option['text'] . '</label></div>';
                }
            } else {
                if ($answer_option['id'] == 'Other') {
                    $html .= '<div class="register_horizontal_choice"><input type="checkbox" class="register_checkbox" name="' . $answer_array['id'] . '" id="' . $answer_option['id'] . '" value="' . $answer_option['id'] . '" required><label for="' . $answer_option['id'] . '">' . $answer_option['text'] . '</label> ' . $this->generate_other_text_input("") . '</div>';
                } else {
                    $html .= '<div class="register_horizontal_choice"><input type="checkbox" class="register_checkbox" name="' . $answer_array['id'] . '" id="' . $answer_option['id'] . '" value="' . $answer_option['id'] . '" required><label for="' . $answer_option['id'] . '">' . $answer_option['text'] . '</label></div>';
                }
            }

        }
        $html .= '</div>';
        return $html;
    }

    public function generate_other_text_input($value)
    {
        return "<input class='col-8 input-margin' type='text' value='" . $value . "'>";
    }

    public function generate_select_question($answer) {
        $counter = 0; // need it for labeling and stuff
        $html = '<div class="alert_select_answers ">';
        $html .= "<select id='".$answer['id']."' name='".$answer['id']."'>";

        foreach ($answer['answers'] as $answer_option) {
            // if we got something in response
            if (!empty($this->response) and array_key_exists($answer['id'], $this->response) and $this->response[$answer['id']] == $answer_option['id']) {
                // this checkbox was checked previously
                $html .= '<div class="alert_horizontal_choice"><option class="alert_quiz_select" name="' . $answer['id'] . '" id="' . $answer_option['id'] . $counter . '" value="' . $answer_option['id'] . '" selected><label for="' . $answer_option['id'] . $counter . '">' . $answer_option['text'] . '</label></div>';
            } else { // else
                $html .= '<div class="alert_horizontal_choice"><option class="alert_quiz_select" name="' . $answer['id'] . '" id="' . $answer_option['id'] . $counter . '" value="' . $answer_option['id'] . '" ><label for="' . $answer_option['id'] . $counter . '">' . $answer_option['text'] . '</label></div>';
            }
            $counter += 1;
        }

        $html .= "</select>";
        $html .= '</div>';

        return $html;
    }

    public function generate_readable_response_array() {

        $result = [];

        foreach ($this->state['state_answers'] as $answer) {
            $key = $answer['short_text'];
            if ($answer['type'] == 'text') { // for text answers , just store the received input
                $value = $this->response[$answer['id']];
            }
            else if ($answer['type'] == 'select') { // need to search in the answer array for the response id in the answers
                foreach ($answer['answers'] as $radio_answer) {
                    if ($this->response[$answer['id']] == $radio_answer['id']) {
                        $value = $radio_answer['short_text'];
                    }
                }
            }
            else if ($answer['type'] == 'checkbox') {
                foreach ($answer['answers'] as $checkbox_answer) {
                    if (count($this->response[$answer['id']]) < 2) {
                        if ($this->response[$answer['id']] == $checkbox_answer['id']) {
                            // handle the other_text_input here
                            if ($checkbox_answer['id'] == 'other' and $this->response['other_text_input'] != "") {
                                $value = $checkbox_answer['text'] . " - " . $this->response['other_text_input'];
                            }
                            else {
                                $value = $checkbox_answer['text'];
                            }
                        }
                    }
                    else {
                        $value = implode(",", $this->response[$answer['id']]);
                        if ($checkbox_answer['id'] == 'Other' and $this->response['other_text_input'] != "") {
                            $value = $value . " - " . $this->response['other_text_input'];
                        }
                    }

                }
            }
            $result[$key] = $value;
        }
        return $result;
    }
}