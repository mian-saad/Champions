
<?php

        $plugin_path = plugin_dir_path( dirname(__FILE__, 1));
        $string_file = json_decode(file_get_contents( $plugin_path."assets/base/en/alert_strings.json"), true);
        $intro = $string_file['intro'];
        $bullet1 = $string_file['bullet1'];
        $bullet2 = $string_file['bullet2'];
        $bullet3 = $string_file['bullet3'];

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

        
        <div id='en' class='lan' style='display: inline'>
            <h1> Welcome to ALERT Module</h1>
            <p>".$intro."</p>
            <li>".$bullet1."</li>
            <li>".$bullet2."</li>
            <li>".$bullet3."</li>
            <br>
            
            <div id='alert_button_pane'><a class='button' type='submit' id='alert_new_report' href='#' onclick='return false;'>New Alert</a></div>
        </div >
       
        <div id='ge' class='lan' style='display: none'>
            <h1> German Welcome to ALERT Module</h1>
            <p>".$intro."</p>
            <li>".$bullet1."</li>
            <li>".$bullet2."</li>
            <li>".$bullet3."</li>
            <br>
            <div id='alert_button_pane'><a class='button' type='submit' id='alert_new_report' href='#' onclick='return false;'>New Alert</a></div>
        </div >

        <div id='hun' class='lan' style='display: none'>
            <h1> Hungarian Welcome to ALERT Module</h1>
            <p>".$intro."</p>
            <li>".$bullet1."</li>
            <li>".$bullet2."</li>
            <li>".$bullet3."</li>
            <br>
            <div id='alert_button_pane'><a class='button' type='submit' id='alert_new_report' href='#' onclick='return false;'>New Alert</a></div>
        </div >
        
        <div id='pol' class='lan' style='display: none'>
            <h1> Polish Welcome to ALERT Module</h1>
            <p>".$intro."</p>
            <li>".$bullet1."</li>
            <li>".$bullet2."</li>
            <li>".$bullet3."</li>
            <br>
            <div id='alert_button_pane'><a class='button' type='submit' id='alert_new_report' href='#' onclick='return false;'>New Alert</a></div>
        </div >
        
        <div id='ro' class='lan' style='display: none'>
            <h1> Romanian Welcome to ALERT Module</h1>
            <p>".$intro."</p>
            <li>".$bullet1."</li>
            <li>".$bullet2."</li>
            <li>".$bullet3."</li>
            <br>
            <div id='alert_button_pane'><a class='button' type='submit' id='alert_new_report' href='#' onclick='return false;'>New Alert</a></div>
        </div >
        
        </div>
        ";

        echo "
            <script src=\"https://code.jquery.com/jquery-3.5.1.min.js\"></script>
            <script>
            
                $(document).ready(function(){
                    $('#lang_select').on('change', function() {
                        $('.lan').hide();
                        $('#'+ $(this).val()).show();
                    });
                });
          
             </script>
        ";

