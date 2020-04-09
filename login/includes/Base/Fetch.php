<?php
/**
 * @package register
 */
namespace Incl\Base;

use \Incl\Base\BaseController;

session_start();

class Fetch extends BaseController {

    // Registers Fetch
    public function register() {
        add_action('wp_ajax_fetch', array($this, 'fetch'));
        add_action('wp_ajax_nopriv_fetch', array($this, 'fetch'));
    }

    // Redirection Conditions
    public function fetch(){

        $clicked = sanitize_text_field( $_GET['id'] );

        if ($clicked === 'flogin')
            $this->arenaLogin();
        else if ($clicked === 'fregister')
            $this->registerPersonal();
        else if ($clicked === 'register')
            $this->registerSkills();
        else if ($clicked === 'register1')
            $this->registerDescription();
        else if ($clicked === 'registered')
            $this->registerVerify();
        else if ($clicked === 'login')
            $this->loggedMain();
        else if ($clicked === 'alerter')
            $this->loggedParticipants();
        else if ($clicked === 'cmnt')
            $this->loggedAddComments();
        else if ($clicked === 'alertBack')
            $this->loggedMain();
        else if ($clicked === 'showcmnt')
            $this->loggedDisplayComments();
        else if ($clicked === 'join')
            $this->loggedAlert();
        else if ($clicked === 'registerVerify')
            $this->registerVerifyCode();
        else if ($clicked === 'inpro')
            $this->loggedPageSection();
        else if ($clicked === 'addRecommendation')
            $this->loggedDisplayRecommendation();
        else if ($clicked === 'updateRecommendation')
            $this->loggedUpdateRecommendation();

        wp_die();

    }

    // Log In Page
    public function arenaLogin() {

        $clicked = sanitize_text_field( $_GET['id'] );

        if ($clicked === 'registered')
            echo "<p>USER Registered</p>";

        $html = "
                    <h3>Welcome to ARENA Module</h3>
                    <h4>Login Form</h4>
                    <form id=\"arena-login\" method=\"POST\" action=\"#\" >
                        <div class='row'>
                            <div class='col-2'></div>
                            <div class='col-8'>
                                <label>Email</label>
                                <input id=\"naam\" type=\"email\" name=\"email\"><br>
                                      
                                <label>Password</label>
                                <input id=\"paas\" type=\"password\" name=\"password\" minlength=\"4\">
                                  
                                <button class='bt' id=\"send\" type=\"submit\" onclick=\"return false;\">Login</button>
                                <input type=\"hidden\" name=\"action\" value=\"getData\">
                            </div>
                            <div class='col-2'></div>
                        </div>
                    </form>";
        echo $html;
    }

