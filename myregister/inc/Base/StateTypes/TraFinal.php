<?php


namespace Inc\Base\StateTypes;


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

    public function __construct($report_id, $state_code, array $answers, $submit_string, $back_string, $crime_location_string, $language_pref_string, $residence_string, $no_results_string, $pdfurl)
    {
        $this->pdfurl = $pdfurl;
        $this->no_results_string = $no_results_string;
        $this->crime_location_string=$crime_location_string;
        $this->language_pref_string=$language_pref_string;
        $this->residence_string=$residence_string;
        $this->back_string = $back_string;
        $this->report_id = $report_id;
        $this->state_code = $state_code;
        $this->submit_string = $submit_string;
        $this->answers = $answers;
//        $this->contact_proposals = $contact_proposals;
        $this->proposal_html="";
    }


    public function generate_html()
    {
        $html = $this->generate_hidden_fields($this->report_id);

        foreach ($this->answers as $short_text => $value) {
            $html .= "<p>" . $short_text . " : " . $value . "</p>";
        }

        $this->proposal_html .= "<hr><p>".$this->crime_location_string."</p>";
        if(count($this->contact_proposals["crime_location"])==0){
            $this->proposal_html .= "<p>".$this->no_results_string."</p>";
        }
        foreach ($this->contact_proposals["crime_location"] as $item) {
            if($item["agency_name"]!=""){
                $this->proposal_html .= "<p>" . $item["agency_name"] . "<br>";
            } else {
                continue;
            }
            if($item["email"]!=""){
                $this->proposal_html .= $item["email"] . "<br>";
            }
            if($item["phone"]!=""){
                $this->proposal_html .= $item["phone"] . "<br>";
            }
            if($item["url"]!=""){
                $this->proposal_html .= "URL : <a href='". $item["url"] . "'>". $item["url"] . "</a></p>";
            }
            else $this->proposal_html .= "</p>";
        }

        $this->proposal_html .= "<hr><p>".$this->language_pref_string."</p>";
        if(count($this->contact_proposals["language_pref"])==0){
            $this->proposal_html .= "<p>".$this->no_results_string."</p>";
        }
        foreach ($this->contact_proposals["language_pref"] as $item) {
            if($item["agency_name"]!=""){
                $this->proposal_html .= "<p>" . $item["agency_name"] . "<br>";
            } else {
                continue;
            }
            if($item["email"]!=""){
                $this->proposal_html .= $item["email"] . "<br>";
            }
            if($item["phone"]!=""){
                $this->proposal_html .= $item["phone"] . "<br>";
            }
            if($item["url"]!=""){
                $this->proposal_html .= "URL : <a href='". $item["url"] . "'>". $item["url"] . "</a></p>";
            }
            else $this->proposal_html .= "</p>";
        }

        $this->proposal_html .= "<hr><p>".$this->residence_string."</p>";
        if(count($this->contact_proposals["residence_state"])==0){
            $this->proposal_html .= "<p>".$this->no_results_string."</p>";
        }
        foreach ($this->contact_proposals["residence_state"] as $item) {
            if($item["agency_name"]!=""){
                $this->proposal_html .= "<p>" . $item["agency_name"] . "<br>";
            } else {
                continue;
            }
            if($item["email"]!=""){
                $this->proposal_html .= $item["email"] . "<br>";
            }
            if($item["phone"]!=""){
                $this->proposal_html .= $item["phone"] . "<br>";
            }
            if($item["url"]!=""){
                $this->proposal_html .= "URL : <a href='". $item["url"] . "'>". $item["url"] . "</a></p>";
            }
            else $this->proposal_html .= "</p>";
        }

        $html .= $this->proposal_html;
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
        return "<div id='tra_button_pane'><a class='button' id='tra_back' href='#' onclick='return false;'>$this->back_string</a> <a class='button' id='tra_submit' href='$this->pdfurl' >$this->submit_string</a></div>";
    }
}
