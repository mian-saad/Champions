<?php

namespace Route\Display\View;

use Route\Display\Model;

class LoginForm {

    // Log In Page
    public function arenaLogin() {

        $loggedState = new Model\LoggedComponents1();
        $loggedState->login();
    }
}