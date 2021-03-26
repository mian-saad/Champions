<?php

namespace Contain\Display\View;

use Contain\Display\Controller\LoadData;
use Contain\Display\Model\LoggedComponents;

class ForgotPassword {

    public function Render($string_file) {
        $html = "<form id='arena-login' method='POST' action='#' >
                    <h3>".$string_file['forgot_password_q']."</h3>
                    <label>".$string_file['please_enter_email']."</label>
                    <input id='naam' type='email' name='email' placeholder='test@example.com'><br><br>
                    <a class='button login-button' id='forgot_password' >".$string_file['forgot_password']."</a>
                    <a class='button login-button' id='backto' >".$string_file['back']."</a>
                </form>";

        echo $html;
    }

}