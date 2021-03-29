<?php
namespace Contain\Display\View\FlpSection;

use Contain\Display\Controller\LoadData;
use Contain\Display\Model\LoggedComponents;
use function WPMailSMTP\Vendor\GuzzleHttp\Psr7\str;

class LandingPage {

    public $language;

    public function __construct($language) {
        $this->lang = $language;
    }

    public function RenderPage($Email) {
        $html = $this->header($Email);
        $html .= $this->InstanceOfCase($Email);
        echo $html;
    }

    public function header($Email) {
        $path = plugin_dir_url( dirname( __FILE__ , 4) );
        $html = "<div class='row'>";
        $html .= "<div class='col-6'>";
        $html .= "<img class='logo' src='".$path."assets/logo.png' alt='logo'>";
        $html .= "</div>";
        $LoadArenaData = new LoadData();
        $ArenaExpertFirstName = $LoadArenaData->loadArenaData('first_name', $Email);
        $ArenaExpertLastName = $LoadArenaData->loadArenaData('last_name', $Email);
        $Name = ($ArenaExpertFirstName[0]  ." ".  $ArenaExpertLastName[0]);
        $html .= "<div class='col-6'>";
        $html .= "<h4 class='NameHeader' id='MyName' name='".$Email."'><a id='edit' data-value='".$Email."'>".$Name."</a></h4>";
        $html .= "</div>";
        $html .= "</div>";
        $html .= "<hr>";
        $html .= " <br>";
        $str = $_SESSION['strings'];
        $html .= "<div class='row'><h3 style='margin: 0 auto'>".$str['arena_panel']."</h3></div><br><br>";
        return $html;
    }

    // return multiple functions that forms the instance on landing page (Subject of Case, Deadline, Button Status)
    public function InstanceOfCase ($Email) {

        $CountryMatchId = $this->CountryMatch($Email);
        $CityMatchId = $this->CityMatch($Email);
        $location_match_ids = array_intersect($CountryMatchId, $CityMatchId);

        $status_check_ids = $this->CaseStatus($location_match_ids);
        $final_ids = array_intersect($status_check_ids, $location_match_ids);

        $static_matching_ids = $this->static_matching($Email, $final_ids);

        $FinalIds = $static_matching_ids + $this->AlertFLP($Email);

        $html = "<div class='row'>".$this->lang['landingpage_hint']."</div>";
        $html .= "<div class='row'>";
        $html .= "<table>";

        $html .= " <tr> ";

        $html .= " <td> ";
        $html .= "<b>".$this->lang['subject']."</b>";
        $html .= " </td> ";

        $html .= " <td> ";
        $html .= "<b>".$this->lang['feedback']."</b>";
        $html .= " </td> ";

        $html .= " <td> ";
        $html .= "<b>".$this->lang['status']."</b>";
        $html .= " </td> ";

        $html .= " <td> ";
        $html .= "<b>".$this->lang['request']."</b>";
        $html .= " </td> ";

        $html .= " </tr> ";

        $html .= " <tr> ";

            foreach ($FinalIds as $id) {

                $html .= " <td class='subject' id='".$id."'> ";
                $html .= $this->subject($id);
                $html .= " </td> ";

                $html .= " <td> ";
                $html .= $this->deadline($id);
                $html .= " </td> ";

                $html .= " <td> ";
                $html .= $this->InProgressClosed($id, $Email);
                $html .= " </td> ";

                $html .= " <td> ";
                $html .= $this->JoinNotJoin($id, $Email);
                $html .= " </td> ";

                $html .= " </tr> ";
        }

        $html .= "</table>";
        $html .= "</div>";
        return $html;
    }

    public function Recommendations ($FinalReportIds) {
        $html = " <button id='Recommend-".$FinalReportIds."' class='Recommend button'>".$this->lang['recommendation']."</button> ";
        return $html;
    }

