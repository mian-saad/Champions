<?php

namespace Cover\Base\StateTypes;
use Cover\Base\ReportController;
use http\Url;

class TraFinal extends TraState {
    public $submit_string;
    public $answers;
    public $proposal_html;
    public $no_results_string;
    public $isValidated;
    public $pdfurl;
    public $summary;
    public $string_file;
    public $eng_answers;

    public function __construct($eng_answers, $string_file, $summary, $alert_id, $state_code, array $answers, $submit_string, $back_string, $no_results_string, $pdfurl, $isValidated)
    {
        $this->pdfurl = $pdfurl;
        $this->no_results_string = $no_results_string;
        $this->back_string = $back_string;
        $this->alert_id = $alert_id;
        $this->state_code = $state_code;
        $this->submit_string = $submit_string;
        $this->answers = $answers;
        $this->summary = $summary;
        $this->proposal_html="";
        $this->string_file = $string_file;
        $this->eng_answers = $eng_answers;
    }

    public function generate_html() {

        $recommendation = $this->Recommendation();
        $matching_ids = $this->old_alerts($recommendation);
        $categories = $this->GetCategories($recommendation);

        $html = $this->generate_hidden_fields($this->alert_id);
        $html .= "<h3 class='alert_question'> $this->summary </h3>";
        foreach ($this->answers as $short_text => $value) {
            $html .= "<p class='summary_tags'><b>" . $short_text . " : </b>" . $value . "</p>";
        }
        $html .= $this->generate_buttons();
        $html .= "<hr><p>".$this->string_file['final_info']."</p>";
        $html .= $this->GetRecommendations($matching_ids);
        /* ML Algorithm called here
        $html .= $this->AIRecommendations();
        if ($_SESSION['state_code'] == '1.4') {
            $this->AIExpert();
        }*/
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
        $list[$this->alert_id] = [];
        foreach ($this->eng_answers as $short_text => $value) {
            if ($short_text === 'alert_category' ||
                $short_text === 'alert_location' ||
                $short_text === 'alert_target') {
                $vals = explode('~~~', $value);
                foreach ($vals as $val) {
                    if ($val !== "Other" || $val !== "I don't know") {
                        array_push($list[$this->alert_id], $val);
                    }
                }
            }
        }
        return $list;
    }

    public function old_alerts($new) {
        global $wpdb;
        $list = [];
        $matching_alert_ids = [];
        $alerts = $wpdb->get_results( "SELECT alert_id, alert_category, alert_location, alert_target FROM wp_alert", OBJECT );
        foreach ($alerts as $alert) {
            $metadata[$alert->alert_id] = [];
            $alert_category = explode('~~~', $alert->alert_category);
            $alert_location = explode('~~~', $alert->alert_location);
            $alert_target = explode('~~~', $alert->alert_target);

            foreach ($alert_category as $value) {
                array_push($metadata[$alert->alert_id], $value);
            }
            foreach ($alert_location as $value) {
                array_push($metadata[$alert->alert_id], $value);
            }
            foreach ($alert_target as $value) {
                array_push($metadata[$alert->alert_id], $value);
            }
            array_push($list, $this->match_old_new($metadata, $new));
            $metadata = [];
        }
        // sort array in descending order
        foreach ($list as $item => $value) {
            if ($value !== 0) {
                $matching_alert_ids = $matching_alert_ids + $value;
            }
        }
        arsort($matching_alert_ids);

        foreach ($matching_alert_ids as $id => $value) {
            if ($value == 0) {
                unset($matching_alert_ids[$id]);
            }
        }
        return array_keys($matching_alert_ids);
    }

    public function match_old_new($old, $new) {

        $result = array_intersect(reset($old), reset($new));
        $entries_count[array_key_first($old)] = count($result);

        return $entries_count;
    }

