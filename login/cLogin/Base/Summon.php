<?php
/**
 * @package register
 */
namespace Contain\Base;

use \Contain\Base\BaseController;

session_start();

class Summon extends BaseController {

    // Registers Summon
    public function register() {
        add_action('wp_ajax_summon', array($this, 'summon'));
        add_action('wp_ajax_nopriv_summon', array($this, 'summon'));
    }

    // Redirection Conditions
    public function summon(){

        $clicked = sanitize_text_field( $_GET['id'] );

        switch ($clicked) {

            case 'FormLogin':
                $loggedState = new LoggedStates\LoginForm();
                $loggedState->arenaLogin();

            case 'login':
                $loggedState = new LoggedStates\LandingPage();
                $loggedState->loggedMain();


        }
//        if ($clicked === 'FormLogin') {
//            $loggedState = new LoggedStates\LoginForm();
//            $loggedState->arenaLogin();
//        }
//        else if ($clicked === 'login')
//            $this->loggedMain();
//        else if ($clicked === 'alerter')
//            $this->loggedParticipants();
//        else if ($clicked === 'cmnt')
//            $this->loggedAddComments();
//        else if ($clicked === 'alertBack')
//            $this->loggedMain();
//        else if ($clicked === 'showcmnt')
//            $this->loggedDisplayComments();
//        else if ($clicked === 'join')
//            $this->loggedAlert();
//        else if ($clicked === 'inpro')
//            $this->loggedPageSection();
//        else if ($clicked === 'addRecommendation')
//            $this->loggedDisplayRecommendation();
//        else if ($clicked === 'updateRecommendation')
//            $this->loggedUpdateRecommendation();

        wp_die();

    }

    // Log In Page
    public function arenaLogin() {

        $clicked = sanitize_text_field( $_GET['id'] );

        if ($clicked === 'registered')
            echo "<p>USER Registered</p>";

        $html = "
                    <h2>Welcome to ARENA Module</h2><br>
                    <!-- <h4>Login Form</h4> -->
                    <form id=\"arena-login\" method=\"POST\" action=\"#\" >
                        <div class='row'>
                            <div class='col-3'></div>
                            <div class='col-6'>
                                <label>Email</label>
                                <input id=\"naam\" type=\"email\" name=\"email\"><br>
                                      
                                <label>Password</label>
                                <input id=\"paas\" type=\"password\" name=\"password\" minlength=\"4\">
                                  
                                <button class='button login-button' id=\"send\" type=\"submit\" onclick=\"return false;\">Login</button>
                                <input type=\"hidden\" name=\"action\" value=\"getData\">
                            </div>
                            <div class='col-3'></div>
                        </div>
                    </form>";
        echo $html;
    }

    // Logged In Main Page
    public function loggedMain() {

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
                    echo "<div class='row'>";
                        echo "<div class='col-9'></div>";
                        echo "<div class=' col-3'>";
                            echo "Logged in as <b class='userEmail'>".$arena[$r] -> first_name." ".$arena[$r] -> last_name."</b>";

                        echo "</div>";

                    echo "</div>";
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
                                        echo "<button id='inprogress$id' value='$j' class='inprogress col-3' >IN PROGRESS</button><br>";
                                        $o = 999;
                                    }
                                    elseif($arenaID[$o] === ""){
                                        $event = $alert[$j] -> report_id;
                                        echo "<button id='join$id' class='join' value='$event' class='col-3'>JOIN</button>";
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

        echo "<button id='alertBack'>Back</button>";
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

        echo "<h3>".$alert[$alertID] -> description_subject."</h3>";
            echo "<p>".$alert[$alertID] -> event_description."</p><br><hr>";

            echo "<div class='display_comment' id='display_comment'>";
                echo $this->loggedDisplayComments();
            echo "</div>";

            echo "<div class='row writeComment'>";
                echo "<div class='col-8'>";
                    echo "<input class='get_comment' type='text' id='get_comment' placeholder='Add Comment'>";
                echo "</div>";
                echo "<div class='col-4'>";
                    echo "<button class='submit_comment' id='submit_comment' >Add Comment</button><br><br>";
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
                    echo "<button id='addRecommendation'>Add</button>";
                echo "</div>";
            echo "</div>";


            echo "<div class='row writeComment'>";
                echo "<div class='col-8'>";
                    echo "<input type='text' class='invite'>";
                echo "</div>";
                echo "<div class='col-4'>";
                    echo "<button class='invite'>Invite Expert</button>";
                echo "</div>";
            echo "</div>";
            echo "<button id='alertBack' class='alertBack'>Back</button>";

        echo "</div>";
    }

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


}