    public function subject ($FinalReportIds) {
        $LoadAlertData = new LoadData();
        $AlertSubject = $LoadAlertData->loadAlertData('description_subject', $FinalReportIds);
        $c = $AlertSubject;
        return $AlertSubject[0];
    }

    public function deadline ($FinalReportIds) {
        $LoadAlertData = new LoadData();
        $AlertDeadline = $LoadAlertData->loadAlertData('deadline', $FinalReportIds);
        $c = $AlertDeadline;
        return $AlertDeadline[0];
    }

    public function JoinNotJoin ($FinalReportIds, $Email) {
        // checks from associated alert in arena,
        // if the $FinalReportIds is not there then join button and not join button should render
        $LoadAlertData = new LoadData();
        $AlertCaseStatus = $LoadAlertData->loadAlertData('alert_case_status', $FinalReportIds);

        $LoadArenaData = new LoadData();
        $ArenaClosedAssociatedReportId = $LoadArenaData->loadArenaData('closed_associated_alert', $Email);
        $ArenaNotAssociatedReportId = $LoadArenaData->loadArenaData('not_associated_alert', $Email);
        // check in associatedalert, if true render joined and close
        $ArenaAssociatedReportId = $LoadArenaData->loadArenaData('associated_alert', $Email);
//        $html = "<div style='display: inline'>";
        $html = "";
        if (strpos($ArenaAssociatedReportId[0], $FinalReportIds) !== false) {
            if (strpos($ArenaClosedAssociatedReportId[0], $FinalReportIds) !== false) {
                $html .= " <button disabled class='ArenaClickableButtons button' id='Closed-".$FinalReportIds."'>".$this->lang['closed']."</button> ";
                return $html;
            }
            else {
                $html .= " <button class='ArenaClickableButtons button Joined' id='Joined-".$FinalReportIds."'>".$this->lang['joined']."</button> ";
                $html .= " <button class='ArenaClickableButtons button Close' id='Close-".$FinalReportIds."'>".$this->lang['close']."</button> ";
                return $html;
            }
        }
        elseif (strpos($ArenaNotAssociatedReportId[0], $FinalReportIds) !== false) {
            $html .= " <button class='ArenaClickableButtons button' disabled id='Declined-".$FinalReportIds."'>".$this->lang['rejected']."</button> ";
            return $html;
        }
        elseif ($AlertCaseStatus[0] !== 'Closed') {
            $html .= " <button class='ArenaClickableButtons button' id='Join-".$FinalReportIds."'>".$this->lang['join']."</button> ";
            $html .= " <button class='ArenaClickableButtons button' id='Decline-".$FinalReportIds."'>".$this->lang['reject']."</button> ";
            return $html;
        }
    }

    public function Declined () {
        $DeclinedId = [];
        $LoadAlertData = new LoadData();
        $AlertNotAssociated = $LoadAlertData->loadAlertData('alert_case_status');
        $AlertReportId = $LoadAlertData->loadAlertData('report_id');

        for ($AlertNotAssociatedCounter = 0; $AlertNotAssociatedCounter<count($AlertNotAssociated); $AlertNotAssociatedCounter++) {
            if ($AlertNotAssociated[$AlertNotAssociatedCounter] === 'Rejected' || $AlertNotAssociated[$AlertNotAssociatedCounter] === null) {
                array_push($DeclinedId, $AlertReportId[$AlertNotAssociatedCounter]);
            }
        }
        return $DeclinedId;
    }

