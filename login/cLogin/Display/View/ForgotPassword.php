<?php

namespace Contain\Display\View;

use Contain\Display\Controller\LoadData;
use Contain\Display\Model\LoggedComponents;

class ForgotPassword {

    public function Render() {
        $html = "<form id='arena-login' method='POST' action='#' >
                    <label>Email</label>
                    <input id='naam' type='email' name='email'><br><br>
                    <a class='button login-button' id='forgot_password' >Forgot Password</a>
                </form>";

        echo $html;
    }

}