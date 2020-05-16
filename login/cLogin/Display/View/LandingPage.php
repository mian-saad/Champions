<?php

namespace Contain\Display\View;

use Contain\Display\Controller\LoadData;
use Contain\Display\Model\LoggedComponents;

class LandingPage {

    //TODO: redirect to arena the flp who comes to generate alert

    // return multiple functions that forms the instance on landing page (Subject of Case, Deadline, Button Status)
    public function InstanceOfCase ($Email) {
        // idea: initaitae a var with 0 and add 1 when it runs first time, when back button is pressed it remains 1 and can go without validation
        $MatchedLanguageId = $this->MatchLanguage($Email);
        $MatchedSkillsId = $this->MatchSkills($Email);
        $FinalIds = array_intersect($MatchedLanguageId, $MatchedSkillsId);
        // finds common id from LingoMatch and MatchingWisdom - these would be the final ids which we want to render on the page

        // compute report_id against $FinalIds first
        $LoadAlertData = new LoadData();
        $AlertReportId = $LoadAlertData->loadAlertData('report_id');

        // $AlertReportId[$FinalReportIds]

         for($counter = 0; $counter<count($FinalIds); $counter++) {
             $xx = $FinalIds[$counter];
             $yy = $AlertReportId[$FinalIds[$counter]];
            // call subject
            $this->subject($AlertReportId[$FinalIds[$counter]]);
            // call deadline
            $this->deadline($AlertReportId[$FinalIds[$counter]]);
            // call button
             $this->button($AlertReportId[$FinalIds[$counter]]);
             $this->ButtonStatusArena($Email, $AlertReportId[$FinalIds[$counter]]); //if true then buttons to render inprogress and close, if false buttons to render join and not join
             echo "aaaa";
         }
    }

    public function subject ($FinalReportIds) {
        $LoadAlertData = new LoadData();
        $AlertSubject = $LoadAlertData->loadAlertData('description_subject', $FinalReportIds);
        $c = $AlertSubject;
        return $AlertSubject;
    }

    public function deadline ($FinalReportIds) {
        $LoadAlertData = new LoadData();
        $AlertDeadline = $LoadAlertData->loadAlertData('deadline', $FinalReportIds);
        $c = $AlertDeadline;
        return $AlertDeadline;
    }

    public function button ($FinalReportIds/*final ids, button status, */) {
        // final ids from instanceofCase
            // check the status of button of the final ids in alert if its closed or not
        $LoadAlertData = new LoadData();
        $AlertStatusModerator = $LoadAlertData->loadAlertData('alert_status_moderator', $FinalReportIds);
        $AlertStatusFlp = $LoadAlertData->loadAlertData('alert_status_flp', $FinalReportIds);
        $AlertStatusMutual = $LoadAlertData->loadAlertData('alert_status_mutual', $FinalReportIds);
        if ($AlertStatusModerator==='Closed' && $AlertStatusFlp==='Closed' && $AlertStatusMutual==='Closed') {
            return false;
        }
        else {
            return true;
        }

        // check button status from arena if its joined, not joined, in progress
        // HEADS-UP: associateAlert stores the ids of those alert in arena expert which has been joined

    }

    public function ButtonStatusArena ($Email, $FinalReportIds) {
        $LoadArenaData = new LoadData();
        $ArenaAssociatedAlert = $LoadArenaData->loadArenaData('associated_alert', $Email);
        if (strpos($ArenaAssociatedAlert[0], $FinalReportIds) !== false) {
            return true;
        }
        else {
            return false;
        }
    }

    public function Closed () {
        // checks from alert_status_moderator, alert_status_flp and alert_status_mutual,
        // if there then the only button should be closed
        // if not there then check join, not join and in progress
    }

    public function JoinNotJoin () {
        // checks from associated alert in arena,
        // if the $FinalReportIds is not there then join button and not join button should render
    }

    public function InProgressClose () {
        // checks from associated alert in arena,
        // if the $FinalReportIds is there then in progress and close button should render
    }

    public function Declined () {
        // checks from NotAssociatedAlert in arena,
        // if the $FinalReportIds is there then Declined Button should render
    }

    public function ClickButton () {
        // on clicking join button update database and call InProgressClose
        // on clicking not join button update database(add another column NotAssociatedAlert) and call Declined
        // on clicking in progress button call discussion page
        // on clicking close button
        // this is going to be complex - if its been closed by flp or an expert
        // if closed by flp update database
        // if closed by expert add another column in arena, where all experts who closed the case appear - then update database
        // after every update call Closed
    }


    // Matches Language of Alert and Arena
    public function MatchLanguage ($Email) {
        $MatchedLanguageId = [];
        $LoadAlertData = new LoadData();
        $LoadArenaData = new LoadData();
        $AlertLanguage = $LoadAlertData->loadAlertData('report_locale');
        $ArenaLanguage = $LoadArenaData->loadArenaData('report_locale', $Email);

        for ($AlertLanguageCounter = 0; $AlertLanguageCounter<count($AlertLanguage); $AlertLanguageCounter++) {
            if (strpos($AlertLanguage[$AlertLanguageCounter], $ArenaLanguage[0]) !== false) {
                array_push($MatchedLanguageId, $AlertLanguageCounter);
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
        $ArenaSkills = $LoadArenaData->loadArenaData('skill', $Email);

        $ArenaSkills = explode(',', $ArenaSkills[0]);
        for ($ArenaSkillsCounter = 0; $ArenaSkillsCounter<count($ArenaSkills); $ArenaSkillsCounter++) {
            for ($AlertSkillsCounter = 0; $AlertSkillsCounter<count($AlertSkills); $AlertSkillsCounter++) {
                if (strpos($AlertSkills[$AlertSkillsCounter], $ArenaSkills[$ArenaSkillsCounter]) !== false) {
                    array_push($MatchedSkillsId, $AlertSkillsCounter);
                }
            }
        }
        // takes the skills of arena expert and breaks it down into an array then matches those against all the alert event categories
        return $MatchedSkillsId;
        // NOTE: this array is from ALERT
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
        $alert = $wpdb->get_results( "SELECT report_id, tempID, event_category, event_description, description_subject FROM {$wpdb->prefix}tra_reports", OBJECT );
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
    public function loggedAlert(){
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