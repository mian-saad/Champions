<?php

namespace Contain\Display\View;

use Contain\Display\Controller\LoadData;
use Contain\Display\View;

class DiscussionPage {

    public function Render($Email, $ID) {

        $loggedState = new View\LandingPage();
        $html = $loggedState ->header($Email);
        $html .= "<div class='row'>";
        $html .= "<div class='col-8'>";
        $html .= $this->PostSubject($ID);
        $html .= $this->PostDescription($ID);
        $html .= $this->DisplayComments($ID);
        if ($this->JoinCommentsSectionValidation($Email, $ID)) {
            $html .= $this->CommentsSection($ID);
        }
        $html .= $this->BackButton();
        $html .= "</div>";
        $html .= $this->Participants($ID);
        $html .= "</div>";
        echo $html;
    }

    public function RenderRecommendation($Email, $ID) {

        $loggedState = new View\LandingPage();
        $html = $loggedState ->header($Email);
        $html .= "<div class='row'>";
        $html .= "<div class='col-8'>";
        $html .= $this->PostSubject($ID);
        $html .= $this->PostDescription($ID);
        $html .= $this->DisplayRecommendation($ID);
        if ($this->JoinCommentsSectionValidation($Email, $ID)) {
            $html .= $this->RecommendationsSection($ID);
        }
        $html .= $this->BackButton();
        $html .= "</div>";
        $html .= $this->Participants($ID);
        $html .= "</div>";
        echo $html;
    }

    public function JoinCommentsSectionValidation ($Email, $ID) {
        // if associatedAlert contains $ID where email is $Email
        $LoadArenaData = new LoadData();
        $ArenaAssociatedAlertReportId = $LoadArenaData->loadArenaData('associated_alert', $Email);
        $ArenaClosedAssociatedAlertReportId = $LoadArenaData->loadArenaData('closed_associated_alert', $Email);
        if (strpos($ArenaAssociatedAlertReportId[0], $ID) !== false) {
            if (strpos($ArenaClosedAssociatedAlertReportId[0], $ID) !== false) {
                return false;
            }
            return true;
        }
        return false;
    }

    public function DisplayRecommendation ($AssociatedAlert) {
        $LoadRecommendationsData = new LoadData();
        $Recommendations = $LoadRecommendationsData->loadRecommendationData('recommendation_data', $AssociatedAlert);
        $Name = $LoadRecommendationsData->loadRecommendationData('recommendation_name', $AssociatedAlert);
        $RecommendationID = $LoadRecommendationsData->loadRecommendationData('recommendation_id', $AssociatedAlert);
        $RecommendationEmail = $LoadRecommendationsData->loadRecommendationData('recommendation_email', $AssociatedAlert);
        $html = "<div class='row'>";
        $html .= "<div id='DisplayRecommendationsSection' class='col-8'>";
        for ($counter = 0; $counter<count($Recommendations);  $counter++) {
            $html .= "<p><b>".$Name[$counter].": </b><span class='RecommendClass' name='".$RecommendationEmail[$counter]."' id='".$RecommendationID[$counter]."' value='".$Name[$counter]."'>".$Recommendations[$counter]."</span></p>";
        }
        $html .= "</div>";
        $html .= "</div>";
        return $html;
    }

    public function InsertRecommendation ($ID, $Email, $Data) {
        global $wpdb;
        $LoadArenaData = new LoadData();
        $Name = $LoadArenaData->loadArenaData('first_name', $Email);
        $RId = rand();
        $data = array('recommendation_data' => $Data, 'recommendation_email' => $Email,'recommendation_name' => $Name[0], 'recommendation_id' => $RId ,'alert_ID' => $ID);
        $wpdb->insert('wp_recommendationData', $data);
        $this->RenderRecommendation($Email, $ID);
    }

    public function UpdateRecommendation ($ID, $Email, $Data, $RId) {
        global $wpdb;
        $wpdb->update("wp_recommendationData", array('recommendation_data' => $Data), array('recommendation_id' => $RId));
        $this->RenderRecommendation($Email, $ID);
    }