    // This just tells the status of the Alert, Can only be in progress OR Closed
    public function InProgressClosed ($FinalReportIds, $Email) {
        // checks from alert_case_status,
        $LoadAlertData = new LoadData();
        $AlertCaseStatus = $LoadAlertData->loadAlertData('alert_case_status', $FinalReportIds);
        $LoadArenaData = new LoadData();
        $ArenaCaseStatusClose = $LoadArenaData->loadArenaData('closed_associated_alert', $Email);
        $ArenaCaseStatusAssociated = $LoadArenaData->loadArenaData('associated_alert', $Email);
        if (strpos($ArenaCaseStatusClose[0], $FinalReportIds) !== false && $AlertCaseStatus[0] !== 'Closed') {
            return "<span class='alert-status-in-progress'>".$this->lang['closing']."</span>";
        }
        // if Accepted then in progress and close should render
        if ($AlertCaseStatus[0] === 'Accepted') {
            return "<span class='alert-status-in-progress'>".$this->lang['in_progress']."</span>";
        }
        // if Closed then closed should render
        if ($AlertCaseStatus[0] === 'Closed') {
            return "<span class='alert-status-closed'>".$this->lang['closed']."</span>";
        }
        // if Rejected nothing should  render - check declined
    }

    // Matches Language of Alert and Arena
    public function MatchLanguage ($Email) {
        $MatchedLanguageId = [];
        $LoadAlertData = new LoadData();
        $LoadArenaData = new LoadData();
        $AlertLanguage = $LoadAlertData->loadAlertData('report_locale');
        $AlertReportId = $LoadAlertData->loadAlertData('report_id');
        $ArenaLanguage = $LoadArenaData->loadArenaData('report_locale', $Email);

        for ($AlertLanguageCounter = 0; $AlertLanguageCounter<count($AlertLanguage); $AlertLanguageCounter++) {
            if (strpos($AlertLanguage[$AlertLanguageCounter], $ArenaLanguage[0]) !== false) {
                array_push($MatchedLanguageId, $AlertReportId[$AlertLanguageCounter]);
            }
        }
        // returns an array with those matchedId which has matched language
        // NOTE: this array is from ALERT
        return $MatchedLanguageId;
    }

    // Matches the skills of Arena with event category of Alert
    public function MatchSkills($Email) {
//        $this->static_matching($Email);
        $MatchedSkillsId = [];
        $LoadAlertData = new LoadData();
        $LoadArenaData = new LoadData();
        $AlertSkills = $LoadAlertData->loadAlertData('event_category');
        $AlertReportId = $LoadAlertData->loadAlertData('report_id');
        $ArenaSkills = $LoadArenaData->loadArenaData('flp_experience_with_radicalisation', $Email);
        $Arena_AlertID = $LoadArenaData->loadArenaData('alert_id', $Email);

        $ArenaSkills = explode('~~~', $ArenaSkills[0]);
        for ($ArenaSkillsCounter = 0; $ArenaSkillsCounter<count($ArenaSkills); $ArenaSkillsCounter++) {
            for ($AlertSkillsCounter = 0; $AlertSkillsCounter<count($AlertSkills); $AlertSkillsCounter++) {


                similar_text($AlertSkills[$AlertSkillsCounter], $ArenaSkills[$ArenaSkillsCounter], $per);
                similar_text($ArenaSkills[$ArenaSkillsCounter], $AlertSkills[$AlertSkillsCounter], $per_op);
                if ($per>10 || $per_op>10) {
                    array_push($MatchedSkillsId, $AlertReportId[$AlertSkillsCounter]);
                }

                // if its an FLP add the alert_id to $MatchedSkillsId
                if (strpos($Arena_AlertID[0], $AlertReportId[$AlertSkillsCounter]) !== false) {
                    if (!in_array($AlertReportId[$AlertSkillsCounter], $MatchedSkillsId)) {
                        array_push($MatchedSkillsId, $AlertReportId[$AlertSkillsCounter]);
                    }
                }
            }
        }

        // takes the skills of arena expert and breaks it down into an array then matches those against all the alert event categories
        return $MatchedSkillsId;
        // NOTE: this array is from ALERT
    }

