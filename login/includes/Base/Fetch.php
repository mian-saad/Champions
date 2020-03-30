<?php
/**
 * @package register
 */
namespace Incl\Base;

use \Incl\Base\BaseController;

session_start();

class Fetch extends BaseController {

    public function register() {
        add_action('wp_ajax_fetch', array($this, 'fetch'));
        add_action('wp_ajax_nopriv_fetch', array($this, 'fetch'));
    }

    public function fetch(){

        $clicked = sanitize_text_field( $_GET['id'] );

        if ($clicked === 'flogin')
            $this->page1();
        else if ($clicked === 'fregister')
            $this->page2();
        else if ($clicked === 'register')
            $this->page2_1();
        else if ($clicked === 'register1')
            $this->page2_2();
        else if ($clicked === 'registered')
            $this->page3();
        else if ($clicked === 'login')
            $this->page4();
        else if ($clicked === 'alerter')
            $this->page5();
        else if ($clicked === 'cmnt')
            $this->page6();
        else if ($clicked === 'alertBack')
            $this->page4();
        else if ($clicked === 'showcmnt')
            $this->display_comment();
        else if ($clicked === 'join')
            $this->associateAlert();
        else if ($clicked === 'inpro')
            $this->pageDetail();



        wp_die();

    }

    // LOGIN PAGE
    public function page1() {

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

    // REGISTRATION PAGE 1
    public function page2() {

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

    // REGISTRATION PAGE 2
    public function page2_1() {

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

        $this->registerData($data);

        $html = "<div id=\"contain\">
                    <h3>Welcome to ARENA REGISTRATION</h3>
                    <h5>Domain Expert - Skills Information</h5>
                    <form id=\"arena-registration\" method=\"GET\" action=\"#\" data-url=\"<?php echo admin_url('admin-ajax.php'); ?>\">
                        <p>Please Select your skills</p>
                         <div class='cheqd'><input name='skills' id='juvenile_lawyer' value='juvenile_lawyer' type=\"checkbox\" class=\"checkbox\" > <label for='juvenile_lawyer'> Juvenile Lawyer </label></div> 
                         <div class='cheqd'><input name='skills' id='prosecutor' value='prosecutor' type=\"checkbox\" class=\"checkbox\" > <label for='prosecutor'> Prosecutor </label></div>
                         <div class='cheqd'><input name='skills' id='psychologist' value='psychologist' type=\"checkbox\" class=\"checkbox\" > <label for='psychologist'> Psychologist  </label></div>
                         <div class='cheqd'><input name='skills' id='social_media' value='social_media' type=\"checkbox\" class=\"checkbox\" > <label for='social_media'> Social Media </label></div>
                         <div class='cheqd'><input name='skills' id='radicalization' value='radicalization' type=\"checkbox\" class=\"checkbox\" > <label for='radicalization'> Radicalisation </label></div>
                         <div class='cheqd'><input name='skills' id='youngpeople' value='youngpeople' type=\"checkbox\" class=\"checkbox\" > <label for='youngpeople'> Young People </label></div>
                         <div class='cheqd'><input name='skills' id='school' value='school' type=\"checkbox\" class=\"checkbox\" > <label for='school'> School </label></div>
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

    // REGISTRATION PAGE 3
    public function page2_2() {

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

    //SUMMARY PAGE, REGISTERS USER, STORES USER IN DATABASE
    public function page3() {

        $description = sanitize_text_field( $_GET['description'] );

        //array_push($data, $description);
        $data = array('description' => $description);

        $this->registerData($data);

        $this->page1();


    }

    // LOGGED IN PAGE 1
    public function page4() {

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
        $alert = $wpdb->get_results( "SELECT report_id, event_category, event_description, description_subject FROM {$wpdb->prefix}tra_reports", OBJECT );
        $arena = $wpdb->get_results( "SELECT first_name, last_name, email, password, associatedAlert, skill FROM {$wpdb->prefix}arena", OBJECT );
        for ($r; $r<count($arena); $r++) {
            if (($email === $arena[$r]->email && $pass === $arena[$r]->password) OR ($clicked === 'alertBack')) {
                $_SESSION['mail'] = $arena[$r] -> email;
                $_SESSION["iterator"] = $r ;
                if ($clicked != 'alertBack' && $clicked != 'join'){
                    echo "<div class='row'>";
                        echo "<div class='col-8'></div>";
                        echo "<div class=' col-4'>";
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
                            if ($arenaSkills[$i] === $alertSkills[$k]){
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

    // STORE ASSOCIATED ALERT TO ARENA
    public function associateAlert(){
        global $wpdb;
        $alertV = sanitize_text_field( $_GET['alertV'] );
        $mail = $_SESSION['mail'];
        $_SESSION['assocAlert'] = $alertV;
        $arena = $wpdb->get_results( "SELECT associatedAlert FROM {$wpdb->prefix}arena WHERE email='$mail'", OBJECT );
        $data = $alertV.",".$arena[0]->associatedAlert;


        $wpdb -> update('wp_arena', array('associatedAlert' => $data), array('email' => $mail));
        $this->page4();
    }

    // LOGGED IN PAGE 2
    public function page5() {

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

    public function pageDetail() {

        global $wpdb;
        $alertID = sanitize_text_field( $_GET['alertID'] );
        $_SESSION['alertd'] = $alertID;
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
        echo "<p>".$alert[$alertID] -> event_description."</p><br><hr>";

        echo "<div class='display_comment' id='display_comment'>";
        echo $this->display_comment();
        echo "</div>";

        echo "<div class='row writeComment'>";
            echo "<div class='col-8'>";
                echo "<input class='get_comment' type='text' id='get_comment' placeholder='Add Comment'>";
            echo "</div>";

            echo "<div class='col-4'>";
                echo "<button class='submit_comment' id='submit_comment' >Add Comment</button><br><br>";
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

    }

    // ADD COMMENTS
    public function page6() {

        global $wpdb;
        $comment = sanitize_text_field( $_GET['comment_data'] );
        $name = sanitize_text_field( $_GET['mail'] );
        $alert_ID = $_SESSION['alertd'];
        $data = array( 'comment_data' => $comment, 'comment_name' => $name, 'alert_ID' => $alert_ID );
        $commentsData = $wpdb->prefix . 'commentsData';
        $wpdb->insert($commentsData, $data);
        $this->display_comment();
    }

    // DISPLAYS COMMENT
    public function display_comment() {

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
            $fn = $_SESSION["champions"] + $_SESSION["champions1"] + $_SESSION["champions2"];
            $wpdb->insert($arena, $fn);
        }
        else
            echo "errrr";

    }
}