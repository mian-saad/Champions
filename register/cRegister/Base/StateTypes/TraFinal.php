<?php


namespace Comprise\Base\StateTypes;


class TraFinal extends TraState
{
    public $submit_string;
    public $answers;
    public $no_results_string;
    public $string_file;

    public function __construct($string_file, $report_id, $state_code, array $answers, $submit_string, $back_string, $no_results_string)
    {

        $this->no_results_string = $no_results_string;
        $this->back_string = $back_string;
        $this->report_id = $report_id;
        $this->state_code = $state_code;
        $this->submit_string = $submit_string;
        $this->answers = $answers;
        $this->string_file = $string_file;
    }


    public function generate_html()
    {
        $html = "<div class='col-12'>";
        $html .= $this->generate_hidden_fields($this->report_id);
        $html .= "<h3>".$this->string_file['review_data']."</h3>";

        foreach ($this->answers as $short_text => $value) {
            if ($short_text == 'Password') {
                $value = "********";
            }
            $html .= "<p>" . $short_text . " : " . $value . "</p>";
        }
        $html .= "</div>";
        $html .= $this->generate_buttons();
        return $html;
    }

    // if at least one category is chosen, then we pass the validation
    public function validate($response)
    { // how do we wanna store the items to the db
        return true;
    }

    public function generate_buttons()
    {
        return "<div id='register_button_pane'><a class='button' id='arena_back' href='#' onclick='return false;'>$this->back_string</a> <a class='button' id='arena_submit' href='#' >$this->submit_string</a></div>";
    }
}
