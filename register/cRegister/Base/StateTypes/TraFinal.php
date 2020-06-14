<?php


namespace Comprise\Base\StateTypes;


class TraFinal extends TraState
{
    public $submit_string;
    public $pdf_string;
    public $answers;
    public $contact_proposals;
    public $proposals_string;
    public $crime_location_string;
    public $language_pref_string;
    public $residence_string;
    public $proposal_html;
    public $no_results_string;
    public $pdfurl;

    public function __construct($report_id, $state_code, array $answers, $submit_string, $back_string, $crime_location_string, $language_pref_string, $residence_string, $no_results_string)
    {

        $this->no_results_string = $no_results_string;
        $this->crime_location_string=$crime_location_string;
        $this->language_pref_string=$language_pref_string;
        $this->residence_string=$residence_string;
        $this->back_string = $back_string;
        $this->report_id = $report_id;
        $this->state_code = $state_code;
        $this->submit_string = $submit_string;
        $this->answers = $answers;
    }


    public function generate_html()
    {
        $html = $this->generate_hidden_fields($this->report_id);
        $html .= "<h3>Please Review your Data</h3>";

        foreach ($this->answers as $short_text => $value) {
            $html .= "<p>" . $short_text . " : " . $value . "</p>";
        }

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
        return "<div id='register_button_pane'><a class='button' id='arena_back' href='#' onclick='return false;'>$this->back_string</a> <a class='button' id='arena_submit' onClick=\"window.location.reload();\" href='#' >$this->submit_string</a></div>";
    }
}