    public function GetCategories($CurrentCategory) {
        global $wpdb;
        $RecommendedAlerts = [];
        $Categories = $wpdb->get_results( "SELECT alert_id, alert_category FROM wp_alert", OBJECT );
        for ($j=0; $j<count($Categories); $j++) {
            similar_text($CurrentCategory, $Categories[$j]->alert_category, $percentage);
            if ($percentage>40) {
                $RecommendedAlerts[$j] = $Categories[$j]->alert_id;
            }
        }
        return $RecommendedAlerts;
    }

    public function url_manager_recommendation($Recommendation, $url, $reg_exUrl) {
        if (substr($Recommendation->recommendation_data,0,3) == 'www') {
            $url[0] = 'https://'.$url[0];
        }
        return "<b value='".$Recommendation -> recommendation_data."'><li>".preg_replace($reg_exUrl, "<a target='_blank' href='{$url[0]}'>{$url[0]}</a>", $Recommendation->recommendation_data)."</li></b>";
    }

    public function recommendation_decider($RecommendedAlertsIDs) {
        global $wpdb;
        $html = "";
        $reg_exUrl = "/(https?:\/\/)?([\w\-])+\.{1}([a-zA-Z]{2,63})([\/\w-]*)*\/?\??([^#\n\r]*)?#?([^\n\r]*)/";
        for ($i=0; $i<count($RecommendedAlertsIDs); $i++) {
            $Recommendations = $wpdb->get_results( "SELECT recommendation_data, recommendation_name FROM wp_recommendationData WHERE alert_ID='{$RecommendedAlertsIDs[$i]}'", OBJECT );
            foreach ($Recommendations as $Recommendation) {
                if(preg_match($reg_exUrl, $Recommendation->recommendation_data, $url)) {
                    $html .= $this->url_manager_recommendation($Recommendation, $url, $reg_exUrl);
                }
                else {
                    $html .= "<b value='".$Recommendation -> recommendation_data."'><li>" . $Recommendation -> recommendation_data . "</li></b>";
                }
                $html .= "</br>";
            }
        }
        $html .= "<div id='alert_button_pane'> <a class='button' id='done'>".$this->string_file['done']."</a>  </div>";
        $html .= "<p><h6>".$this->string_file['satisfied_recommendation']."</h6></p>";

        return $html;
    }

    public function GetRecommendations($RecommendedAlertsIDs) {
        $html = "<hr> <h3>".$this->string_file['recommendation']."</h3>";

        if (empty($RecommendedAlertsIDs)){
            $html .= "<p>".$this->string_file['no_recommendation_case']."</p>";
            return $html;
        }
        else {
            $html .= $this->recommendation_decider($RecommendedAlertsIDs);
            return $html;
        }
    }

    public function AIRecommendations() {

        $unseen_text = 'damaged';

        $html = "<hr> <h3>".$this->string_file['recommendation']."</h3>";

        $plugin_path = plugin_dir_path( dirname(__FILE__, 2));
        exec("/usr/local/bin/python3 ".$plugin_path."Base/Recommendation.py '$unseen_text'", $test_result);
//        $test_result = implode(" ",$test_result);
        foreach ($test_result as $item) {
//            $html .= "<p>".$item."</p>";
            $html .= "<p>No Recommendations for this case</p>";
        }


        return $html;
//        $command = escapeshellcmd('/usr/local/bin/python3 '.$plugin_path.'/Base/test.py');
//        $output = shell_exec($command);
//        echo $output;
    }

    public function AIExpert() {

        $unseen_text = 'this is unseen';

        $plugin_path = plugin_dir_path( dirname(__FILE__, 2));
        exec("/usr/local/bin/python3 ".$plugin_path."/Base/Recommendation.py '$unseen_text'", $test_result);
//        $test_result = implode(" ",$test_result);
        foreach ($test_result as $item) {
            $xv= $item;
        }


//        $command = escapeshellcmd('/usr/local/bin/python3 '.$plugin_path.'/Base/test.py');
//        $output = shell_exec($command);
//        echo $output;
    }

}
