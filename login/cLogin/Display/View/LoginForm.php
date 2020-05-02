<?php

namespace Contain\Display\View;

use Contain\Display\Model;

class LoginForm {

    // Log In Page
    public function arenaLogin() {

        $loggedState = new Model\LoggedComponents();
        $loggedState->login();
    }
}