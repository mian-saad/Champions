<?php


namespace Cover\Base\StateTypes;


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
        $html .= "<h3 class='alert_question'>ALERT Summary</h3>";

        foreach ($this->answers as $short_text => $value) {
            $html .= "<p class='summary_tags'>" . $short_text . " : " . $value . "</p>";
        }

        $html .= $this->generate_buttons();
        $html .= $this->GetRecommendations($this->GetCategories($this->Recommendation()));

        return $html;
    }

    // if at least one category is chosen, then we pass the validation
    public function validate($response)
    { // how do we wanna store the items to the db
        return true;
    }

    public function generate_buttons()
    {
        return "<div id='alert_button_pane'><a class='button' href='#' onClick=\"window.location.reload();\" onclick='return false;'>DONE</a> <a class='button' id='alert_submit' href='$this->pdfurl' download>$this->submit_string</a></div>";
    }

    public function Recommendation() {
        // check event category of this alert
        // fetch skills of all alert cases
        // compare skills of this alert case with all alert cases
        // if matches then retrieve id of that alert case
        // chec in recommendation table if its there
        foreach ($this->answers as $short_text => $value) {
            if ($short_text === "Event Category") {
                return $value;
            }
        }
    }

    public function GetCategories($CurrentCategory) {
        global $wpdb;
        $RecommendedAlerts = [];
        // Fetch all categories
        $Categories = $wpdb->get_results( "SELECT report_id, event_category FROM wp_alert", OBJECT );
        for ($j=0; $j<count($Categories); $j++) {
            similar_text($CurrentCategory, $Categories[$j]->event_category, $percentage);
            if ($percentage>40) {
                $RecommendedAlerts[$j] = $Categories[$j]->report_id;
            }
        }
        return $RecommendedAlerts;
    }

    public function GetRecommendations($RecommendedAlertsIDs) {
        $html = "<hr> <h3>Recommendations</h3>";
        global $wpdb;
        if (!empty($RecommendedAlertsIDs)){
            for ($i=0; $i<count($RecommendedAlertsIDs); $i++) {
                $Recommendations = $wpdb->get_results( "SELECT recommendation_data, recommendation_name FROM wp_recommendationData WHERE alert_ID='{$RecommendedAlertsIDs[$i]}'", OBJECT );
                if (!empty($Recommendations[0])) {
//                    $html .= "<b>".$Recommendations[0] -> recommendation_name." recommended </b>";
                    $html .= "<b value='".$Recommendations[0] -> recommendation_data."'><li>" . $Recommendations[0] -> recommendation_data . "</li></b>";
                    $html .= "</br>";
                }
                else {
                    $html .= "<p>No Recommendations for this Case</p>";
                }
            }
            return $html;
        }
        $html .= "<p>No Recommendations for this Case</p>";
        return $html;
    }
}
