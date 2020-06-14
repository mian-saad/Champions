<?php

namespace Cover\Base\StateTypes;

class TraGDPR extends TraState
{
    public $continue_string;

    public function __construct($report_id, $state_code, $state, $continue_string, $warning)
    {
        $this->report_id = $report_id;
        $this->state_code = $state_code;
        $this->state = $state;
        $this->continue_string = $continue_string;
        $this->warning = $warning;
    }

    public function generate_html()
    {
        $html = $this->generate_hidden_fields($this->report_id);
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
            return true;
        }
        return false;
    }

    public function generate_checkbox_question($answer_array, $name_string)
    {
        $html = '<div class="alert_checkbox_answers">';

        foreach ($answer_array as $answer_option) {
            $html .= '<div class="alert_horizontal_choice"><input type="checkbox" class="alert_checkbox" name="' . $this->state['id'] . '" id="' . $answer_option['id'] . '" value="' . $answer_option['id'] . '" required><label for="' . $answer_option['id'] . '">' . $answer_option['text'] . '</label></div>';
        }
        $html .= '</div>';
        return $html;
    }

    public function generate_buttons()
    {
        return "<div id='alert_button_pane'><a class='button' id='alert_continue' href='#' onclick='return false;'>$this->continue_string</a></div>";
    }
}