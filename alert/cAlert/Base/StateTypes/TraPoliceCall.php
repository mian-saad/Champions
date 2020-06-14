<?php


namespace Cover\Base\StateTypes;



class TraPoliceCall extends TraState
{
    public $button_string;
    public $state_text;
    public $phone_number_string;

    public function __construct($report_id, $state_code, $state_text, $button_string, $phone_number_string)
    {
        $this->phone_number_string = $phone_number_string;
        $this->state_text = $state_text;
        $this->report_id = $report_id;
        $this->state_code = $state_code;
        $this->button_string = $button_string;
    }

    public function generate_html()
    {
        $html = "";
        $html .= "<p>" . $this->state_text . "</p>";
        $html .= "<p>" . $this->phone_number_string . ":<br>112<br>911</p>";
        return $html;
    }

    // if at least one category is chosen, then we pass the validation
    public function validate($response)
    {
        return true;
    }

    public function generate_buttons()
    {
        return "<div id='alert_button_pane'><a class='button' id='alert_submit' href='#' onclick='return false;'>$this->button_string</a></div>";
    }
}