    public function RecommendationsSection($ID) {
        $html = "<div class='row'>";
            $html .= "<div id='RecommendationsSection' class='col-8'>";
                $html .= "<textarea id='RecommendationText'></textarea><br>";
                $html .= "<button class='button' id='Recommend' value='".$ID."'>Add Recommendation</button><br>";
            $html .= "</div>";
        $html .= "</div><br>";

        return $html;
    }

    public function DisplayComments ($AssociatedAlert) {
        $LoadCommentsData = new LoadData();
        $Comments = $LoadCommentsData->loadCommentData('comment_data', $AssociatedAlert);
        $Name = $LoadCommentsData->loadCommentData('comment_name', $AssociatedAlert);

        $html = "<div class='row'>";
        $html .= "<div id='DisplayCommentSection' class='col-8'>";
            for ($counter = 0; $counter<count($Comments);  $counter++) {
                $html .= "<p><b>".$Name[$counter].":</b> ".$Comments[$counter]."</p>";
            }
        $html .= "</div>";
        $html .= "</div>";
        return $html;
    }

    public function CommentsSection($ID) {
        $html = "<div class='row'>";
            $html .= "<div id='CommentSection' class='col-8'>";
                $html .= "<textarea id='commentText'></textarea><br>";
                $html .= "<button class='button' id='comment' value='".$ID."'>Comment</button><br>";
            $html .= "</div>";
        $html .= "</div><br>";

        return $html;
    }

    public function CommentsLogic ($ID, $Email, $Data) {
        global $wpdb;
        $LoadArenaData = new LoadData();
        $Name = $LoadArenaData->loadArenaData('first_name', $Email);
        $data = array('comment_data' => $Data, 'comment_name' => $Name[0], 'alert_ID' => $ID);
        $wpdb->insert('wp_commentsData', $data);
        $this->Render($Email, $ID);
    }

    public function BackButton () {
        $html = "<button class='button' id='BackDiscussion'>Back</button>";
        return $html;
    }

    public function PostSubject ($ID) {
        $LoadAlertData = new LoadData();
        $AlertCaseSubject = $LoadAlertData->loadAlertData('description_subject', $ID);
        $html = "<h4>".$AlertCaseSubject[0]."</h4>";
        return $html;
    }

    public function PostDescription ($ID) {
        $LoadAlertData = new LoadData();
        $AlertCaseDescription = $LoadAlertData->loadAlertData('event_description', $ID);
        $html = "<p class='description'>".$AlertCaseDescription[0]."</p>";

        return $html;
    }

    public function Participants ($ID) {
        $html = "<div class='col-4 Participants'><h4><b>Participants</b></h4>";
        $LoadArenaData = new LoadData();
        $Participants = $LoadArenaData->loadArenaData('associated_alert');
        $ParticipantName = $LoadArenaData->loadArenaData('first_name');


        for ($counter = 0; $counter<count($Participants); $counter++) {
            if (strpos($Participants[$counter], $ID) !== false) {
                $html .= "<li id='Participants'>".$ParticipantName[$counter]."</li>";
            }
        }
        $html .= "</div>";
        return $html;
    }

    public function ClickButton($Email) {
        $loggedState = new View\LandingPage();
        $loggedState -> RenderPage($Email);
    }









    // <-- OLD SECTION --> //
    // <-- OLD SECTION --> //
    // <-- OLD SECTION --> //
    // <-- OLD SECTION --> //
    // <-- OLD SECTION --> //
    // <-- OLD SECTION --> //
    // <-- OLD SECTION --> //
    // <-- OLD SECTION --> //

    public array $string_file;
    public string $plugin_path;

