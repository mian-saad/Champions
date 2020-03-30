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
    public $flp;

    public function __construct($report_id, $flp, $state_code, array $answers, $submit_string, $back_string, $crime_location_string, $language_pref_string, $residence_string, $no_results_string, $pdfurl)
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
        $this->flp = $flp;
//        $this->contact_proposals = $contact_proposals;
        $this->proposal_html="";
    }


    public function generate_html() {

        echo "<h3 class='cus_margin'>Alert Summary</h3>";
        $html = $this->generate_hidden_fields($this->report_id);

        $html .= "<p>FLP: " . $this->flp . "</p>";
        foreach ($this->answers as $short_text => $value) {
            $html .= "<p>" . $short_text . " : " . $value . "</p>";
        }

        // in arena concluded case would have some tags. based on those tags that data would be rendered here
        //$html .= "<hr><p>".$this->crime_location_string."</p>";


//        if(count($this->contact_proposals["crime_location"])==0){
//            $this->proposal_html .= "<p>".$this->no_results_string."</p>";
//        }
//        foreach ($this->contact_proposals["crime_location"] as $item) {
//            if($item["agency_name"]!=""){
//                $this->proposal_html .= "<p>" . $item["agency_name"] . "<br>";
//            } else {
//                continue;
//            }
//            if($item["email"]!=""){
//                $this->proposal_html .= $item["email"] . "<br>";
//            }
//            if($item["phone"]!=""){
//                $this->proposal_html .= $item["phone"] . "<br>";
//            }
//            if($item["url"]!=""){
//                $this->proposal_html .= "URL : <a href='". $item["url"] . "'>". $item["url"] . "</a></p>";
//            }
//            else $this->proposal_html .= "</p>";
//        }

//        $this->proposal_html .= "<hr><p>".$this->language_pref_string."</p>";
//        if(count($this->contact_proposals["language_pref"])==0){
//            $this->proposal_html .= "<p>".$this->no_results_string."</p>";
//        }
//        foreach ($this->contact_proposals["language_pref"] as $item) {
//            if($item["agency_name"]!=""){
//                $this->proposal_html .= "<p>" . $item["agency_name"] . "<br>";
//            } else {
//                continue;
//            }
//            if($item["email"]!=""){
//                $this->proposal_html .= $item["email"] . "<br>";
//            }
//            if($item["phone"]!=""){
//                $this->proposal_html .= $item["phone"] . "<br>";
//            }
//            if($item["url"]!=""){
//                $this->proposal_html .= "URL : <a href='". $item["url"] . "'>". $item["url"] . "</a></p>";
//            }
//            else $this->proposal_html .= "</p>";
//        }
//
//        $this->proposal_html .= "<hr><p>".$this->residence_string."</p>";
//        if(count($this->contact_proposals["residence_state"])==0){
//            $this->proposal_html .= "<p>".$this->no_results_string."</p>";
//        }
//        foreach ($this->contact_proposals["residence_state"] as $item) {
//            if($item["agency_name"]!=""){
//                $this->proposal_html .= "<p>" . $item["agency_name"] . "<br>";
//            } else {
//                continue;
//            }
//            if($item["email"]!=""){
//                $this->proposal_html .= $item["email"] . "<br>";
//            }
//            if($item["phone"]!=""){
//                $this->proposal_html .= $item["phone"] . "<br>";
//            }
//            if($item["url"]!=""){
//                $this->proposal_html .= "URL : <a href='". $item["url"] . "'>". $item["url"] . "</a></p>";
//            }
//            else $this->proposal_html .= "</p>";
//        }
//        $html .= $this->proposal_html;
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
//        echo '<script>';
//        echo 'function myFunction() {';
//        echo 'alert(\'Your alert has been sent via email!\')}';
//        //echo 'window.location.href = "http://localhost:8888/alert";';
//        echo '</script>';
        return "<div id='tra_button_pane'><a class='button' href='#' id='done' >DONE</a> <a class='button' id='tra_submit' href='$this->pdfurl' onclick='myFunction()' download>$this->submit_string</a></div>";
    }
}
