<?php


namespace Inc\Base\StateTypes;


class TraError extends TraState
{
    public $button_string;
    public $state_text;

    public function __construct($report_id, $state_code, $state_text, $button_string)
    {
        $this->state_text = $state_text;
        $this->report_id = $report_id;
        $this->state_code = $state_code;
        $this->button_string = $button_string;
    }

    public function generate_html()
    {
        $html = "";
        $html .= "<p class='tra_warning'> ERROR STATE: " . $this->state_text . "</p>";
        return $html;
    }

    // if at least one category is chosen, then we pass the validation
    public function validate($response)
    {
        return true;
    }

    public function generate_buttons()
    {
        return "<div id='tra_button_pane'><a class='button' href='#'>$this->button_string</a></div>";
    }
}