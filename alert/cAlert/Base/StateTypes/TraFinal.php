<?php

namespace Cover\Base\StateTypes;

use Cover\Base\ReportController;

class TraFinal extends TraState {
    public $submit_string;
    public $answers;
    public $crime_location_string;
    public $language_pref_string;
    public $residence_string;
    public $proposal_html;
    public $no_results_string;
    public $isValidated;
    public $pdfurl;
    public $summary;
    public $string_file;

    public function __construct($string_file, $summary, $alert_id, $state_code, array $answers, $submit_string, $back_string, $crime_location_string, $language_pref_string, $residence_string, $no_results_string, $pdfurl, $isValidated)
    {
        $this->pdfurl = $pdfurl;
        $this->no_results_string = $no_results_string;
        $this->crime_location_string=$crime_location_string;
        $this->language_pref_string=$language_pref_string;
        $this->residence_string=$residence_string;
        $this->back_string = $back_string;
        $this->alert_id = $alert_id;
        $this->state_code = $state_code;
        $this->submit_string = $submit_string;
        $this->answers = $answers;
        $this->summary = $summary;
        $this->proposal_html="";
        $this->string_file = $string_file;
    }


    public function generate_html() {
        $html = $this->generate_hidden_fields($this->alert_id);
        $html .= "<h3 class='alert_question'> $this->summary </h3>";
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

    public function generate_buttons() {
        $report_controller = new ReportController();
        if ($_SESSION['validate'] == false) { // Not validated
            return "<div id='alert_button_pane'><a class='button' id='alert_back' href='#' onclick='return false;'>$this->back_string</a> <a class='button' id='alert_continue' href='$this->pdfurl' download>$this->submit_string</a></div>";
        }
        else {
            $_SESSION['validate'] = false;
            return "<div id='alert_button_pane'> <a class='button' id='alert_submit' href='$this->pdfurl' download>$this->submit_string</a></div>";
        }

    }

    public function Recommendation() {
        // check event category of this alert
        // fetch skills of all alert cases
        // compare skills of this alert case with all alert cases
        // if matches then retrieve id of that alert case
        // chec in recommendation table if its there
        foreach ($this->answers as $short_text => $value) {
            if ($short_text === "Category") {
                return $value;
            }
        }
    }

    public function GetCategories($CurrentCategory) {
        global $wpdb;
        $RecommendedAlerts = [];
        // Fetch all categories
        $Categories = $wpdb->get_results( "SELECT alert_id, alert_category FROM wp_alert", OBJECT );
        for ($j=0; $j<count($Categories); $j++) {
            similar_text($CurrentCategory, $Categories[$j]->alert_category, $percentage);
            if ($percentage>40) {
                $RecommendedAlerts[$j] = $Categories[$j]->alert_id;
            }
        }
        return $RecommendedAlerts;
    }

    public function GetRecommendations($RecommendedAlertsIDs) {
        $html = "<hr> <h3>".$this->string_file['recommendation']."</h3>";
        global $wpdb;
//        $reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
        $reg_exUrl = "/(https?:\/\/)?([\w\-])+\.{1}([a-zA-Z]{2,63})([\/\w-]*)*\/?\??([^#\n\r]*)?#?([^\n\r]*)/";
//        $reg_exUrl = "/(http:\/\/www\.|https:\/\/www\.|http:\/\/|https:\/\/)?[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?/";
        if (!empty($RecommendedAlertsIDs)){
            for ($i=0; $i<count($RecommendedAlertsIDs); $i++) {
                $Recommendations = $wpdb->get_results( "SELECT recommendation_data, recommendation_name FROM wp_recommendationData WHERE alert_ID='{$RecommendedAlertsIDs[$i]}'", OBJECT );

                if (!empty($Recommendations[0])) {
                    foreach ($Recommendations as $Recommendation) {
                        if(preg_match($reg_exUrl, $Recommendation->recommendation_data, $url)) {
                            if (substr($Recommendation->recommendation_data,0,3) == 'www') {
                                $url[0] = 'https://'.$url[0];
                            }
                            $html .= "<b value='".$Recommendation -> recommendation_data."'><li>".preg_replace($reg_exUrl, "<a target='_blank' href='{$url[0]}'>{$url[0]}</a>", $Recommendation->recommendation_data)."</li></b>";
                        }
                        else {
                            $html .= "<b value='".$Recommendation -> recommendation_data."'><li>" . $Recommendation -> recommendation_data . "</li></b>";
                        }
                        $html .= "</br>";
                    }
                    $html .= "<div id='alert_button_pane'> <a class='button' id='thankyou'>".$this->string_file['done']."</a>  </div>";
                    $html .= "<p><h6>".$this->string_file['satisfied_recommendation']."</h6></p>";
                }
                else {
                    if ($html === "<hr> <h3>Recommendations</h3>") {
                        $html = "<hr> <h3>".$this->string_file['recommendation']."</h3>";
                        $html .= "<p>".$this->string_file['no_recommendation']."</p>";
                    }
                }
            }
            return $html;
        }
        $html .= "<p>".$this->string_file['no_recommendation_case']."</p>";
        return $html;
    }
}
