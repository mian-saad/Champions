<?php


namespace Cover\Base\StateTypes;


class TraRadioQuestion extends TraState
{
    public $continue_string;

    public function __construct($alert_id, $state_code, $state, $continue_string, $back_string, $field_warning, $warning)
    {
        $this->field_warning = $field_warning;
        $this->back_string = $back_string;
        $this->alert_id = $alert_id;
        $this->state_code = $state_code;
        $this->state = $state;
        $this->continue_string = $continue_string;
        $this->warning = $warning;
    }

    public function generate_html()
    {
        $html = $this->generate_hidden_fields($this->alert_id);
        $html .= "<form id='alert_question_form'>";
        $html .= $this->generate_question_text($this->state['state_text']);
        $html .= $this->generate_radio_question($this->state);
        $html .= "</form>";
        $html .= $this->generate_buttons();
        $html .= $this->generate_warning();
        return $html;
    }

    // if at least one category is chosen, then we pass the validation
    public function validate($response)
    { // how do we wanna store the items to the db
        if (array_key_exists($this->state['id'], $response) and $response[$this->state['id']] != null) {
            $this->response = $response;
            return true;
        }
        return false;
    }

    public function generate_radio_question($state)
    {
        $html = '<div class="alert_radio_answers ">';
        $counter = 0; // need it for labeling and stuff

        foreach ($state['state_answers'] as $answer_option) {
            /*
            if($answer_option["id"]=="other"){
            $html .= '<div class="alert_horizontal_choice"><input type="radio" class="alert_quiz_radio" name="alert_other" id="' . $answer_option['id'] . $counter . '" value="' . $answer_option['id'] . '" required><label for="' . $answer_option['id'] . $counter . '">' . $answer_option['text'] . '</label><input type="text" class="other_input" value:"default" style="display:none"></div>';
            }else{
            $html .= '<div class="alert_horizontal_choice"><input type="radio" class="alert_quiz_radio" name="' . $state['id'] . '" id="' . $answer_option['id'] . $counter . '" value="' . $answer_option['id'] . '" required><label for="' . $answer_option['id'] . $counter . '">' . $answer_option['text'] . '</label></div>';
            }
             */
            if ($this->response[$state['id']] == $answer_option['id']) {
                // this radio was checked previously
                if ($answer_option['id'] == 'other') {
                    $html .= '<div class="alert_horizontal_choice"><input type="radio" class="alert_quiz_radio" name="' . $state['id'] . '" id="' . $answer_option['id'] . $counter . '" value="' . $answer_option['id'] . '" checked required><label for="' . $answer_option['id'] . $counter . '">' . $answer_option['text'] . '</label>' . $this->generate_other_text_input($this->response['other_text_input']) . '</div>';
                } else {
                    $html .= '<div class="alert_horizontal_choice"><input type="radio" class="alert_quiz_radio" name="' . $state['id'] . '" id="' . $answer_option['id'] . $counter . '" value="' . $answer_option['id'] . '" checked required><label for="' . $answer_option['id'] . $counter . '">' . $answer_option['text'] . '</label></div>';
                }
            } else {
                if ($answer_option['id'] == 'other') {
                    $html .= '<div class="alert_horizontal_choice"><input type="radio" class="alert_quiz_radio" name="' . $state['id'] . '" id="' . $answer_option['id'] . $counter . '" value="' . $answer_option['id'] . '" required><label for="' . $answer_option['id'] . $counter . '">' . $answer_option['text'] . '</label> ' . $this->generate_other_text_input("") . '</div>';
                } else {
                    $html .= '<div class="alert_horizontal_choice"><input type="radio" class="alert_quiz_radio" name="' . $state['id'] . '" id="' . $answer_option['id'] . $counter . '" value="' . $answer_option['id'] . '" required><label for="' . $answer_option['id'] . $counter . '">' . $answer_option['text'] . '</label></div>';
                }
            }

            $counter += 1;
        }

        $html .= '</div>';
        return $html;
    }

    public function generate_other_text_input($value)
    {
        return "<input type='text' style='display:block' name='other_text_input' value='" . $value . "'>";
    }

    public function generate_buttons()
    {
        return "<div id='alert_button_pane'><a class='button' id='alert_back' href='#' onclick='return false;'>$this->back_string</a> <a class='button' id='alert_continue' href='#' onclick='return false;'>$this->continue_string</a></div>";
    }

    public function generate_readable_response_array()
    {
        foreach ($this->state['state_answers'] as $state_answer) {
            if ($state_answer['id'] == $this->response[$this->state['id']]) {
                // handle the other_text_input here
                if ($state_answer['id'] == 'other' and $this->response['other_text_input'] != "") {
                    $response_string = $state_answer['text'] . " - " . $this->response['other_text_input'];
                } else {
                    $response_string = $state_answer['text'];
                }
                $result[$this->state['short_text']] = $response_string;
                return $result;
            }
        }
    }
}