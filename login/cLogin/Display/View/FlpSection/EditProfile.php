<?php

namespace Contain\Display\View\FlpSection;

class EditProfile {

    public $language;

    public function __construct($language, $email) {
        $this->lang = $language;
        $this->email = $email;
        $this->skill_path = plugin_dir_path( dirname(__FILE__, 4));
        $this->profile = json_decode(file_get_contents($this->skill_path . "assets/base/" . $_SESSION['language'] . "/profile.json"), true);
    }

    public function render() {
        global $wpdb;
        $arenaData = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}arena WHERE flp_email= '$this->email'", OBJECT );
        echo $this->body($arenaData[0]);

    }

    public function body($Data) {


        $html = "<h3>".$this->lang['edit_profile']."</h3>
                <form id='arena_question_form'>
                   <!-- Title Checkboxes -->
                   <div class='row'>
                      <div class='col-6'>
                         <p class='register_question question'>".$this->lang['select_profession']."</p>
                         <div class='register_select_answers'>
                            ".$this->title()."
                         </div>
                      </div>
                   </div><br>
                   <!-- Country Selection -->
                   <div class='row'>
                      <div class='col-8'>
                         <p class='register_question question'>".$this->lang['select_country']."</p>
                         <div class='register_select_answers'>
                            <select name='flp_country'>
                            ".$this->country()."
                            </select>
                         </div>
                      </div>
                   </div><br>
                   <!-- Text box Inputs -->
                   ".$this->inputs()."
                   <!-- Skills checkboxes -->
                   <div class='row'>
                     <div class='col-6'>
                     <p class='register_question question'>".$this->lang['select_skills']."</p>
                     ".$this->skills()."
                     </div>
                   </div><br>";

        $html .= "<section class='padd_sync' >
                    <!-- Description -->
                    <p class='register_question question'>".$this->lang['provide_description']."</p>
                    <textarea id='register_text_big' name='flp_description' rows='10'>$Data->flp_description</textarea>
                  </section></form><br>";

        $html .= "<div id='register_button_pane' class='padd_sync' >
                    <!-- Buttons -->
                    <a class='button' id='BackDiscussion' href='#' onclick='return false;'>".$this->lang['back']."</a> 
                    <a class='button' id='update' href='#' onclick='return false;'>".$this->lang['update']."</a>
                    <p id='notify'></p>
                  </div>";

        return $html;
    }

    public function title() {

        global $wpdb;
        $title = $wpdb->get_row( "SELECT flp_title FROM {$wpdb->prefix}arena WHERE flp_email= '$this->email'", OBJECT ,0);
        $db_title = explode(',', $title->flp_title);
        $html = "";
        foreach ($this->profile['title'] as $title) {
            if (in_array($title['id'], $db_title)){
                $html .= "<input checked type='checkbox' class='register_quiz_select' name='flp_title' id='".$title['text']."' value='".$title['text']."'>".$title['text']."</input>";
                if ($title['text']===$this->lang['other']) {
                    $other = end($db_title);
                    $html .= "<br><input name='other_title' type='text' value='$other' />";
                }
            }
            else {
                $html .= "<input type='checkbox' class='register_quiz_select' name='flp_title' id='".$title['text']."' value='".$title['text']."'>".$title['text']."</input>";
                if ($title['text']===$this->lang['other']) {
                    $html .= "<br><input name='other_title' type='text' />";
                }
            }
            $html .= "<br>";
        }
        return $html;
    }

    public function country() {

        $html = "";
        global $wpdb;
        $db_country = $wpdb->get_row( "SELECT flp_country FROM {$wpdb->prefix}arena WHERE flp_email= '$this->email'", OBJECT ,0);

        foreach ($this->profile['country'] as $country) {
            if ($db_country->flp_country === $country['id']) {
                $html .= "<option selected class='register_quiz_select' name='flp_country' id='$db_country->flp_country' value='".$country['text']."'>".$country['text']."</option>";
            }
            else {
                $html .= "<option class='register_quiz_select' name='flp_country' id='$db_country->flp_country' value='".$country['text']."'>".$country['text']."</option>";
            }
        }
        return $html;
    }
    
    public function inputs() {

        $row_counter = 0;
        $html = "";
        global $wpdb;
        $db_inputs = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}arena WHERE flp_email= '$this->email'", ARRAY_A);

        foreach ($this->profile['inputs'] as $input) {
            if (array_key_exists($input['id'], $db_inputs[0])) {
                if ($row_counter % 2 === 0) {
                    $html .= "<div class='row'>";
                }
                $html .= "<div class='col - 6'>";
                $html .= "<p class='register_question question'>".$input['text']."</p>";
                if ($input['id'] === 'flp_email') {
                    $html .= "<input disabled type='text' name='".$input['id']."' value='".$db_inputs[0][$input['id']]."' />";
                }
                else {
                    $html .= "<input type='text' name='".$input['id']."' value='".$db_inputs[0][$input['id']]."' />";
                }
                $html .= "</div>";

                if ($row_counter % 2 !== 0) {
                    $html .= "</div>";
                }
                $row_counter++;
            }
        }
        $html .= "</div><br>";
        return $html;
    }

    public function skills() {

        global $wpdb;
        $skills = $wpdb->get_row( "SELECT flp_skills FROM {$wpdb->prefix}arena WHERE flp_email= '$this->email'", OBJECT ,0);
        $db_skills = explode(',', $skills->flp_skills);
        $html = "";
        foreach ($this->profile['skills'] as $skill) {
            if (in_array($skill['id'], $db_skills)){
                $html .= "<input checked type='checkbox' class='register_quiz_select' name='flp_skills' id='' value='".$skill['text']."'>".$skill['text']."</input>";
                if ($skill['text']===$this->lang['other']) {
                    $other = end($db_skills);
                    $html .= "<br><input type='text' name='other_skills' value='$other' />";
                }
            }
            else {
                $html .= "<input type='checkbox' class='register_quiz_select' name='flp_skills' id='' value='".$skill['text']."'>".$skill['text']."</input>";
                if ($skill['text']===$this->lang['other']) {
                    $html .= "<input type='text' name='other_skills' />";
                }
            }
            $html .= "<br>";
        }
        return $html;
    }

}