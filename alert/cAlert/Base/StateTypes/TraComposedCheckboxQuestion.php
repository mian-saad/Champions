<?php


namespace Cover\Base\StateTypes;


class TraComposedCheckboxQuestion extends TraState
{
    public $continue_string;
    public $other;

    public function __construct($alert_id, $state_code, $state, $continue_string, $back_string, $field_warning, $warning, $other)
    {
        $this->field_warning = $field_warning;
        $this->back_string = $back_string;
        $this->alert_id = $alert_id;
        $this->state_code = $state_code;
        $this->state = $state;
        $this->continue_string = $continue_string;
        $this->warning = $warning;
        $this->other = $other;
    }

    public function generate_html()
    {
        $html = $this->generate_hidden_fields($this->alert_id);
        if($this->state['show_header']=="true"){
            $html .= "<h3 class='alert_question'>" . $this->state['short_text'] . "</h3>";
        }
        $html .= "<form id='alert_question_form'>";

        $counter = 0;
        foreach ($this->state['state_answers'] as $question_number) {

            $html .= $this->generate_question_text($question_number['text']);
            $html .= $this->generate_checkbox_question($question_number, $counter);
            $counter++;
        }
        $html .= "</form>";
        $html .= $this->generate_buttons();
        $html .= $this->generate_warning();
        return $html;
    }


    public function validate($response) {
        // This is how we are going to store the items to the db

        // Validate
        $counter = 0;
        foreach ($this->state['state_answers'] as $state) {
            if (!empty($response[$state['id']])) {
                $counter++;
            }
            else {
                return false;
            }
        }
        if ($counter===3) {
            // Make the data in correct format
//            $this->response = $this->clean_response($response);
            $this->response = $response;
            return true;
        }
        else {
            return false;
        }
    }

    public function generate_checkbox_question($question_number, $counter)
    {
        $html = '<div class="alert_checkbox_answers">';
        $i = 0;
        foreach ($question_number['answers'] as $answer_option) {

            if (!empty($this->response) and in_array($answer_option['text'], $this->response[$question_number['id']])) {
                // this is a checked answer
                if ($answer_option['text'] == $this->other) {
                    $html .= '<div class="alert_horizontal_choice"><input type="checkbox" class="alert_checkbox" name="' . $question_number['id'] . '" id="' . $answer_option['id'] . $i .'" value="' . $answer_option['id']. '" checked required><label >' . $answer_option['text'] . '</label> ' . $this->generate_other_text_input($this->response['other_text_input']) . '</div>';
                } else {
                    $html .= '<div class="alert_horizontal_choice"><input type="checkbox" class="alert_checkbox" name="' . $question_number['id'] . '" id="' . $answer_option['id']. '" value="' . $answer_option['id']. '" checked required><label >' . $answer_option['text'] . '</label></div>';
                }
            } else {
                if ($answer_option['text'] == $this->other) {
                    $html .= '<div class="alert_horizontal_choice"><input type="checkbox" class="alert_checkbox" name="' . $question_number['id'] . '" id="' . $answer_option['id'] . $i .'" value="' . $answer_option['id']. '" required><label >' . $answer_option['text'] . '</label> ' . $this->generate_other_text_input("") . '</div>';
                } else {
                    $html .= '<div class="alert_horizontal_choice"><input type="checkbox" class="alert_checkbox" name="' . $question_number['id'] . '" id="' . $answer_option['id'] . '" value="' . $answer_option['id']. '" required><label >' . $answer_option['text'] . '</label></div>';
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

    public function generate_readable_response_array() {
        
        $t_response = [];
        $value = null;
        foreach ($this->state['state_answers'] as $state) {
            if ( !is_array($this->response[$state['id']])) {
                $this->response[$state['id']] = str_split($this->response[$state['id']], 100);
            }
            foreach ($state['answers'] as $answer) {

                if (in_array($answer['id'], $this->response[$state['id']])) {
                    if ($value != null) {
                        $value = $value .', '. $answer['text'];
                    }
                    else {
                        $value = $answer['text'];
                    }
                }
            }
            $t_response = array_merge($t_response, array($state['short_text'] => $value));
            $value = null;
        }
        return $t_response;
    }

    public function generate_readable_response_array_eng() {

        $t_response = [];
        $value = null;
        foreach ($this->state['state_answers'] as $state) {
            foreach ($state['answers'] as $answer) {
                if (in_array($answer['id'], $this->response[$state['id']])) {
                    if ($value != null) {
                        $value = $value .', '. $answer['id'];
                    }
                    else {
                        $value = $answer['id'];
                    }
                }
            }
            $t_response = array_merge($t_response, array($state['short_text'] => $value));
            $value = null;
        }
        return $t_response;
    }

    public function clean_response($response_from_user) {

        foreach ($this->state['state_answers'] as $state) {
            if (in_array("", $response_from_user[$state['id']]) ) {
                $pos = array_search("", $response_from_user[$state['id']]);
                unset( $response_from_user[$state['id']][$pos] );
                $response_from_user[$state['id']] = array_values($response_from_user[$state['id']]);
            }
            if (in_array($this->other, $response_from_user[$state['id']]) ) {
                $pos = array_search($this->other, $response_from_user[$state['id']]);
                $response_from_user['other_'.$state['id']] = $response_from_user[$state['id']][$pos+1];
                unset( $response_from_user[$state['id']][$pos+1] );
                $response_from_user[$state['id']] = array_values($response_from_user[$state['id']]);
            }
        }
        return $response_from_user;
    }

}