    public function ClickButton ($Identifier, $Email) {

        // on clicking join button update database and call InProgressClose
        global $wpdb;
        $Result = explode('-', $Identifier);
        $Status = $Result[0];
        $Id1 = $Result[1];

        // When clicked on Join
        if ($Status === 'Join') {
            $LoadArenaData = new LoadData();
            $LoadArenaAssociatedAlertData = $LoadArenaData->loadArenaData('associated_alert', $Email);
            $Id = $Id1 .",".$LoadArenaAssociatedAlertData[0];

            $wpdb->update("wp_arena", array('flp_associatedAlert' => $Id), array('flp_email' => $Email));
        }
        // When clicked on Decline
        if ($Status === 'Decline') {
            $LoadArenaData = new LoadData();
            $LoadArenaAssociatedAlertData = $LoadArenaData->loadArenaData('not_associated_alert', $Email);
            $Id = $Id1 .",".$LoadArenaAssociatedAlertData[0];
            // on clicking not join button update database(add another column NotAssociatedAlert) and call Declined
            $wpdb->update("wp_arena", array('flp_notAssociatedAlert' => $Id), array('flp_email' => $Email));
        }
        // When clicked on Close
        if ($Status === 'Close') {
            $LoadData = new LoadData();
            $FLPId = $LoadData->loadArenaData('flp_id', $Email);
            $LoadArenaClosedAssociatedAlertData = $LoadData->loadArenaData('closed_associated_alert', $Email);
            $Id = $Id1 .",".$LoadArenaClosedAssociatedAlertData[0];
            $FLPIdInAlert = $LoadData->loadAlertData('flp_id', $Id1);
            if (strpos($FLPIdInAlert[0], $FLPId[0]) !== false) {
                $wpdb->update("wp_arena", array('flp_ClosedAssociatedAlert' => $Id), array('flp_email' => $Email));
                $wpdb->update("wp_alert", array('alert_status_flp' => 'Closed'), array('alert_id' => $Id1));
            }
            else {
                $wpdb->update("wp_arena", array('flp_ClosedAssociatedAlert' => $Id), array('flp_email' => $Email));
                $wpdb->update("wp_alert", array('alert_status_mutual' => 'Closed'), array('alert_id' => $Id1));
            }
        }
        $this->RenderPage($Email);
    }

    public function CaseStatus($Id) {
        $MatchedId = [];
        $LoadData = new LoadData();
        foreach ($Id as $id) {
        $CaseStatus = $LoadData->loadAlertData('alert_case_status', $id);
            if ($CaseStatus[0] === "Accepted" || $CaseStatus[0] === "Closed") {
                array_push($MatchedId, $id);
            }
        }
        return $MatchedId;
    }

    public function CityMatch($email) {
        $MatchedId = [];
        global $wpdb;
        $flp = $wpdb->get_results( "SELECT flp_city, flp_visibility_level FROM {$wpdb->prefix}arena WHERE flp_email='$email'", OBJECT );
        $flp_city = $flp[0]->flp_city;
        $flp_visibility = $flp[0]->flp_visibility_level;

        $alert = $wpdb->get_results( "SELECT alert_city, alert_id FROM {$wpdb->prefix}alert", OBJECT );
        foreach ($alert as $city) {
            if ($flp_city === $city->alert_city && !empty($flp_visibility)) {
                array_push($MatchedId, $city->alert_id);
            }
            if (empty($flp_visibility)) {
                array_push($MatchedId, $city->alert_id);
            }
        }
        return $MatchedId;
    }