    // Registration Page - Personal Data
    public function registerPersonal() {

        $html = "<div id=\"contain\">
                    <h4>Welcome to ARENA REGISTRATION</h4>
                    <h5>Domain Expert - Personal Information</h5>
                    <form id=\"arena-registration\" method=\"GET\" action=\"#\" data-url=\"<?php echo admin_url('admin-ajax.php'); ?>\">
                            
                        <div class='row'>
                            <div class='col-6'>
                                <label>Title</label>
                                <select id=\"title\" required>
                                //disabling to make <i>required</i> work
            <!--                <option disabled=\"disabled\" selected=\"selected\">Choose Option</option>-->
                                <option>Lawyer</option>
                                <option>Social Worker</option>
                                <option>Youth Social Worker</option>
                                <option>Researcher</option>
                                <option>Prosecutor</option>
                                <option>Expert on Youth Gangs and Organised Crime</option>
                                <option>Former Member of Radicalised Group</option>
                                <option>Psychologist</option>
                                <option>Hate Speech Specialist</option>
                                <option>Social Media Specialist</option>
                                <option>Radicalisation Expert</option>
                                <option>Teacher</option>
                                <option>Educator</option>
                                <option>Trainer</option>
                            </select>
                            </div>
                            <div class='col-6'>
                                <label>Country</label>
                                <select id=\"country\" name=\"country\" required>
                               <option value=\"Austria\">Austria</option>
                               <option value=\"Belgium\">Belgium</option>
                               <option value=\"Bulgaria\">Bulgaria</option>
                               <option value=\"Croatia\">Croatia</option>
                               <option value=\"Czech Republic\">Czech Republic</option>
                               <option value=\"Denmark\">Denmark</option>
                               <option value=\"Estonia\">Estonia</option>
                               <option value=\"Finland\">Finland</option>
                               <option value=\"Germany\">Germany</option>
                               <option value=\"Hungary\">Hungary</option>
                               <option value=\"Iceland\">Iceland</option>
                               <option value=\"Italy\">Italy</option>
                               <option value=\"Liechtenstein\">Liechtenstein</option>
                               <option value=\"Lithuania\">Lithuania</option>
                               <option value=\"Luxembourg\">Luxembourg</option>
                               <option value=\"Macedonia\">Macedonia</option>
                               <option value=\"Netherlands\">Netherlands</option>
                               <option value=\"Norway\">Norway</option>
                               <option value=\"Poland\">Poland</option>
                               <option value=\"Portugal\">Portugal</option>
                               <option value=\"Romania\">Romania</option>
                               <option value=\"Spain\">Spain</option>
                               <option value=\"Sweden\">Sweden</option>
                               <option value=\"Switzerland\">Switzerland</option>
                             </select><br><br>
                            </div>
                        </div>
                        
                        <div class='row'>
                            <div class='col-6'>
                                <label>First Name</label>
                                <input type=\"text\" id=\"first_name\" required>
                            </div>
                            <div class='col-6'>
                                <label>Last Name</label>
                                <input class=\"field-msg\" type=\"text\" id=\"last_name\" required>
                            </div>
                        </div>
                        
                        <div class='row'>
                            <div class='col-6'>
                                <label>Email</label>
                                <input type=\"email\" id=\"email\" required>
                            </div>
                            <div class='col-6'>
                                <label>Password</label>
                                <input type=\"password\" id=\"password\" minlength=\"4\" required>
                            </div>
                        </div>
                        
                        <div class='row'>
                            <div class='col-12'>
                                <button class='bt' id=\"next_register\" type=\"submit\">NEXT</button>
                            </div>
                        </div>
                        <input type=\"hidden\" name=\"action\" value=\"getData\">
                                                        
                    </form>
                </div>";
        echo $html;
    }

    // Registration Page Skills
    public function registerSkills() {

        $first_name = sanitize_text_field( $_GET['first_name'] );
        $last_name = sanitize_text_field( $_GET['last_name'] );
        $country = sanitize_text_field( $_GET['country'] );
        $email = sanitize_email( $_GET['email'] );
        $password = sanitize_text_field( $_GET['password'] );
        $title = sanitize_text_field( $_GET['title'] );
        $data = array(
            'first_name' => $first_name,
            'last_name' => $last_name,
            'country' => $country,
            'email' => $email,
            'password' => $password,
            'title' => $title
        );
        $verifyCode = rand(1111, 9999);
        $_SESSION["verifyCode"] = $verifyCode;

        wp_mail( $email, "Verification Code", "Yor Verification Code: ". $verifyCode ."");

        $this->registerData($data);

        $html = "<div id=\"contain\">
                    <h3>Welcome to ARENA REGISTRATION</h3>
                    <h5>Domain Expert - Skills Information</h5>
                    <form id=\"arena-registration\" method=\"GET\" action=\"#\" data-url=\"<?php echo admin_url('admin-ajax.php'); ?>\">
                        <p>Please Select your skills</p>
                         <div class='cheqd'><input name='skills' id='juvenile_lawyer' value='Juvenile Lawyer' type=\"checkbox\" class=\"checkbox\" > <label for='juvenile_lawyer'> Juvenile Lawyer </label></div> 
                         <div class='cheqd'><input name='skills' id='prosecutor' value='Prosecutor' type=\"checkbox\" class=\"checkbox\" > <label for='prosecutor'> Prosecutor </label></div>
                         <div class='cheqd'><input name='skills' id='psychologist' value='Psychologist' type=\"checkbox\" class=\"checkbox\" > <label for='psychologist'> Psychologist  </label></div>
                         <div class='cheqd'><input name='skills' id='social_media' value='Social Media' type=\"checkbox\" class=\"checkbox\" > <label for='social_media'> Social Media </label></div>
                         <div class='cheqd'><input name='skills' id='radicalization' value='Radicalization' type=\"checkbox\" class=\"checkbox\" > <label for='radicalization'> Radicalisation </label></div>
                         <div class='cheqd'><input name='skills' id='youngpeople' value='Young People' type=\"checkbox\" class=\"checkbox\" > <label for='youngpeople'> Young People </label></div>
                         <div class='cheqd'><input name='skills' id='school' value='School' type=\"checkbox\" class=\"checkbox\" > <label for='school'> School </label></div>
                         <div class='cheqd'><input name='skills' id='hatespeech' value='hatespeech' type=\"checkbox\" class=\"checkbox\" > <label for='hatespeech'> Hate Speech  </label></div>
                         <div class='cheqd'><input name='skills' id='deradicalisation' value='deradicalisation' type=\"checkbox\" class=\"checkbox\" > <label for='deradicalisation'> Deradicalisation  </label></div>
                         <div class='cheqd'><input name='skills' id='organisedcrime' value='organisedcrime' type=\"checkbox\" class=\"checkbox\" > <label for='organisedcrime'> Organised Crime </label></div>
                         <div class='cheqd'><input name='skills' id='youthgang' value='youthgang' type=\"checkbox\" class=\"checkbox\" > <label for='youthgang'> Youth Gang  </label></div>
                         <div class='cheqd'><input type=\"checkbox\" id='other' class=\"checkbox\" onclick=\"if(this.checked){ document.getElementById('othr').focus();}\" > <label for='other'> Other </label></div>
                        <div><input id='othr' style='width: 500px' type='text'></div>
   
                        <div class=\"p-t-15\">
                            <button class='bt' id=\"next1_register\" type=\"submit\">NEXT</button>
                        </div>
                        <input type=\"hidden\" name=\"action\" value=\"getData\">
                    </form>
                </div>";
        echo $html;

    }