    // Logged In Page Section - Add Comments
    public function loggedAddComments() {

        global $wpdb;
        $comment = sanitize_text_field( $_GET['comment_data'] );
        $name = sanitize_text_field( $_GET['mail'] );
        $alert_ID = $_SESSION['alertd'];
        $data = array( 'comment_data' => $comment, 'comment_name' => $name, 'alert_ID' => $alert_ID );
        $commentsData = $wpdb->prefix . 'commentsData';
        $wpdb->insert($commentsData, $data);
        $this->loggedDisplayComments();
    }

    // Logged In Page Section - Display Comments
    public function loggedDisplayComments() {

        global $wpdb;
        $results = $wpdb->get_results( "SELECT alert_ID, comment_data, comment_name FROM {$wpdb->prefix}commentsData", OBJECT );
        for ($i=0; $i<count($results); $i++) {

            if ($_SESSION['alertd'] === $results[$i] -> alert_ID){
                echo "<b>".$results[$i] -> comment_name.": </b>";
                echo $results[$i] -> comment_data;
                echo "</br>";
            }
        }
    }

    // Logged In Page Section - Display Recommendations
    public function loggedDisplayRecommendation() {

        $clicked = sanitize_text_field( $_GET['id'] );

        if ($clicked === 'addRecommendation') {
            global $wpdb;
            $recommendation = sanitize_text_field( $_GET['recommendation'] );
            $name = sanitize_text_field( $_GET['mail'] );
            $rId = sanitize_text_field( $_GET['rId'] );
            $alert_ID = $_SESSION['alertd'];
            $data = array( 'recommendation_data' => $recommendation, 'recommendation_id' => $rId, 'recommendation_name' => $name, 'alert_ID' => $alert_ID );
            $recommendationData = $wpdb->prefix . 'recommendationData';
            $wpdb->insert($recommendationData, $data);

            $results = $wpdb->get_results( "SELECT alert_ID, recommendation_data, recommendation_name FROM {$wpdb->prefix}recommendationData", OBJECT );
            for ($i=0; $i<count($results); $i++) {

                if ($_SESSION['alertd'] === $results[$i] -> alert_ID){
                    echo "<b>".$results[$i] -> recommendation_name.": </b>";
                    $localID = rand();
                    echo "<b class='getRecomendID' id='editRecommend$localID' value='".$results[$i] -> recommendation_data."'>" . $results[$i] -> recommendation_data . "</b>";
                    echo "</br>";
                }
            }
        }
        else {
            global $wpdb;
            $results = $wpdb->get_results( "SELECT alert_ID, recommendation_data, recommendation_name FROM {$wpdb->prefix}recommendationData", OBJECT );
            for ($i=0; $i<count($results); $i++) {

                if ($_SESSION['alertd'] === $results[$i] -> alert_ID){
                    echo "<b>".$results[$i] -> recommendation_name.": </b>";
                    $localID = rand();
                    echo "<b class='getRecomendID' id='editRecommend$localID' value='".$results[$i] -> recommendation_data."'>" . $results[$i] -> recommendation_data . "</b>";
                    echo "</br>";
                }
            }
        }

    }

    public function loggedUpdateRecommendation() {
        global $wpdb;
        $recommendation = sanitize_text_field( $_GET['recommendation'] );
//        $mail = sanitize_text_field( $_GET['mail'] );
        $rId = sanitize_text_field( $_GET['rId'] );
//        $results = $wpdb->get_results( "SELECT recommendation_data FROM {$wpdb->prefix}recommendationData", OBJECT );
        $wpdb->update( "wp_recommendationData", array('recommendation_data' => $recommendation), array('recommendation_data' => $rId) );
//        echo "a";
    }

