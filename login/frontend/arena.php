<?php

$plugin_path = plugin_dir_path( dirname(__FILE__, 1));
$string_file = json_decode(file_get_contents( $plugin_path."assets/base/en/alert_strings.json"), true);
$intro = $string_file['intro'];
$bullet1 = $string_file['bullet1'];
$bullet2 = $string_file['bullet2'];



echo "
        <div class=\"card-body\" id=\"contentBox\">
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
            <h1> Welcome to ARENA Module</h1>
            <p>".$intro."</p>
            <li>".$bullet1."</li>
            <li>".$bullet2."</li>
            <br>
            <div id='login_button_pane'>
                <a class=\"button\" id=\"start_arena\" >Login</a><br><br>
                <a class=\"button\" id='start_registration' >Registration</a>
            </div>            
        </div >
       
        <div id='ge' class='lan' style='display: none'>
            <h1> German Welcome to ARENA Module</h1>
            <p>".$intro."</p>
            <li>".$bullet1."</li>
            <li>".$bullet2."</li>
            <br>
            <a class=\"login_links\" id=\"start_arena\" >Login</a><br>
            <a class=\"login_links\" id='start_registration' >Registration</a>
        </div >

        <div id='hun' class='lan' style='display: none'>
            <h1> Hungarian Welcome to ARENA Module</h1>
            <p>".$intro."</p>
            <li>".$bullet1."</li>
            <li>".$bullet2."</li>
            <br>
            <a class=\"login_links\" id=\"start_arena\" >Login</a><br>
            <a class=\"login_links\" href='https://www.firstlinepractitioners.com/arena/' >Registration</a>
        </div >
        
        <div id='pol' class='lan' style='display: none'>
            <h1> Polish Welcome to ARENA Module</h1>
            <p>".$intro."</p>
            <li>".$bullet1."</li>
            <li>".$bullet2."</li>
            <br>
            <a class=\"login_links\" id=\"start_arena\" >Login</a><br>
            <a class=\"login_links\" href='https://www.firstlinepractitioners.com/arena/' >Registration</a>
        </div >
        
        <div id='ro' class='lan' style='display: none'>
            <h1> Romanian Welcome to ARENA Module</h1>
            <p>".$intro."</p>
            <li>".$bullet1."</li>
            <li>".$bullet2."</li>
            <br>
            <a class=\"login_links\" id=\"start_arena\" >Login</a><br>
            <a class=\"login_links\" href='https://www.firstlinepractitioners.com/arena/' >Registration</a>
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

