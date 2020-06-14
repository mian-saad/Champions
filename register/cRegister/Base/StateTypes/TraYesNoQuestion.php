<?php


namespace Comprise\Base\StateTypes;


class TraYesNoQuestion extends TraState
{
    public $yes_string;
    public $no_string;

    public function __construct($report_id, $state_code, $state, $yes_string, $no_string, $back_string, $field_warning, $warning)
    {
        $this->field_warning = $field_warning;
        $this->back_string = $back_string;
        $this->report_id = $report_id;
        $this->state_code = $state_code;
        $this->state = $state;
        $this->yes_string = $yes_string;
        $this->no_string = $no_string;
        $this->warning = $warning;
    }

    public function generate_html()
    {
        $html = $this->generate_hidden_fields($this->report_id);
        $html .= "<form id='arena_question_form'>";
        $html .= $this->generate_question_text($this->state['state_text']);
        $html .= "</form>";
        $html .= $this->generate_buttons();
        $html .= $this->generate_warning();
        return $html;
    }

    // if answer is not true or false, return false
    public function validate($response)
    {
        if ($response == "true" or $response == "false") {
            $this->response = $response;
            return true;
        }
        return false;
    }

    public function generate_buttons()
    {
        return "<div id='register_button_pane'><a class='button' id='arena_back' href='#' onclick='return false;'>$this->back_string</a> <a class='button' id='register_yes' href='#' onclick='return false;'>$this->yes_string</a> <a class='button' id='register_no' href='#' onclick='return false;'>$this->no_string</a></div>";
    }

    public function generate_readable_response_array()
    {
        if ($this->response == "true") {
            $result[$this->state['short_text']] = $this->yes_string;
        } else if ($this->response == "false") {
            $result[$this->state['short_text']] = $this->no_string;
        }

        return $result;
    }
}