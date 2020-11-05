<?php

namespace Contain\Display\View;

class EditProfile {

    public function render($email) {
        global $wpdb;
        $arenaData = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}arena WHERE flp_email= '$email'", OBJECT );

        echo $this->body($arenaData[0]);
    }

    public function cond ($arenaData) {
        if ($arenaData->flp_skills === 'Radicalisation') {echo 'checked';}
    }

    public function body($arenaData) {

        if ( strpos($arenaData->flp_skills, 'uvenile Lawyer')) {
            $Juvenile = 'checked';
        }
        if (strpos($arenaData->flp_skills, 'rosecutor')) {
            $Prosecutor = 'checked';
        }
        if (strpos($arenaData->flp_skills, 'sychologist')) {
            $Psychologist = 'checked';
        }
        if (strpos($arenaData->flp_skills, 'ocial Media')) {
            $Social = 'checked';
        }
        if (strpos($arenaData->flp_skills, 'adicalisation')) {
            $radicalisation = 'checked';
        }
        if (strpos($arenaData->flp_skills, 'oung People')) {
            $Young = 'checked';
        }
        if (strpos($arenaData->flp_skills, 'chool')) {
            $School = 'checked';
        }
        if (strpos($arenaData->flp_skills, 'ate Speech')) {
            $Hate = 'checked';
        }
        if (strpos($arenaData->flp_skills, 'eradicalisation')) {
            $Deradicalisation = 'checked';
        }
        if (strpos($arenaData->flp_skills, 'rganised Crime')) {
            $Organised = 'checked';
        }
        if (strpos($arenaData->flp_skills, 'outh Gang')) {
            $Youth = 'checked';
        }
        if (strpos($arenaData->flp_skills, 'Other')) {
            $Other = 'checked';
        }
        $html = "<form id=\"arena_question_form\">
                   <div class=\"row\">
                      <div class=\"col-12\">
                         <p class=\"register_question\">Please Select your Profession</p>
                         <div class=\"register_select_answers \">
                            <select id=\"title\" name=\"flp_title\">
                               <option class=\"register_quiz_select\" name=\"flp_title\" id=\"$arenaData->flp_title\" value=\"$arenaData->flp_title\">$arenaData->flp_title</option>
                               <option class=\"register_quiz_select\" name=\"flp_title\" id=\"Bus/Tram_Driver0\" value=\"Bus/Tram Driver\">Bus/Tram Driver</option>
                               <option class=\"register_quiz_select\" name=\"flp_title\" id=\"Counsellor1\" value=\"Counsellor\">Counsellor</option>
                               <option class=\"register_quiz_select\" name=\"flp_title\" id=\"Diversity_Officer2\" value=\"Diversity Officer\">Diversity Officer</option>
                               <option class=\"register_quiz_select\" name=\"flp_title\" id=\"Employee_of_a_Culture_Centre3\" value=\"Employee of a Culture Centre\">Employee of a Culture Centre</option>
                               <option class=\"register_quiz_select\" name=\"flp_title\" id=\"Employee_of_Women_Shelter4\" value=\"Employee of Women Shelter\">Employee of Women Shelter</option>
                               <option class=\"register_quiz_select\" name=\"flp_title\" id=\"Football_Coach5\" value=\"Football Coach\">Football Coach</option>
                               <option class=\"register_quiz_select\" name=\"flp_title\" id=\"Football_Fan6\" value=\"Football Fan\">Football Fan</option>
                               <option class=\"register_quiz_select\" name=\"flp_title\" id=\"Football_Referee7\" value=\"Football Referee\">Football Referee</option>
                               <option class=\"register_quiz_select\" name=\"flp_title\" id=\"Forest_Worker8\" value=\"Forest Worker\">Forest Worker</option>
                               <option class=\"register_quiz_select\" name=\"flp_title\" id=\"International_School_Teacher9\" value=\"International School Teacher\">International School Teacher</option>
                               <option class=\"register_quiz_select\" name=\"flp_title\" id=\"International_Volunteer10\" value=\"International Volunteer\">International Volunteer</option>
                               <option class=\"register_quiz_select\" name=\"flp_title\" id=\"Municipal_Employee11\" value=\"Municipal Employee\">Municipal Employee</option>
                               <option class=\"register_quiz_select\" name=\"flp_title\" id=\"NGO_Worker12\" value=\"NGO Worker\">NGO Worker</option>
                               <option class=\"register_quiz_select\" name=\"flp_title\" id=\"Police_Officer13\" value=\"Police Officer\">Police Officer</option>
                               <option class=\"register_quiz_select\" name=\"flp_title\" id=\"Primary_School_Teacher14\" value=\"Primary School Teacher\">Primary School Teacher</option>
                               <option class=\"register_quiz_select\" name=\"flp_title\" id=\"Probation_Officer15\" value=\"Probation Officer\">Probation Officer</option>
                               <option class=\"register_quiz_select\" name=\"flp_title\" id=\"Psychotherapist16\" value=\"Psychotherapist\">Psychotherapist</option>
                               <option class=\"register_quiz_select\" name=\"flp_title\" id=\"Secondary_School_Teacher17\" value=\"Secondary School Teacher\">Secondary School Teacher</option>
                               <option class=\"register_quiz_select\" name=\"flp_title\" id=\"Social_Worker18\" value=\"Social Worker\">Social Worker</option>
                               <option class=\"register_quiz_select\" name=\"flp_title\" id=\"Student_Volunteer19\" value=\"Student Volunteer\">Student Volunteer</option>
                               <option class=\"register_quiz_select\" name=\"flp_title\" id=\"Teacher20\" value=\"Teacher\">Teacher</option>
                               <option class=\"register_quiz_select\" name=\"flp_title\" id=\"University_Lecturer21\" value=\"University Lecturer\">University Lecturer</option>
                               <option class=\"register_quiz_select\" name=\"flp_title\" id=\"Volunteer22\" value=\"Volunteer\">Volunteer</option>
                               <option class=\"register_quiz_select\" name=\"flp_title\" id=\"Youth_Prison_Ward23\" value=\"Youth Prison Ward\">Youth Prison Ward</option>
                               <option class=\"register_quiz_select\" name=\"flp_title\" id=\"Youth_Worker24\" value=\"Youth Worker\">Youth Worker</option>
                            </select>
                         </div>
                      </div>
                      <div class=\"col-12\">
                         <p class=\"register_question\">Please Select your Country</p>
                         <div class=\"register_select_answers \">
                            <select id=\"title\" name=\"flp_country\">
                               <option class=\"register_quiz_select\" name=\"flp_country\" id=\"$arenaData->flp_country\" value=\"$arenaData->flp_country\">$arenaData->flp_country</option>
                               <option class=\"register_quiz_select\" name=\"flp_country\" id=\"Austria0\" value=\"Austria\">Austria</option>
                               <option class=\"register_quiz_select\" name=\"flp_country\" id=\"Germany1\" value=\"Germany\">Germany</option>
                               <option class=\"register_quiz_select\" name=\"flp_country\" id=\"Hungary2\" value=\"Hungary\">Hungary</option>
                               <option class=\"register_quiz_select\" name=\"flp_country\" id=\"Italy3\" value=\"Italy\">Italy</option>
                               <option class=\"register_quiz_select\" name=\"flp_country\" id=\"Poland4\" value=\"Poland\">Poland</option>
                               <option class=\"register_quiz_select\" name=\"flp_country\" id=\"Romania5\" value=\"Romania\">Romania</option>
                            </select>
                         </div>
                      </div>
                      <div class=\"col-6\">
                         <p class=\"register_question\">Please provide your First Name</p>
                         <input type=\"text\" name=\"flp_first_name\" value=\"$arenaData->flp_first_name\"><br>
                      </div>
                      <div class=\"col-6\">
                         <p class=\"register_question\">Please provide your Last Name</p>
                         <input type=\"text\" name=\"flp_last_name\" value=\"$arenaData->flp_last_name\"><br>
                      </div>
                      <div class=\"col-6\">
                         <p class=\"register_question\">Please provide your Email</p>
                         <input disabled type=\"text\" name=\"flp_email\" value=\"$arenaData->flp_email\"><br>
                      </div>
                      <div class=\"col-6\">
                         <p class=\"register_question\">Please provide your Password</p>
                         <input type=\"password\" name=\"flp_password\" value=\"$arenaData->flp_first_name\"><br>
                      </div>
                      <div class=\"col-6\">
                         <p class=\"register_question\">Please provide your organisation</p>
                         <input type=\"text\" name=\"flp_organisation\" value=\"$arenaData->flp_organisation\"><br>
                      </div>
                      <div class=\"col-6\">
                         <p class=\"register_question\">How many years of experience do you have in this profession?</p>
                         <input type=\"text\" name=\"flp_years_of_experience\" value=\"$arenaData->flp_years_of_experience\"><br>
                      </div>
                      <div class=\"col-6\">
                         <p class=\"register_question\">What is your current City/Town</p>
                         <input type=\"text\" name=\"flp_city\" value=\"$arenaData->flp_city\"><br>
                      </div>
                   </div>
                 <br>";

        $html .= "<section class='padd' >
                    <p class='register_question'>Please Select your Skills</p>
                    <div class='register_checkbox_answers'>
                      <div class='register_horizontal_choice'><input ".$Juvenile." type='checkbox' class='register_checkbox' name='flp_skills' id='Juvenile Lawyer' value='Juvenile Lawyer' ><label for='Juvenile Lawyer'>Juvenile Lawyer</label></div>
                      <div class=\"register_horizontal_choice\"><input ".$Prosecutor." type=\"checkbox\" class=\"register_checkbox\" name=\"flp_skills\" id=\"Prosecutor\" value=\"Prosecutor\" required=\"\"><label for=\"Prosecutor\">Prosecutor</label></div>
                      <div class=\"register_horizontal_choice\"><input ".$Psychologist." type=\"checkbox\" class=\"register_checkbox\" name=\"flp_skills\" id=\"Psychologist\" value=\"Psychologist\" required=\"\"><label for=\"Psychologist\">Psychologist</label></div>
                      <div class=\"register_horizontal_choice\"><input ".$Social." type=\"checkbox\" class=\"register_checkbox\" name=\"flp_skills\" id=\"Social Media\" value=\"Social Media\" required=\"\"><label for=\"Social Media\">Social Media</label></div>
                      <div class='register_horizontal_choice'><input ".$radicalisation ." type='checkbox' class='register_checkbox' name='flp_skills' id='Radicalisation' value='Radicalisation' ><label for='Radicalisation'>Radicalisation</label></div>
                      <div class=\"register_horizontal_choice\"><input ".$Young." type=\"checkbox\" class=\"register_checkbox\" name=\"flp_skills\" id=\"Young People\" value=\"Young People\" required=\"\"><label for=\"Young People\">Young People</label></div>
                      <div class=\"register_horizontal_choice\"><input ".$School." type=\"checkbox\" class=\"register_checkbox\" name=\"flp_skills\" id=\"School\" value=\"School\" required=\"\"><label for=\"School\">School</label></div>
                      <div class=\"register_horizontal_choice\"><input ".$Hate." type=\"checkbox\" class=\"register_checkbox\" name=\"flp_skills\" id=\"Hate Speech\" value=\"Hate Speech\" required=\"\"><label for=\"Hate Speech\">Hate Speech</label></div>
                      <div class=\"register_horizontal_choice\"><input ".$Deradicalisation." type=\"checkbox\" class=\"register_checkbox\" name=\"flp_skills\" id=\"Deradicalisation\" value=\"Deradicalisation\" required=\"\"><label for=\"Deradicalisation\">Deradicalisation</label></div>
                      <div class=\"register_horizontal_choice\"><input ".$Organised." type=\"checkbox\" class=\"register_checkbox\" name=\"flp_skills\" id=\"Organised Crime\" value=\"Organised Crime\" required=\"\"><label for=\"Organised Crime\">Organised Crime</label></div>
                      <div class=\"register_horizontal_choice\"><input ".$Youth." type=\"checkbox\" class=\"register_checkbox\" name=\"flp_skills\" id=\"Youth Gang\" value=\"Youth Gang\" required=\"\"><label for=\"Youth Gang\">Youth Gang</label></div>
                      <div class=\"register_horizontal_choice\"><input ".$Other." type=\"checkbox\" class=\"register_checkbox\" name=\"flp_skills\" id=\"Other\" value=\"Other\" required=\"\"><label for=\"Other\">Other</label> <input class=\"col-8 input-margin\" type=\"text\" name=\"other_text_input\" value=\"\"></div>
                    
                    </div>
                  </section><br>";

        $html .= "<section class='padd' >
                    <p class=\"register_question\">Please provide a description of your experience and expertise</p>
                    <textarea id=\"register_text_big\" name=\"flp_description\" rows=\"10\">$arenaData->flp_description</textarea>
                  </section></form><br>";

        $html .= "<div id=\"register_button_pane\" class='padd' >
                    <a class=\"button\" id=\"BackDiscussion\" href=\"#\" onclick=\"return false;\">Back</a> 
                    <a class=\"button\" id=\"update\" href=\"#\" onclick=\"return false;\">Update</a>
                    <p id='notify'></p>
                  </div>";

        return $html;
    }

}