    // Registration Page Description
    public function registerDescription() {

        $skill = sanitize_text_field(stripslashes($_GET['skill']));
        $data = array('skill' => $skill);
        $x = str_replace( array('[',']', '"'),"", $data);
        $this->registerData($x);
        $html = "<div id=\"contain\">
                    <h3>Welcome to ARENA REGISTRATION</h3>
                    <h5>Domain Expert - Skills/Bio Information</h5>
                    <form id=\"arena-registration\" method=\"GET\" action=\"#\" data-url=\"<?php echo admin_url('admin-ajax.php'); ?>\">
                        
                        <div> <label> Please provide a description of your experience and expertise </label></div> 
                        <div><textarea name='description' id='description'  type=\"text\" ></textarea> </div>
                        
                        <div class=\"p-t-15\">
                            <button class='bt' id=\"registered\" type=\"submit\">REGISTER</button>
                        </div>
                        <input type=\"hidden\" name=\"action\" value=\"getData\">
                    
                        </form>
                    </div>";
        echo $html;

    }

    // Email verification Form
    public function registerVerify() {

        $description = sanitize_text_field( $_GET['description'] );
        $data = array('description' => $description);
        $this->registerData($data);

        $html = "<form id=\"frm-mobile-verification\">
                    <div class=\"form-row\">
                        <label>A Verification Code has been sent to Your Email. Please Verify your Email.</label>		
                    </div>
                    <div class=\"form-row\">
                        <input type=\"number\"  id=\"emailCode\" class=\"form-input\" placeholder=\"Enter the Verification Code\">		
                    </div><br>
                    <div class=\"form-row\">
                        <input id=\"verify\" type=\"button\" class=\"btnVerify\" value=\"Verify\" >		
                    </div>
                 </form>";
        echo $html;
    }

    // Email verification Form
    public function registerVerifyCode(){
        $eCode = sanitize_text_field( $_GET['eCode'] );

        if (strval($_SESSION["verifyCode"]) === $eCode) {
            echo "<h3>Success</h3>";
            $this->registerData($eCode);
        }
        else {
            echo "<h3>Failure</h3>";
        }
    }

    // Stores Domain Expert to Database
    public function registerData($data){

        global $wpdb;
        $clicked = sanitize_text_field( $_GET['id'] );
        $arena = $wpdb->prefix . 'arena';

        if ($clicked === 'register')
            $_SESSION["champions"] = $data ;
        else if ($clicked === 'register1')
            $_SESSION["champions1"] = $data ;
        else if ($clicked === 'registered') {
            $_SESSION["champions2"] = $data ;
        }
        else if ($clicked === 'registerVerify'){
            $fn = $_SESSION["champions"] + $_SESSION["champions1"] + $_SESSION["champions2"];
            $wpdb->insert($arena, $fn);
        }
        else
            echo "errrr";

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