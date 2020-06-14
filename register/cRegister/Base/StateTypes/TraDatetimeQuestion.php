<?php


namespace Comprise\Base\StateTypes;


class TraDatetimeQuestion extends TraState
{
    public $continue_string;
    public $locale;

    public function __construct($report_id, $state_code, $state, $locale, $continue_string, $back_string, $field_warning, $warning)
    {
        $this->field_warning = $field_warning;
        $this->back_string = $back_string;
        $this->locale = $locale;
        $this->report_id = $report_id;
        $this->state_code = $state_code;
        $this->state = $state;
        $this->continue_string = $continue_string;
        $this->warning = $warning;
    }

    public function generate_html()
    {
        $html = $this->generate_hidden_fields($this->report_id);

        $html .= "<form id='arena_question_form'>";
        $html .= "<script>jQuery.datetimepicker.setLocale('$this->locale')</script>";
        $html .= $this->generate_question_text($this->state['state_text']);
        $html .= '<input class="picker" type="text" name="' . $this->state['id'] . '" value="' . $this->response[$this->state['id']] . '" />';
        $html .= "</form>";
        $html .= $this->generate_buttons();
        $html .= $this->generate_warning();
        return $html;
    }

    // if every answer id is in response, and is not empty, return true
    public function validate($response)
    {
        // how do we wanna store the items to the db
        if (array_key_exists($this->state['id'], $response) and $response[$this->state['id']] != "") {
            $this->response = $response;
            return true;
        }
        return false;
    }

    public function generate_buttons()
    {
        return "<div id='register_button_pane'><a class='button' id='arena_back' href='#' onclick='return false;'>$this->back_string</a> <a class='button' id='arena_continue' href='#' onclick='return false;'>$this->continue_string</a></div>";
    }

    public function generate_readable_response_array()
    {
        $result[$this->state['short_text']] = $this->response[$this->state['id']];
        return $result;
    }
}