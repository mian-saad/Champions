
<?php

        $plugin_path = plugin_dir_path( dirname(__FILE__, 1));
        // English
        $english_strings = json_decode(file_get_contents( $plugin_path."assets/base/en/alert_strings.json"), true);
        $english_intro = $english_strings['intro'];
        $english_bullet1 = $english_strings['bullet1'];
        $english_bullet2 = $english_strings['bullet2'];
        $english_bullet3 = $english_strings['bullet3'];

        // German
        $german_strings = json_decode(file_get_contents( $plugin_path."assets/base/ge/alert_strings.json"), true);
        $german_intro = $german_strings['intro'];

        // Hungarian
        $hungarian_strings = json_decode(file_get_contents( $plugin_path."assets/base/hun/alert_strings.json"), true);
        $hungarian_intro = $hungarian_strings['intro'];

        // Polish
        $polish_strings = json_decode(file_get_contents( $plugin_path."assets/base/pol/alert_strings.json"), true);
        $polish_intro = $polish_strings['intro'];

        // Romanian
        $romanian_strings = json_decode(file_get_contents( $plugin_path."assets/base/ro/alert_strings.json"), true);
        $romanian_intro = $romanian_strings['intro'];

        echo "
        <div id='alert_questionnaire_content_div'>
        <form>
            <select name='lang' id='lang_select'>
                <option value='en'>🇬🇧 English</option>
                <option value='ge'>🇩🇪 German</option>
                <option value='hun'>🇭🇺 Hungarian</option>
                <option value='pol'>🇵🇱 Polish</option>
                <option value='ro'>🇷🇴 Romanian</option>
            </select>
        </form><br>

        
        <div id='en' class='lan' style='display: inline; text-align: justify;'>
            <h1> Welcome to ALERT Module</h1>
            <p>".$english_intro."</p>
            <li>".$english_bullet1."</li>
            <li>".$english_bullet2."</li>
            <li>".$english_bullet3."</li>
            <br>
            
            <div id='alert_button_pane'><a class='button' type='submit' id='alert_new_report' href='#' onclick='return false;'>New Alert</a></div>
        </div >
       
        <div id='ge' class='lan' style='display: none; text-align: justify;'>
            <h1> Willkommen bei ALERT-Modul</h1>
            <p>".$german_intro."</p>
            
            <div id='alert_button_pane'><a class='button' type='submit' id='alert_new_report' href='#' onclick='return false;'>Neuer Alert</a></div>
        </div >

        <div id='hun' class='lan' style='display: none; text-align: justify;'>
            <h1> Üdvözöljük az ALERT modulban</h1>
            <p>".$hungarian_intro."</p>
            
            <div id='alert_button_pane'><a class='button' type='submit' id='alert_new_report' href='#' onclick='return false;'>Új figyelmeztetés</a></div>
        </div >
        
        <div id='pol' class='lan' style='display: none; text-align: justify;'>
            <h1> Witamy w module ALERT</h1>
            <p>".$polish_intro."</p>
            
            <div id='alert_button_pane'><a class='button' type='submit' id='alert_new_report' href='#' onclick='return false;'>Nowy alert</a></div>
        </div >
        
        <div id='ro' class='lan' style='display: none; text-align: justify;'>
            <h1> Bine ați venit la modulul ALERT</h1>
            <p>".$romanian_intro."</p>
            
            <div id='alert_button_pane'><a class='button' type='submit' id='alert_new_report' href='#' onclick='return false;'>Alertă nouă</a></div>
        </div >
        
        </div>
        ";

        echo "
            <script src='https://code.jquery.com/jquery-3.5.1.min.js'></script>
            <script src='".$this->plugin_url."assets/js/jquery.datetimepicker.full.js'></script>
            <script>
            
                $(document).ready(function(){
                    $('#lang_select').on('change', function() {
                        $('.lan').hide();
                        $('#'+ $(this).val()).show();
                    });
                });
          
             </script>
        ";

