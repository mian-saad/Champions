<?php

namespace Cover\Base\StateTypes;

class TraDatetimeQuestion extends TraState {
    public $continue_string;
    public $locale;

    public function __construct($alert_id, $state_code, $state, $locale, $continue_string, $back_string, $field_warning, $warning) {
        $this->field_warning = $field_warning;
        $this->back_string = $back_string;
        $this->locale = $locale;
        $this->alert_id = $alert_id;
        $this->state_code = $state_code;
        $this->state = $state;
        $this->continue_string = $continue_string;
        $this->warning = $warning;
    }

    public function generate_html() {
        $html = $this->generate_hidden_fields($this->alert_id);

        if($this->state['show_header'] === 'true') {
            $html .= "<h3 class='alert_question'>" . $this->state['state_text'] . "</h3>";
        }
        $html .= "<form id='alert_question_form'>";
        $html .= "<script>jQuery.datetimepicker.setLocale('$this->locale')</script>";
        foreach ($this->state['state_answers'] as $answer) {
            $html .= $this->generate_question_text($answer['text']);
            if ($answer['type'] == 'select') {
                $html .= $this->generate_select_question($answer);
//                $html .= "<input name='" . $answer['id'] . "' value='" . $this->response[$answer['id']] . "'><br>";
            }
            if ($answer['type'] == 'text') {
                $html .= "<input type='text' name='" . $answer['id'] . "' value='" . $this->response[$answer['id']] . "'><br>";
            }
            if ($answer['type'] == 'datetime') {
                $html .= "<input class='picker' type='text' name='" . $answer['id'] . "' value='" . $this->response[$answer['id']] . "'><br>";
            }
        }
        $html .= "</form>";
        $html .= $this->generate_buttons();
        $html .= $this->generate_warning();
        return $html;
    }

    // if every answer id is in response, and is not empty, return true
    public function validate($response) {
        $this->response = $response;

        foreach ($this->state['state_answers'] as $state_answer) {
            if (!(array_key_exists($state_answer['id'], $response) and $response[$state_answer['id']] != "")) {
                if ($state_answer['optional'] == "Yes") {
                    return true;
                }
                return false;
            }
        }
        return true;
    }

    public function generate_buttons() {
        return "<div id='alert_button_pane'><a class='button' id='alert_back' href='#' onclick='return false;'>$this->back_string</a> <a class='button' id='alert_continue' href='#' onclick='return false;'>$this->continue_string</a></div>";
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
//        $result[$this->state['short_text']] = $this->response[$this->state['id']];
//        return $result;

        /*$result = [];

        foreach ($this->state['state_answers'] as $answer) {
            $key = $answer['short_text'];
            if ($answer['type'] == 'text') { // for text answers , just store the received input
                $value = $this->response[$answer['id']];
            }
            else if ($answer['type'] == 'datetime') { // for text answers , just store the received input
                $value = $this->response[$answer['id']];
            }
            else if ($answer['type'] == 'select') { // need to search in the answer array for the response id in the answers
                foreach ($answer['answers'] as $radio_answer) {
                    if ($this->response[$answer['id']] == $radio_answer['id']) {
                        $value = $radio_answer['short_text'];
                    }
                }
            }
            $result[$key] = $value;
        }
        return $result;*/



        $result = [];

        foreach ($this->state['state_answers'] as $answer) {
            $key = $answer['short_text'];
            if ($answer['type'] == 'text') { // for text answers , just store the received input
                $value = $this->response[$answer['id']];
            }
            else if ($answer['type'] == 'datetime') { // for text answers , just store the received input
                $value = $this->response[$answer['id']];
            }
            else if ($answer['type'] == 'select') { // need to search in the answer array for the response id in the answers
                foreach ($answer['answers'] as $radio_answer) {
                    if ($this->response[$answer['id']] == $radio_answer['id']) {
                        $value = $radio_answer['short_text'];
                    }
                }
            }
            $result[$key] = $value;
        }
        return $result;
    }
}