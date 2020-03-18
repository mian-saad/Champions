<?php
/*
Template Name: Takedown Questionnaire Template
*/

echo "<div id='tra_questionnaire_div'>";
echo "<div id='tra_questionnaire_content_div'>";
// we could try defining the language of the question here and pass it via ajax or via php, probably via ajax is a better option
echo "<h3> Welcome to ALERT Module</h3>";
echo "<p>First Line Practitioner (FLP) Type: </p>";
echo "<form>";
echo "<select name=\"lang\" id='flp'>";
echo "<option value=\"Social Worker\">Social Worker</option>";
echo "<option value=\"Volunteer\">Volunteer</option>";
echo "<option value=\"Teacher\">Teacher</option>";
echo "<option value=\"Youth Worker\">Youth Worker</option>";
echo "<option value=\"Football Coach\">Football Coach</option>";
echo "<option value=\"Football Fan\">Football Fan</option>";
echo "<option value=\"Employee of Women Shelter\">Employee of Women Shelter</option>";
echo "<option value=\"Youth Prison Ward\">Youth Prison Ward</option>";
echo "<option value=\"Forest Worker\">Forest Worker</option>";
echo "<option value=\"Student Volunteer\">Student Volunteer</option>";
echo "<option value=\"International Volunteer\">International Volunteer</option>";
echo "<option value=\"Primary School Teacher\">Primary School Teacher</option>";
echo "<option value=\"Secondary School Teacher\">Secondary School Teacher</option>";
echo "<option value=\"International School Teacher\">International School Teacher</option>";
echo "<option value=\"Madrasah Teacher\">Madrasah Teacher</option>";
echo "<option value=\"University Lecturer\">University Lecturer</option>";
echo "<option value=\"Police Officer\">Police Officer</option>";
echo "<option value=\"Municipal Employee\">Municipal Employee</option>";
echo "<option value=\"Football Referee\">Football Referee</option>";
echo "<option value=\"Bus/Tram Driver\">Bus/Tram Driver</option>";
echo "<option value=\"Diversity Officer\">Diversity Officer</option>";
echo "<option value=\"Employee of a Culture Centre\">Employee of a Culture Centre</option>";
echo "</select>";
echo "</form>";
echo "<div id='tra_button_pane'><a class='button' id='tra_new_report' href='#' onclick='return false;'>Generate New Case</a></div>";
echo "</div >";
echo "</div >";


?> 