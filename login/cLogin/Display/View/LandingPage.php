<?php


// add arena topic under <hr>
// add country list in alert module


namespace Contain\Display\View;

use Contain\Display\Controller\LoadData;
use Contain\Display\Model\LoggedComponents;

class LandingPage {
    public function RenderPage($Email) {
        $html = $this->header($Email);
        $html .= $this->InstanceOfCase($Email);
        echo $html;
    }

    public function header($Email) {
        $path = plugin_dir_url( dirname( __FILE__ , 3) );
        $html = "<div class='row'>";
        $html .= "<div class='col-6'>";
        $html .= "<img class='logo' src='".$path."assets/logo.png' alt='logo'>";
        $html .= "</div>";
        $LoadArenaData = new LoadData();
        $ArenaExpertFirstName = $LoadArenaData->loadArenaData('first_name', $Email);
        $ArenaExpertLastName = $LoadArenaData->loadArenaData('last_name', $Email);
        $Name = ($ArenaExpertFirstName[0]  ." ".  $ArenaExpertLastName[0]);
        $html .= "<div class='col-6'>";
        $html .= "<h4 class='NameHeader' id='MyName' name='".$Email."'>".$Name."</h4>";
        $html .= "</div>";
        $html .= "</div>";
        $html .= "<hr>";
        $html .= " <br>";
        $html .= "<div class='row'><h3 style='margin: 0 auto'>My Arena Panel</h3></div><br><br>";
        return $html;
    }
    //TODO: redirect to arena the flp who comes to generate alert

    // return multiple functions that forms the instance on landing page (Subject of Case, Deadline, Button Status)
    public function InstanceOfCase ($Email) {

        // idea: initaitae a var with 0 and add 1 when it runs first time, when back button is pressed it remains 1 and can go without validation
        $MatchedLanguageId = $this->MatchLanguage($Email);
        $MatchedSkillsId = $this->MatchSkills($Email);
        $AlertDeclinedId = $this->Declined();
        // $FinalIds consists of all the common ids from $MatchedLanguageId, $MatchedSkillsId
        $FinalIds = array_intersect($MatchedLanguageId, $MatchedSkillsId);
        // $FinalIds consists of all the above $FinalIds which are not Rejected
        $FinalIds = array_diff($FinalIds, $AlertDeclinedId);

        $html = "<div class='row'>";
        $html .= "<table>";
        $html .= " <tr> ";
        $html .= " <td> ";
        $html .= "<b>Subject</b>";
        $html .= " </td> ";
        // call deadline
        $html .= " <td> ";
        $html .= "<b>Expected Feedback</b>";
        $html .= " </td> ";
        // call deadline
//        $html .= " <td> ";
//        $html .= "<b>Add Recommendation</b>";
//        $html .= " </td> ";
        // call button from Alert
        $html .= " <td> ";
        $html .= "<b>Case Status</b>";
        $html .= " </td> ";
        // call button from relative to expert
        $html .= " <td> ";
        $html .= "<b>Request</b>";
        $html .= " </td> ";
        $html .= " </tr> ";
        $html .= " <tr> ";
        foreach ($FinalIds as $id) {
            // call subject
            // if joined green else this

                $html .= " <td class='subject' id='".$id."'> ";
                    $html .= $this->subject($id);
                $html .= " </td> ";
            // call deadline
                $html .= " <td> ";
                    $html .= $this->deadline($id);
                $html .= " </td> ";
            // call deadline
//                $html .= " <td> ";
//                    $html .= $this->Recommendations($id);
//                $html .= " </td> ";
            // call button from Alert
                $html .= " <td> ";
                    $html .= $this->InProgressClosed($id, $Email);
                $html .= " </td> ";
            // call button from relative to expert
                $html .= " <td> ";
                    $html .= $this->JoinNotJoin($id, $Email);
                $html .= " </td> ";
            $html .= " </tr> ";
//             $this->button($FinalIds[$counter]);
//             $this->ButtonStatusArena($Email, $FinalIds[$counter]); //if true then buttons to render inprogress and close, if false buttons to render join and not join
         }
        $html .= "</table>";
        $html .= "</div>";
        return $html;
    }

