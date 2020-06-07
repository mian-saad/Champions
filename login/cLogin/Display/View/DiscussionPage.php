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
        $html .= "<br><hr><br>";
        $html .= $this->RenderRecommendation($Email, $ID);
        echo $html;
    }

//    public function RenderRecommendation($Email, $ID) {
//
//        $loggedState = new View\LandingPage();
//        $html = $loggedState ->header($Email);
//        $html .= "<div class='row'>";
//        $html .= "<div class='col-8'>";
//        $html .= $this->PostSubject($ID);
//        $html .= $this->PostDescription($ID);
//        $html .= $this->DisplayRecommendation($ID);
//        if ($this->JoinCommentsSectionValidation($Email, $ID)) {
//            $html .= $this->RecommendationsSection($ID);
//        }
//        $html .= $this->BackButton();
//        $html .= "</div>";
//        $html .= $this->Participants($ID);
//        $html .= "</div>";
//        echo $html;
//    }

    public function RenderRecommendation($Email, $ID) {

        $html = "<div class='row'>";
        $html .= "<div class='col-8'>";
        $html .= "<h4>Recommendations</h4>";
        $html .= $this->DisplayRecommendation($ID);
        if ($this->JoinCommentsSectionValidation($Email, $ID)) {
            $html .= $this->RecommendationsSection($ID);
        }
        $html .= "</div>";
        $html .= "</div>";
        return $html;
    }

    public function JoinCommentsSectionValidation ($Email, $ID) {
        // if associatedAlert contains $ID where email is $Email
        $LoadArenaData = new LoadData();
        $ArenaAssociatedAlertReportId = $LoadArenaData->loadArenaData('associated_alert', $Email);
        $ArenaClosedAssociatedAlertReportId = $LoadArenaData->loadArenaData('closed_associated_alert', $Email);
        if (strpos($ArenaAssociatedAlertReportId[0], $ID) !== false) {
            if (strpos($ArenaClosedAssociatedAlertReportId[0], $ID) !== false) {
                return true;
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
        $this->ValidationBeforeMail($ID, 'Recommendation');
        $this->RenderRecommendation($Email, $ID);
    }

    public function UpdateRecommendation ($ID, $Email, $Data, $RId) {
        global $wpdb;
        $wpdb->update("wp_recommendationData", array('recommendation_data' => $Data), array('recommendation_id' => $RId));
        $this->ValidationBeforeMail($ID, 'Recommendation');
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
                $html .= "<p><b>".$Name[$counter].": </b><i>".$Comments[$counter]."</i></p>";
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
        $this->ValidationBeforeMail($ID, 'Comment');
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
        $html .= "<br><br><div id='invite'><input id='invitation-email'/><br><button id='invitation-button' class='button invitation-button'>Invite</button></div>";
        $html .= "</div>";
        return $html;
    }

    public function ClickButton($Email) {
        $loggedState = new View\LandingPage();
        $loggedState -> RenderPage($Email);
    }


    // in every action get the alert id and the corresponding action
    // search that id in associatedAlert in Arena table
    // for every user in arena where there is this alert id in associatedAlert get the email address
    // then call email

    public function ValidationBeforeMail($UserID, $State) {
        $Emails = [];
        $increment = 0;
        global $wpdb;
        $ArenaData = $wpdb->get_results( "SELECT email, associatedAlert FROM {$wpdb->prefix}arena", OBJECT );
        for ($counter = 0; $counter<count($ArenaData); $counter++) {
            if (strpos($ArenaData[$counter]->associatedAlert, $UserID) !== false) {
                $Emails[$increment] = $ArenaData[$counter]->email;
                $increment++;
            }
        }
        $this->SendMail($Emails, $State);
    }

    public function SendMail($Emails, $State) {
        for ($counter = 0; $counter<count($Emails); $counter++) {
            wp_mail( "$Emails[$counter]", "Arena Notification Module", "The case you are part of has a new ".$State."", array('Content-Type: text/html; charset=UTF-8'));
        }
    }

}