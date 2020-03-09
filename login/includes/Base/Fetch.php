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
            $this->page();
        else if ($clicked === 'showcmnt')
            $this->display_comment();
        else if ($clicked === 'join')
            $this->update_data();



        wp_die();

    }

    // LOGIN PAGE
    public function page1() {

        $clicked = sanitize_text_field( $_GET['id'] );

        if ($clicked === 'registered')
            echo "<p>USER Registered</p>";

        $html = "<div class=\"card-body\" id=\"contentBox\">
                    <h3>Welcome to ARENA Module</h3>
                    <h2 class=\"title\">Login Form</h2>
                    <form id=\"arena-login\" method=\"POST\" action=\"#\" >
                        <div class=\"row row-space\">
                            <div class=\"col-2\">
                                <div class=\"input-group\">
                                    <label class=\"label\">Email</label>
                                    <input id=\"naam\" type=\"email\" name=\"email\">
                                </div>
                            </div>
                            <div class=\"col-2\">
                                <div class=\"input-group\">
                                    <label class=\"label\">Password</label>
                                    <input id=\"paas\" type=\"password\" name=\"password\" minlength=\"4\">
                                </div>
                            </div>
                        </div>
                        <div class=\"p-t-15\">
                            <button id=\"send\" type=\"submit\" onclick=\"return false;\">Login</button>
                        </div>
                        <input type=\"hidden\" name=\"action\" value=\"getData\">
                    </form>
                 </div>";
        echo $html;
    }

    // REGISTRATION PAGE 1
    public function page2() {

        $html = "<div id=\"contain\">
                    <h3>Welcome to ARENA REGISTRATION</h3>
                    <h5>Domain Expert - Personal Information</h5>
                    <form id=\"arena-registration\" method=\"GET\" action=\"#\" data-url=\"<?php echo admin_url('admin-ajax.php'); ?>\">
                        
                        <div class=\"row row-space\">
                        <div class=\"col-2\">
                                <div class=\"input-group\">
                            <label class=\"label\">Title</label>
                            <div class=\"rs-select2 js-select-simple select--no-search\">
                                <select id=\"title\" required>
                                    //disabling to make <i>required</i> work
                <!--                <option disabled=\"disabled\" selected=\"selected\">Choose Option</option>-->
                                    <option>Lawyer</option>
                                    <option>Social Worker</option>
                                    <option>Youth Social Worker</option>
                                    <option>Researcher </option>
                                    <option>Prosecutor</option>
                                    <option>Expert on Youth Gangs and Organised Crime</option>
                                    <option>Former Member of Radicalised Group</option>
                                    <option>Psychologist</option>
                                    <option>Hate Speech Specialist </option>
                                    <option>Social Media Specialist </option>
                                    <option>Radicalisation Expert </option>
                                    <option>Teacher</option>
                                    <option>Educator</option>
                                    <option>Trainer</option>
                                </select>
                                <div class=\"select-dropdown\"></div>
                            </div>
                        </div>
                            </div>
                            <div class=\"col-2\">
                                <div class=\"input-group\">
                                    <label class=\"label\">Country</label>
                                    <div class=\"input-group-icon\">
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
                                         </select>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                        
                        
                        <div class=\"row row-space\">
                            <div class=\"col-2\">
                                <div class=\"input-group\">
                                    <label class=\"label\">First Name</label>
                                    <input type=\"text\" id=\"first_name\" required>
                                </div>
                            </div>
                            <div class=\"col-2\">
                                <div class=\"input-group\">
                                    <label class=\"label\">Last Name</label>
                                    <input class=\"field-msg\" type=\"text\" id=\"last_name\" required>
                                </div>
                            </div>
                        </div>
                        
                        
                        
                        <div class=\"row row-space\">
                            <div class=\"col-2\">
                                <div class=\"input-group\">
                                    <label class=\"label\">Email</label>
                                    <input type=\"email\" id=\"email\" required>
                                </div>
                            </div>
                            <div class=\"col-2\">
                                <div class=\"input-group\">
                                    <label class=\"label\">Password</label>
                                    <input type=\"password\" id=\"password\" minlength=\"4\" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class=\"p-t-15\">
                            <button id=\"next_register\" type=\"submit\">NEXT</button>
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
                        <div><input name='skills' value='juvenile_lawyer' type=\"checkbox\" class=\"checkbox\" > <label> Juvenile Lawyer </label></div> 
                        <div><input name='skills' value='prosecutor' type=\"checkbox\" class=\"checkbox\" > <label> Prosecutor </label></div>
                        <div><input name='skills' value='psychologist' type=\"checkbox\" class=\"checkbox\" > <label> Psychologist  </label></div>
                        <div><input name='skills' value='social_media' type=\"checkbox\" class=\"checkbox\" > <label> Social Media </label></div>
                        <div><input name='skills' value='radicalization ' type=\"checkbox\" class=\"checkbox\" > <label> Radicalisation </label></div>
                        <div><input name='skills' value='youngpeople' type=\"checkbox\" class=\"checkbox\" > <label> Young People </label></div>
                        <div><input name='skills' value='school' type=\"checkbox\" class=\"checkbox\" > <label> School </label></div>
                        <div><input name='skills' value='hatespeech' type=\"checkbox\" class=\"checkbox\" > <label> Hate Speech  </label></div>
                        <div><input name='skills' value='deradicalisation' type=\"checkbox\" class=\"checkbox\" > <label> Deradicalisation  </label></div>
                        <div><input name='skills' value='organisedcrime' type=\"checkbox\" class=\"checkbox\" > <label> Organised Crime </label></div>
                        <div><input name='skills' value='youthgang' type=\"checkbox\" class=\"checkbox\" > <label> Youth Gang  </label></div>
                        <div><input name='skills' value='Skill12' type=\"checkbox\" class=\"checkbox\" > <label> Other </label></div>
                        <div><input style='width: 500px' type='text'></div>
                        
                   
                        <div class=\"p-t-15\">
                            <button id=\"next1_register\" type=\"submit\">NEXT</button>
                        </div>
                        <input type=\"hidden\" name=\"action\" value=\"getData\">
                    </form>
                </div>";
        echo $html;

    }

    // REGISTRATION PAGE 3
    public function page2_2() {

//        $skill = json_decode(stripslashes($_GET['skill']));
//            foreach ($skill as $s){
//            $s = $skill;
//            echo $s;
//        }
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
                            <button id=\"registered\" type=\"submit\">REGISTER</button>
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

        global $wpdb;

        $user_data = $wpdb->get_results( "SELECT email, password FROM {$wpdb->prefix}arena", OBJECT );



        $naam = sanitize_text_field( $_GET['naam'] );
        $paas= sanitize_text_field( $_GET['paas'] );

        for ($j=0; $j<count($user_data); $j++) {
            if ($naam === $user_data[$j]->email && $paas === $user_data[$j]->password) {

                echo "<div class='userPanel'>";
                echo "<br> USER: ";
                echo "<div class='userEmail'>";
                echo $user_data[$j] -> email;
                echo "</div>";
                echo "</div>";
                echo "<br>";
                echo "<div id='alertPanel'>";

                $alert = $wpdb->get_results( "SELECT event_category, event_description FROM {$wpdb->prefix}tra_reports", OBJECT );
                $arena = $wpdb->get_results( "SELECT skill FROM {$wpdb->prefix}arena WHERE email = '{$user_data[$j] -> email}'", OBJECT );

                $userSkills = explode(',', $arena[0] -> skill);



                for ($j=0; $j<count($alert); $j++) {
                    //echo "<br>";
                    $uSkills = explode(',', $alert[$j] -> event_category);

                    for ($k=0; $k<count($uSkills); $k++) {
                        //echo "<br>";
                        //echo "uSKills: ";
                        //echo $uSkills[$k];
                        //echo "<br>";

                        for ($i=0; $i<count($userSkills); $i++) {
                            //echo "<br>";
                            //echo "User Skills: ";
                            //echo $userSkills[$i];
                            //echo "<br>";

                            if ($userSkills[$i] === $uSkills[$k]){

//                                echo "<br>";
//                                echo "ALERT ";
//                                echo $j+1;
//                                echo "<br>";
                                echo "<div value='$j' id='toAlert' class='alertPost'>";
                                echo $alert[$j] -> event_description;
                                echo "</div>";
//                                if (not joined)
                                    echo "<button style='margin-left: 10px;' id='tojoin' class='tojoin'>JOIN</button>";
//                                else if(joined)
//                                    echo "<button id='tojoin'>IN PROGRESS</button>";
//                                else if(concluded)
//                                    echo "<button id='tojoin'>CONCLUDED</button>";
                                echo "<br>";
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

    // LOGGED IN PAGE 2
    public function page5() {

        global $wpdb;
        $arena = $wpdb->get_results( "SELECT first_name FROM {$wpdb->prefix}arena WHERE associatedAlert=''", OBJECT );

        echo "</br>";
        echo "<div class='participants' id='participants'>";
        for ($i=0; $i<count($arena);$i++){
//            echo $arena[$i] -> first_name;
        }
        echo "</div>";

        $alertID = sanitize_text_field( $_GET['alertID'] );


        $alert = $wpdb->get_results( "SELECT event_category, event_description FROM {$wpdb->prefix}tra_reports", OBJECT );
        echo "<div id='alertPanel'>";
        echo "<h3>";
//        echo $alertID;
        echo $alert[$alertID] -> event_description;
        echo "</h3>";

        echo "</br>";
        echo "<div class='display_comment' id='display_comment'>";
        echo $this->display_comment();
        echo "</div>";
        echo "</br>";
        echo "<input class='get_comment' type='text' id='get_comment' placeholder='Add Comment'>";
        echo "<button class='submit_comment' id='submit_comment' >Add Comment</button>";
        echo "</br></br>";
        echo "<button id='alertBack'>Back</button>";
        echo "</br></br>";
        echo "<div>";
        echo "<input type='text' class='invite'>";
        echo "<button>Invite Expert</button>";

    }

    // ADD COMMENTS
    public function page6() {
        global $wpdb;
        $comment_data = sanitize_text_field( $_GET['comment_data'] );
        $user_data = sanitize_text_field( $_GET['mail'] );
//        $comment_idd = sanitize_text_field( $_GET['comment_idd'] );
      //  $alertID = $wpdb->get_results( "SELECT report_id FROM {$wpdb->prefix}tra_reports", OBJECT );
        $userID = $wpdb->get_results( "SELECT first_name FROM {$wpdb->prefix}arena WHERE email='{$user_data}'", OBJECT );

        $data = array( 'comment_data' => $comment_data, 'comment_name' => $userID[0] -> first_name );
        $commentsData = $wpdb->prefix . 'commentsData';
        $wpdb->insert($commentsData, $data);


        $results = $wpdb->get_results( "SELECT comment_data, comment_name FROM {$wpdb->prefix}commentsData", OBJECT );
        //Comment filtering logic will go here
        for ($i=0; $i<count($results); $i++) {
            echo "<b>".$results[$i] -> comment_name.": </b>";
            echo $results[$i] -> comment_data;
            echo "</br>";
        }
        wp_die();
    }

    public function display_comment() {

        global $wpdb;

        $results = $wpdb->get_results( "SELECT comment_data, comment_name FROM {$wpdb->prefix}commentsData", OBJECT );
        //Comment filtering logic will go here
        for ($i=0; $i<count($results); $i++) {
            echo "<b>".$results[$i] -> comment_name.": </b>";
            echo $results[$i] -> comment_data;
            echo "</br>";
        }
    }

    // Stores Domain Expert to Database
    public function registerData($data){


        $clicked = sanitize_text_field( $_GET['id'] );
        global $wpdb;
        $tra_reports_db_name = $wpdb->prefix . 'arena';

        if ($clicked === 'register')
            $_SESSION["champions"] = $data ;
        else if ($clicked === 'register1')
            $_SESSION["champions1"] = $data ;
        else if ($clicked === 'registered') {
            $_SESSION["champions2"] = $data ;
            $fn = $_SESSION["champions"] + $_SESSION["champions1"] + $_SESSION["champions2"];
            $wpdb->insert($tra_reports_db_name, $fn);
        }
        else
            echo "errrr";

    }


    public function page() {
        global $wpdb;

        $userEmail = sanitize_text_field( $_GET['em'] );

        $alert = $wpdb->get_results( "SELECT event_category, event_description FROM {$wpdb->prefix}tra_reports", OBJECT );
        $arena = $wpdb->get_results( "SELECT skill FROM {$wpdb->prefix}arena WHERE email = '{$userEmail}'", OBJECT );
        $userSkills = explode(',', $arena[0] -> skill);
        for ($j=0; $j<count($alert); $j++) {
            $uSkills = explode(',', $alert[$j] -> event_category);
            for ($k=0; $k<count($uSkills); $k++) {
                for ($i=0; $i<count($userSkills); $i++) {
                    if ($userSkills[$i] === $uSkills[$k]){
//                        echo "<br>";
//                        echo "ALERT ";
//                        echo $j+1;
//                        echo "<br>";
                        echo "<div value='$j' id='toAlert' class='alertPost'>";
                        echo $alert[$j] -> event_description;
                        echo "</div>";
                        echo "<br>";
                        $k = 999;
                    }
                }
            }
        }
        wp_die();

    }

    public function update_data() {
        echo "<p>inside</p>";
    }

}