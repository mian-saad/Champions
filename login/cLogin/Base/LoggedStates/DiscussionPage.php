<?php

namespace Contain\Base\LoggedStates;

class DiscussionPage {

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
        wp_mail( $inviteEmail, "Champions", $this->string_file['invitation_message'], array('Content-Type: text/html; charset=UTF-8'));
    }
}