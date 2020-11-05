<?php

namespace Contain\Display\View;

class MainPage {
    public function render($string_file) {
//        $plugin_path = plugin_dir_path( dirname(__FILE__, 3));
//        $string_file = json_decode(file_get_contents($plugin_path . "assets/base/" . $lang . "/alert_strings.json"), true);

        $html = "<div class=\"card-body\" id=\"contentBox\">
                    <h1>".$string_file['arena_login_module']."</h1>
                    <div class='row'>
                        <div class='col-2'></div>
                        <div class='col-8'>
                            <form id='arena-login' method='POST' action='#' >
                                <label>".$string_file['email']."</label>
                                <input id='naam' type='email' name='email'><br>
                
                                <label>".$string_file['password']."</label>
                                <input id='paas' type='password' name='password' minlength='4'>
                
                                <div class='login-button'>
                                    <a class='button' id='Login'  >".$string_file['login_flp']."</a>
                                    <a class='button' id='backtolanguage' >".$string_file['back']."</a>
                                </div>
                                
                
                            </form>
                <!--                <input type='hidden' name='action' value='getData'>-->
                        </div>
                        <div class='col-2'></div>
                    </div>
                
                    <div class=\"row\">
                        <div class='col-2'></div>
                        <div class=\"col-8\">
                            <div id='login_button_pane'>
                                <a id=\"forgot\" href=\"#\" onclick=\"return false;\">".$string_file['forgot']."</a><br>
                               <!-- <a class='button' href='https://www.firstlinepractitioners.com/arena/' >Register as FLP Expert</a> -->
                                <a id=\"ToBackdoorLogin\" href='#' onclick='return false;'>".$string_file['login_moderator']."</a>
                            </div>
                        </div>
                        <div class='col-2'></div>
                    </div>
                </div>";
        echo $html;
    }

}