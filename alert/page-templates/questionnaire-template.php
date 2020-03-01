<?php
/*
Template Name: Takedown Questionnaire Template
*/

echo "<div id='tra_questionnaire_div'>";
echo "<div id='tra_questionnaire_content_div'>";
// we could try defining the language of the question here and pass it via ajax or via php, probably via ajax is a better option
echo "<h3> Welcome to ALERT Module</h3>";
echo "<p>Chose your preferred language:</p>";
echo "<form>";
echo "<select name=\"lang\" id='lang_select'>";
echo "<option value=\"en\">English</option>";
echo "</select>";
echo "</form>";
echo "<div id='tra_button_pane'><a class='button' id='tra_new_report' href='#' onclick='return false;'>Generate New Case</a></div>";
echo "</div >";
echo "</div >";


?> 