    public function CountryMatch($email) {
        $MatchedId = [];
        global $wpdb;
        $flp = $wpdb->get_results( "SELECT flp_country FROM {$wpdb->prefix}arena WHERE flp_email='$email'", OBJECT );
        $flp_country = $flp[0]->flp_country;
        $alert = $wpdb->get_results( "SELECT alert_country, alert_id FROM {$wpdb->prefix}alert", OBJECT );
        foreach ($alert as $country) {
            if ($flp_country === $country->alert_country) {
                array_push($MatchedId, $country->alert_id);
            }
        }
        return $MatchedId;
    }
    // Generates those alert ids which have been reported by the logged in flp
    public function AlertFLP($email) {
        $MatchedId = [];
        global $wpdb;
        $flp = $wpdb->get_results( "SELECT flp_id FROM {$wpdb->prefix}arena WHERE flp_email='$email'", OBJECT );
        $alerts = $wpdb->get_results( "SELECT alert_id, alert_case_status, flp_id FROM {$wpdb->prefix}alert", OBJECT );
        foreach ($alerts as $alert) {
            if ($alert->flp_id == $flp[0]->flp_id && !empty($alert->alert_case_status) ) {
                array_push($MatchedId, $alert->alert_id);
            }
        }
        return $MatchedId;
    }

    public function static_matching($Email, $Ids) {
        global $wpdb;
        $final_list = [];

        $flp = $wpdb->get_results( "SELECT flp_id, flp_experience_with_radicalisation, flp_working_with, flp_area_of_expertise FROM {$wpdb->prefix}arena WHERE flp_email='$Email'", OBJECT );
        foreach ($flp as $data) {
            $flp = $this->flp_mapping($data);
        }

        foreach ($Ids as $id) {
            $alert = $wpdb->get_results( "SELECT alert_id, alert_category, alert_location, alert_target FROM {$wpdb->prefix}alert WHERE alert_id='$id'", OBJECT );
            $final_list = $final_list + $this->alert_mapping($alert[0], $flp);
        }


        // remove elements with 0 value -- no match in this case
        foreach ($final_list as $item => $value) {
            if ($value == 0) {
                unset($final_list[$item]);
            }
        }

        // sort array in descending order w.r.t values
        arsort($final_list);

        return array_keys($final_list);
    }

    public function alert_mapping($data, $flp) {
        $ident_list = [];
        $plugin_path = plugin_dir_path( dirname(__FILE__, 4));
        $json_data = json_decode(file_get_contents($plugin_path . "assets/base/alert_map.json"), true);

        $ident_category = explode("~~~", $data->alert_category);
        $ident_location = explode("~~~", $data->alert_location);
        $ident_target = explode("~~~", $data->alert_target);

        $metadata = array_merge($ident_category, $ident_location, $ident_target);

        foreach ($metadata as $iteration) {
            $iteration = trim($iteration);
            foreach ($json_data as $item => $value) {
                if ($item === $iteration) {
                    array_push($ident_list, $value);
                }
            }
        }

        // Remove duplicates from flp list
        $imploding_in_string = implode(',', $flp);
        $explodng_in_array = explode(',', $imploding_in_string);
        $flps = array_unique($explodng_in_array);
        $match_count = $this->alert_flp($ident_list, $flps);
        $ascending_match_list[$data->alert_id] = $match_count;

        return $ascending_match_list;
    }

    public function flp_mapping($data) {
        $ident_list = [];
        $plugin_path = plugin_dir_path( dirname(__FILE__, 4));
        $json_data = json_decode(file_get_contents($plugin_path . "assets/base/flp_map.json"), true);

        $ident_radicalisation = explode("~~~", $data->flp_experience_with_radicalisation);
        $ident_working = explode("~~~", $data->flp_working_with);
        $ident_expertise = explode("~~~", $data->flp_area_of_expertise);

        $metadata = array_merge($ident_radicalisation, $ident_working, $ident_expertise);

        foreach ($metadata as $iteration) {
            $iteration = trim($iteration);
            foreach ($json_data as $item => $value) {
                if ($item === $iteration) {
                    array_push($ident_list, $value);
                }
            }
        }
        return $ident_list;
    }

    public function alert_flp($alert_list, $flp_list) {
        $a = $alert_list;
        $b = $flp_list;
        $count = 0;

        foreach ($alert_list as $alert) {
            $alert = explode(',', $alert);
            foreach ($flp_list as $flp) {
                if (in_array($flp, $alert)) {
                    $count++;
                }
            }
        }
        return $count;
    }
}
?>