    // Logged In Page Section
    public function loggedParticipants() {

        global $wpdb;
        $alertID = sanitize_text_field( $_GET['alertID'] );
        $alert = $wpdb->get_results( "SELECT report_id, event_category, description_subject, event_description FROM {$wpdb->prefix}tra_reports", OBJECT );
        $arena = $wpdb->get_results( "SELECT first_name, associatedAlert FROM {$wpdb->prefix}arena", OBJECT );
        echo "</br>";
        echo "<div class='participants col-3' id='participants'><b>List of Participants<b><hr>";
        for ($i=0; $i<count($arena);$i++){
            $arenaID = explode(',', $arena[$i] -> associatedAlert);
            for ($j=0; $j<count($arenaID); $j++){
                if ($arenaID[$j] === $alert[$alertID] -> report_id)
                    echo "<p class='listParticipants'>".$arena[$i] -> first_name."</p>";
            }
        }
        echo "</div>";

        echo "<div id='alertD' class='alertD col-9'>";
        echo "<h3>".$alert[$alertID] -> description_subject."</h3>";
        echo "<p>".$alert[$alertID] -> event_description."</p><br>";

        echo "<button class='button' id='alertBack'>Back</button>";
    }

    // Logged In Page Section
    public function loggedPageSection() {

        global $wpdb;
        $alertID = sanitize_text_field( $_GET['alertID'] );
        $_SESSION['alertd'] = $alertID;
        $alert = $wpdb->get_results( "SELECT report_id, event_category, description_subject, event_description FROM {$wpdb->prefix}tra_reports", OBJECT );
        $arena = $wpdb->get_results( "SELECT first_name, associatedAlert FROM {$wpdb->prefix}arena", OBJECT );
        echo "<br>";
        echo "<div class='participants col-3' id='participants'><b>List of Participants<b><hr>";
        for ($i=0; $i<count($arena);$i++){
            $arenaID = explode(',', $arena[$i] -> associatedAlert);
            for ($j=0; $j<count($arenaID); $j++){
                if ($arenaID[$j] === $alert[$alertID] -> report_id)
                    echo "<p class='listParticipants'>".$arena[$i] -> first_name."</p>";
            }
        }
        echo "</div>";


        echo "<div id='alertD' class='alertD col-9'>";

        echo "<div class='row' ><h3 class='col-6' >".$alert[$alertID] -> description_subject."</h3><button class='button col-6'>Close Case</button></div>";
        echo "<p>".$alert[$alertID] -> event_description."</p><br><hr>";

        echo "<div class='display_comment' id='display_comment'>";
        echo $this->loggedDisplayComments();
        echo "</div>";

        echo "<div class='row writeComment'>";
        echo "<div class='col-8'>";
        echo "<input class='get_comment' type='text' id='get_comment' placeholder='Add Comment'>";
        echo "</div>";
        echo "<div class='col-4'>";
        echo "<button class='button submit_comment' id='submit_comment' >Add Comment</button><br><br>";
        echo "</div>";
        echo "</div>";

        echo "<hr>";

        echo "<div class='row'><div class='col-12'>";
        echo "<h3 class='recommendation_heading'>Recommendations</h3>";
        echo "</div></div>";

        echo "<div class='row'><div id='added_recommendation' class='col-12'>";
        echo "<h5>". $this->loggedDisplayRecommendation() ."</h5>";
        echo "</div></div>";

        echo "<div class='row'>";
        echo "<div class='col-8'>";
        echo "<textarea id='recommend'></textarea>";
        echo "</div>";
        echo "<div class='col-4'>";
        echo "<button class='button' id='addRecommendation'>Add</button>";
        echo "</div>";
        echo "</div>";


        echo "<div class='row writeComment'>";
        echo "<div class='col-8'>";
        echo "<input id='invite_address' type='text' class='invite'>";
        echo "</div>";
        echo "<div class='col-4'>";
        echo "<button id='invite' class='button invite'>Invite Expert</button>";
        echo "</div>";
        echo "</div>";
        echo "<button id='alertBack' class='button alertBack'>Back</button>";

        echo "</div>";
    }

    //Send Invitation
    public function send_invite($inviteEmail) {

        $this->plugin_path = plugin_dir_path( dirname(__FILE__, 3));
        $this->string_file = json_decode(file_get_contents($this->plugin_path . "assets/login-strings.json"), true);
        wp_mail( $inviteEmail, "Arena Login Module", $this->string_file['invitation_message'], array('Content-Type: text/html; charset=UTF-8'));
    }
}