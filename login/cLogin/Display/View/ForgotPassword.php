<?php

namespace Contain\Display\View;

use Contain\Display\Controller\LoadData;
use Contain\Display\Model\LoggedComponents;

class ForgotPassword {

    public function Render($string_file) {
        $html = "<form id='arena-login' method='POST' action='#' >
                    <h3>Forgot Password?</h3>
                    <label>Please Enter the email address you used to register</label>
                    <input id='naam' type='email' name='email' placeholder='test@example.com'><br><br>
                    <a class='button login-button' id='forgot_password' >Forgot Password</a>
                    <a class='button login-button' id='backto' >".$string_file['back']."</a>
                </form>";

        echo $html;
    }

}