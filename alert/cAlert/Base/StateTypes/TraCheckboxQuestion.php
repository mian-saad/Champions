<?php

namespace Cover\Base\StateTypes;

class TraCheckboxQuestion extends TraState
{
    public $continue_string;
    public $lang;

    public function __construct($string_file, $alert_id, $state_code, $state, $continue_string, $back_string, $field_warning, $warning)
    {
        $this->field_warning = $field_warning;
        $this->back_string = $back_string;
        $this->alert_id = $alert_id;
        $this->state_code = $state_code;
        $this->state = $state;
        $this->continue_string = $continue_string;
        $this->warning = $warning;
        $this->lang = $string_file;
    }

    public function generate_html()
    {
        $html = $this->generate_hidden_fields($this->alert_id);
        $html .= $this->generate_question_title($this->state['short_text']);
        $html .= "<form id='alert_question_form'>";
        $html .= $this->generate_question_text($this->state['state_text']);
        $html .= $this->generate_checkbox_question($this->state['state_answers'], $this->state['id']);
        $html .= "</form>";
        $html .= $this->generate_buttons();
        $html .= $this->generate_warning();
        return $html;
    }

    // if at least one category is chosen, then we pass the validation
    public function validate($response)
    { // how do we wanna store the items to the db
        if (array_key_exists($this->state['id'], $response) and $response[$this->state['id']] != null) {
            if (is_array($response[$this->state['id']])) {
                $this->response = $response;
            } else {
                $this->response[$this->state['id']] = array($response[$this->state['id']]);
                $this->response['other_text_input'] = $response['other_text_input'];
            }
            return true;
        }

        return false;
    }

    public function generate_checkbox_question($answer_array, $name_string)
    {
        $html = '<div class="register_checkbox_answers">';

        $i = 0;
        foreach ($answer_array as $answer_option) {
            if (!empty($this->response) and in_array($answer_option['id'], $this->response[$name_string])) {
                // this is a checked answer
                if ($answer_option['text'] == $this->lang['other']) {
                    $html .= '<div class="register_horizontal_choice"><input type="checkbox" class="register_checkbox" name="' . $this->state['id'] . '" id="' . $answer_option['id'] . $i . '" value="' . $answer_option['id'] . '" checked required><label for="' . $answer_option['id'] . '">' . $answer_option['text'] . '</label> ' . $this->generate_other_text_input($this->response['other_text_input']) . '</div>';
                } else {
                    $html .= '<div class="register_horizontal_choice"><input type="checkbox" class="register_checkbox" name="' . $this->state['id'] . '" id="' . $answer_option['id'] . '" value="' . $answer_option['id'] . '" checked required><label for="' . $answer_option['id'] . '">' . $answer_option['text'] . '</label></div>';
                }
            } else {
                if ($answer_option['text'] == $this->lang['other']) {
                    $html .= '<div class="register_horizontal_choice"><input type="checkbox" class="register_checkbox" name="' . $this->state['id'] . '" id="' . $answer_option['id'] . $i . '" value="' . $answer_option['id'] . '" required><label for="' . $answer_option['id'] . '">' . $answer_option['text'] . '</label> ' . $this->generate_other_text_input("") . '</div>';
                } else {
                    $html .= '<div class="register_horizontal_choice"><input type="checkbox" class="register_checkbox" name="' . $this->state['id'] . '" id="' . $answer_option['id'] . '" value="' . $answer_option['id'] . '" required><label for="' . $answer_option['id'] . '">' . $answer_option['text'] . '</label></div>';
                }
            }
            $i++;

        }
        $html .= '</div>';
        return $html;
    }

    public function generate_buttons()
    {
        return "<div id='alert_button_pane'><a class='button' id='alert_back' href='#' onclick='return false;'>$this->back_string</a> <a class='button' id='alert_continue' href='#' onclick='return false;'>$this->continue_string</a></div>";
    }

    public function generate_other_text_input($value)
    {
        return "<input class='col-8 input-margin' type='text' name='other_text_input' value='" . $value . "'>";
    }

    public function generate_readable_response_array()
    {
        $response_string = "";
        $i = 0; // needed for koma stripping

        foreach ($this->response[$this->state['id']] as $item) { // for each item, lets find the short text in our $state
            foreach ($this->state['state_answers'] as $checkbox_answer) {

                if ($item == $checkbox_answer['id']) {
                    // handle the other_text_input here
                    if ($checkbox_answer['id'] == 'other' and $this->response['other_text_input'] != "") {
                        $value = $checkbox_answer['text'] . " - " . $this->response['other_text_input'];
                    } else {
                        $value = $checkbox_answer['text'];
                    }

                    $i++;

                    if (sizeof($this->response[$this->state['id']]) > $i) {
                        $response_string = $response_string . $value . ", ";
                    } else {
                        $response_string = $response_string . $value;
                    }

                }
            }
        }
        $result[$this->state['short_text']] = $response_string;
        return $result;
    }
}