<?php

namespace Contain\Display\View;

use Contain\Display\Model;

class LoginForm {

    // Log In Page
    public function arenaLogin() {

        $loggedState = new Model\LoggedComponents();
        $loggedState->login();
    }

    public function BackdoorLogin($language) {

        $loggedState = new Model\LoggedComponents();
        $loggedState->backdoor_login($language);
    }
}