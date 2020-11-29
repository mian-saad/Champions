<?php


namespace Comprise\Base\StateTypes;


use function WPMailSMTP\Vendor\GuzzleHttp\Psr7\str;

class TraComposedQuestion extends TraState {
    public $continue_string;
    public $lang;

    public function __construct($string_file, $report_id, $state_code, $state, $continue_string, $back_string, $field_warning, $warning) {
        $this->field_warning = $field_warning;
        $this->back_string = $back_string;
        $this->report_id = $report_id;
        $this->state_code = $state_code;
        $this->state = $state;
        $this->continue_string = $continue_string;
        $this->warning = $warning;
        $this->lang = $string_file;
    }

    public function generate_html() {
        $html="";
        if($this->state['show_header']=="true"){
            $html .= "<h3 class='register_question'>" . $this->state['state_text'] . "</h3>";
        }
        $html .= $this->generate_hidden_fields($this->report_id);
        $html .= "<form id='arena_question_form'>";

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
                    $html .= $this->generate_question_text($answer['text']);
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

    public function generate_checkbox_question($answer_array, $name_string) {
        $html = '<div class="register_checkbox_answers">';

        foreach ($answer_array['answers'] as $answer_option) {
            if (!empty($this->response) and in_array($answer_option['id'], $this->response[$name_string])) {
                // this is a checked answer
                if ($answer_option['id'] == $this->lang['other']) {
                    $html .= '<div class="register_horizontal_choice"><input type="checkbox" class="register_checkbox" name="' . $answer_array['id'] . '" id="' . $answer_option['id'] . '" value="' . $answer_option['id'] . '" checked required><label for="' . $answer_option['id'] . '">' . $answer_option['text'] . '</label> ' . $this->generate_other_text_input($this->response['other_text_input']) . '</div>';
                } else {
                    $html .= '<div class="register_horizontal_choice"><input type="checkbox" class="register_checkbox" name="' . $answer_array['id'] . '" id="' . $answer_option['id'] . '" value="' . $answer_option['id'] . '" checked required><label for="' . $answer_option['id'] . '">' . $answer_option['text'] . '</label></div>';
                }
            } else {
                if ($answer_option['id'] == $this->lang['other']) {
                    $html .= '<div class="register_horizontal_choice"><input type="checkbox" class="register_checkbox" name="' . $answer_array['id'] . '" id="' . $answer_option['id'] . '" value="' . $answer_option['id'] . '" required><label for="' . $answer_option['id'] . '">' . $answer_option['text'] . '</label> ' . $this->generate_other_text_input("") . '</div>';
                } else {
                    $html .= '<div class="register_horizontal_choice"><input type="checkbox" class="register_checkbox" name="' . $answer_array['id'] . '" id="' . $answer_option['id'] . '" value="' . $answer_option['id'] . '" required><label for="' . $answer_option['id'] . '">' . $answer_option['text'] . '</label></div>';
                }
            }

        }
        $html .= '</div>';
        return $html;
    }
    public function generate_other_text_input($value) {

        return "<input class='col-8 input-margin' type='text' name='other_text_input' value='" . $value . "'>";
    }
    // if every answer id is in response, and is not empty, return true
    public function validate($response) {
        $this->response = $response;

        foreach ($this->state['state_answers'] as $state_answer) {
            if (!(array_key_exists($state_answer['id'], $response) and $response[$state_answer['id']] != "")) {
                return false;
            }
        }
        return true;
    }

    public function generate_buttons() {
        return "<div id='register_button_pane'><a class='button' id='arena_back' href='#' onclick='return false;'>$this->back_string</a> <a class='button' id='arena_continue' href='#' onclick='return false;'>$this->continue_string</a></div>";
    }

    public function generate_select_question($answer) {
        $counter = 0; // need it for labeling and stuff
        $html = '<div class="register_select_answers ">';
        $html .= "<select id='title' name='". $answer['id'] ."'>";

        foreach ($answer['answers'] as $answer_option) {
            // if we got something in response
            if (!empty($this->response) and array_key_exists($answer['id'], $this->response) and $this->response[$answer['id']] == $answer_option['id']) {
                // this checkbox was checked previously
                $html .= '<div class="register_horizontal_choice"><option class="register_quiz_select" name="' . $answer['id'] . '" id="' . $answer_option['id'] . $counter . '" value="' . $answer_option['id'] . '" selected><label for="' . $answer_option['id'] . $counter . '">' . $answer_option['text'] . '</label></div>';
            } else { // else
                $html .= '<div class="register_horizontal_choice"><option class="register_quiz_select" name="' . $answer['id'] . '" id="' . $answer_option['id'] . $counter . '" value="' . $answer_option['id'] . '" ><label for="' . $answer_option['id'] . $counter . '">' . $answer_option['text'] . '</label></div>';
            }
            $counter += 1;
        }

        $html .= "</select>";
        $html .= '</div>';

        return $html;
    }

    public function generate_readable_response_array() {
        $result = [];
        $i = 0; // needed for koma stripping
//        $response_string = "";

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
                            if ($checkbox_answer['id'] == $this->lang['other'] and $this->response['other_text_input'] != "") {
                                $value = $checkbox_answer['text'] . " - " . $this->response['other_text_input'];
                            }
                            else {
                                $value = $checkbox_answer['text'];
                            }
                        }
                    }
                    else {
                        $value = implode(",", $this->response[$answer['id']]);
                        if ($checkbox_answer['id'] == $this->lang['other'] and $this->response['other_text_input'] != "") {
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