    public function Recommendations ($FinalReportIds) {
        $html = " <button id='Recommend-".$FinalReportIds."' class='Recommend button'>Recommendations</button> ";
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
                $html .= " <button disabled class='ArenaClickableButtons button' id='Closed-".$FinalReportIds."'>Closed</button> ";
                return $html;
            }
            else {
                $html .= " <button class='ArenaClickableButtons button Joined' id='Joined-".$FinalReportIds."'>Joined</button> ";
                $html .= " <button class='ArenaClickableButtons button Close' id='Close-".$FinalReportIds."'>Close</button> ";
                return $html;
            }
        }
        elseif (strpos($ArenaNotAssociatedReportId[0], $FinalReportIds) !== false) {
            $html .= " <button class='ArenaClickableButtons button' disabled id='Declined-".$FinalReportIds."'>Declined</button> ";
            return $html;
        }
        elseif ($AlertCaseStatus[0] !== 'Closed') {
            $html .= " <button class='ArenaClickableButtons button' id='Join-".$FinalReportIds."'>Join</button> ";
            $html .= " <button class='ArenaClickableButtons button' id='Decline-".$FinalReportIds."'>Decline</button> ";
            return $html;
        }
        $html .= "</div>";
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
        if (strpos($ArenaCaseStatusClose[0], $FinalReportIds) !== false) {
            return "<span class='alert-status-in-progress'>In Closing</span>";
        }
        // if Accepted then in progress and close should render
        if ($AlertCaseStatus[0] === 'Accepted') {
            return "<span class='alert-status-in-progress'>In Progress</span>";
        }
        // if Closed then closed should render
        if ($AlertCaseStatus[0] === 'Closed') {
            return "<span class='alert-status-closed'>Closed</span>";
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
        $MatchedSkillsId = [];
        $LoadAlertData = new LoadData();
        $LoadArenaData = new LoadData();
        $AlertSkills = $LoadAlertData->loadAlertData('event_category');
        $AlertReportId = $LoadAlertData->loadAlertData('report_id');
        $ArenaSkills = $LoadArenaData->loadArenaData('skill', $Email);

        $ArenaSkills = explode(',', $ArenaSkills[0]);
        for ($ArenaSkillsCounter = 0; $ArenaSkillsCounter<count($ArenaSkills); $ArenaSkillsCounter++) {
            for ($AlertSkillsCounter = 0; $AlertSkillsCounter<count($AlertSkills); $AlertSkillsCounter++) {
                if (strpos($AlertSkills[$AlertSkillsCounter], $ArenaSkills[$ArenaSkillsCounter]) !== false) {
                    array_push($MatchedSkillsId, $AlertReportId[$AlertSkillsCounter]);
                }
            }
        }
        // takes the skills of arena expert and breaks it down into an array then matches those against all the alert event categories
        return $MatchedSkillsId;
        // NOTE: this array is from ALERT
    }

    public function ClickButton ($Identifier, $Email) {
        $LoadArenaData = new LoadData();
//        $CurrentExpert = $LoadArenaData->loadArenaData('report_id', $Email);

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

            $wpdb->update("wp_arena", array('associatedAlert' => $Id), array('email' => $Email));
        }
        // When clicked on Decline
        if ($Status === 'Decline') {
            // on clicking not join button update database(add another column NotAssociatedAlert) and call Declined
            $wpdb->update("wp_arena", array('notAssociatedAlert' => $Id), array('email' => $Email));
        }
        // When clicked on Close
        if ($Status === 'Close') {
            // on clicking close button
            // this is going to be complex - if its been closed by flp or an expert
            // if closed by flp update database
            // if closed by expert add another column in arena, where all experts who closed the case appear - then update database
            // after every update call Closed
            $LoadArenaData = new LoadData();
            $LoadArenaClosedAssociatedAlertData = $LoadArenaData->loadArenaData('closed_associated_alert', $Email);
            $Id = $Id1 .",".$LoadArenaClosedAssociatedAlertData[0];
            $wpdb->update("wp_arena", array('ClosedAssociatedAlert' => $Id), array('email' => $Email));
            // add if flp condition
            // if $Email === all tra_reports email
            $LoadArenaData = new LoadData();
            $LoadArenaExpertTypeData = $LoadArenaData->loadArenaData('expert_type', $Email);

            if ($LoadArenaExpertTypeData[0] === 'FLP') {

                $wpdb->update("wp_alert", array('alert_status_flp' => 'Closed'), array('reporter_email' => $Email));
            }
            else{
                $wpdb->update("wp_alert", array('alert_status_mutual' => 'Closed'), array('report_id' => $Id1));
            }

        }


        $this->RenderPage($Email);
    }











    /* ---------------------------------  */

    public function loggedMain() {

        $_SESSION['Email'] = sanitize_text_field( $_GET['email'] );
        $_SESSION['Password'] = sanitize_text_field( $_GET['pass'] );

        $clicked = sanitize_text_field( $_GET['id'] );
        $id = 0;

        global $wpdb;
        if ($clicked != 'alertBack' && $clicked != 'join'){
            $email = sanitize_text_field( $_GET['email'] );
            $pass= sanitize_text_field( $_GET['pass'] );
            $_SESSION['mail'] = $email;
            $_SESSION['pass'] = $pass;
            $r = 0;
        }
        elseif ($clicked != 'join'){
            $r = $_SESSION["iterator"];
        }
        else{
            $email= $_SESSION['mail'];
            $pass = $_SESSION['pass'];
            $r = 0;
        }
        $alert = $wpdb->get_results( "SELECT report_id, tempID, event_category, event_description, description_subject FROM {$wpdb->prefix}alert", OBJECT );
        $arena = $wpdb->get_results( "SELECT first_name, arenaTempID, last_name, email, password, associatedAlert, skill FROM {$wpdb->prefix}arena", OBJECT );
        for ($r; $r<count($arena); $r++) {
            if (($email === $arena[$r]->email && $pass === $arena[$r]->password) OR ($clicked === 'alertBack')) {
                $_SESSION['mail'] = $arena[$r] -> email;
                $_SESSION["iterator"] = $r ;
                if ($clicked != 'alertBack' && $clicked != 'join') {

                    $Header = new LoggedComponents();
                    $Header->header($arena[$r] -> first_name, $arena[$r] -> last_name);

                    echo "<hr>";
                    echo "<div id='alertPanel' class='row'>";
                }

                $arenaSkills = explode(',', $arena[$r] -> skill);
                for ($j=0; $j<count($alert); $j++) {
                    $alertSkills = explode(',', $alert[$j] -> event_category);
                    for ($k=0; $k<count($alertSkills); $k++) {
                        for ($i=0; $i<count($arenaSkills); $i++) {
                            if (($arenaSkills[$i] === $alertSkills[$k]) || ($arena[$r]->arenaTempID === $alert[$j]->tempID )) {
                                echo "<div value='$j' id='toAlert' class='alertPost col-7'>";
                                echo $alert[$j] -> description_subject;
                                echo "</div>";
                                $arenaID = explode(',', $arena[$r] -> associatedAlert);
                                for ($o=0; $o<count($arenaID); $o++){
                                    if ($arenaID[$o] === $alert[$j] -> report_id){
                                        echo "<button id='inprogress$id' value='$j' class='button inprogress col-3' >IN PROGRESS</button><br>";
                                        $o = 999;
                                    }
                                    elseif($arenaID[$o] === ""){
                                        $event = $alert[$j] -> report_id;
                                        echo "<button id='join$id' class='button join' value='$event' class='col-3'>JOIN</button>";
                                    }

                                    $id++;
                                }
                                $k = 999;

                            }
                        }
                    }
                }
                echo "</div>";
                wp_die();
            }
        }
    }

    // Logged In Page Section
    public function loggedAlert() {
        global $wpdb;
        $alertV = sanitize_text_field( $_GET['alertV'] );
        $mail = $_SESSION['mail'];
        $_SESSION['assocAlert'] = $alertV;
        $arena = $wpdb->get_results( "SELECT associatedAlert FROM {$wpdb->prefix}arena WHERE email='$mail'", OBJECT );
        $data = $alertV.",".$arena[0]->associatedAlert;


        $wpdb -> update('wp_arena', array('associatedAlert' => $data), array('email' => $mail));
        $this->loggedMain();
    }

}