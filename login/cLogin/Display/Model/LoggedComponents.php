<?php

namespace Contain\Display\Model;

class LoggedComponents {

    public function login() {
        $html = "
                <h2>Welcome to ARENA Module</h2><br>
                <form id=\"arena-login\" method=\"POST\" action=\"#\" >
                    <div class='row'>
                        <div class='col-3'></div>
                        <div class='col-6'>
                            <label>Email</label>
                            <input id=\"naam\" type=\"email\" name=\"email\"><br>
                                  
                            <label>Password</label>
                            <input id=\"paas\" type=\"password\" name=\"password\" minlength=\"4\">
                              
                            <button class='button login-button' id=\"login\" type=\"submit\" onclick=\"return false;\">Login</button>
                            <input type=\"hidden\" name=\"action\" value=\"getData\">
                        </div>
                        <div class='col-3'></div>
                    </div>
                </form>";
        echo $html;
    }

    public function header($FirstName, $LastName) {
        $html = "<div class='row'>";
            $html .= "<div class='col-9'></div>";
            $html .= "<div class=' col-3'>";
                $html .= "Logged in as <b class='userEmail'>".$FirstName." ".$LastName."</b>";
            $html .= "</div>";
        $html .= "</div>";

        echo $html;
    }

}