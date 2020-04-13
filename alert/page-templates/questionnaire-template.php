<?php
/*
Template Name: Takedown Questionnaire Template
*/

echo "<div id='tra_questionnaire_div'>";
echo "<div id='tra_questionnaire_content_div'>";
// we could try defining the language of the questionary here and pass it via ajax or via php, probly via ajax is a better option
echo "<h1> Welcome to ALERT Module</h1>";
echo "<p>Chose your preferred language:</p>";
echo "<form>";
echo "<select name=\"lang\" id='lang_select'>";
echo "<option value=\"en\">English</option>";
echo "<option value=\"it\">Italian</option>";
echo "<option value=\"ge\">German</option>";
echo "<option value=\"spa\">Spanish</option>";
echo "<option value=\"ro\">Romanian</option>";
echo "<option value=\"no\">Norwegian</option>";
echo "<option value=\"pol\">Polish</option>";
echo "<option value=\"cz\">Czech</option>";
echo "<option value=\"sl\">Slovakian</option>";
echo "<option value=\"ne\">Netherlands</option>";
echo "<option value=\"is\">Hebrew</option>";
echo "<option value=\"fr\">French</option>";
echo "<option value=\"gr\">Greek</option>";
echo "<option value=\"bu\">Bulgarian</option>";
echo "<option value=\"por\">Portuguese</option>";
echo "</select>";
echo "</form>";
echo "<div id='tra_button_pane'><a class='button' type='submit' id='tra_new_report' href='#' onclick='return false;'>New Alert</a></div>";
echo "</div >";
echo "</div >";


?> 