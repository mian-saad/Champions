<?php

namespace Route\Display\Controller;

use Route\Display\View;

class ControlCenter1 {

    // Redirection Conditions
    public function controlLogic($tick) {

        switch ($tick) {

            case 'FirstLogin':
                $loggedState = new View\LoginForm();
                $loggedState->arenaLogin();
                break;

            case 'login':
                $_SESSION['Email'] = sanitize_text_field( $_GET['email'] );
                $_SESSION['Password'] = sanitize_text_field( $_GET['pass'] );
                if ($this->VerifyCredentials($_SESSION['Email'],$_SESSION['Password']) === true) {
                    $loggedState = new View\DirectionPage();
                    $loggedState->direction_buttons();
                    break;
                }
                else {
                    $loggedState = new View\FailurePage();
                    $loggedState->RenderErrorPage();
                    break;
                }

            case 'decide_expert':
                $loggedState = new View\BackdoorLandingPage();
                $loggedState->loggedMain();
                break;

            case 'decide_case':
                $loggedState = new View\CasePage();
                $loggedState->case_decision();
                break;

            case 'decide_case_state':
                $decision = sanitize_text_field( $_GET['close'] );
                $loggedState = new View\CasePage();
                $loggedState->decide_case_state($decision);
                break;

            case 'decide':
                $decision = sanitize_text_field( $_GET['determine'] );
                $loggedState = new View\BackdoorLandingPage();
                $loggedState->decide_state($decision);
                break;

        }

        wp_die();
    }

    public function VerifyCredentials($Email, $Password) {
        $LoadData = new LoadData1();
        $DataEmail = $LoadData->loadArenaData('email', $Email);
        $DataPassword = $LoadData->loadArenaData('password', $Email);
        $DataType = $LoadData->loadArenaData('expert_type', $Email);
        $length = count($DataEmail);

        for ($counter = 0; $counter<$length; $counter++) {
            if ($Email === $DataEmail[$counter] && $Password === $DataPassword[$counter] && ($DataType[$counter] === "Moderator")) {
                return true;
            }
        }
        return false;
    }
}
