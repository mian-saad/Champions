<?php

namespace Contain\Display\Controller;

use Contain\Display\View;

class ControlCenter {

    public function VerifyCredentials($Email, $Password) {
        $LoadData = new LoadData();
        $DataEmail = $LoadData->loadArenaData('email');
        $DataPassword = $LoadData->loadArenaData('password');
        $length = count($DataEmail);

        for ($counter = 0; $counter<$length; $counter++) {
            if ($Email === $DataEmail[$counter] && $Password === $DataPassword[$counter]) {
                return true;
            }
        }
        return false;
    }

    // Redirection Conditions
    public function controlLogic($clicked) {

        switch ($clicked) {

            case 'FormLogin':
                $loggedState = new View\LoginForm();
                $loggedState->arenaLogin();
                break;

            case 'alertBack':
                break;

            case 'login':
                $_SESSION['Email'] = sanitize_text_field( $_GET['email'] );
                $_SESSION['Password'] = sanitize_text_field( $_GET['pass'] );
                if ($this->VerifyCredentials($_SESSION['Email'],$_SESSION['Password']) === true) {
                    $loggedState = new View\LandingPage();
                    $loggedState->loggedMain();
                    break;
                }
                else {
                    $loggedState = new View\FailurePage();
                    $loggedState->RenderErrorPage();
                    break;
                }

            case 'alerter':
                $loggedState = new View\DiscussionPage();
                $loggedState->loggedParticipants();
                break;

            case 'cmnt':
                $loggedState = new View\DiscussionPage();
                $loggedState->loggedAddComments();
                break;

            case 'showcmnt':
                $loggedState = new View\DiscussionPage();
                $loggedState->loggedDisplayComments();
                break;

            case 'join':
                $loggedState = new View\LandingPage();
                $loggedState->loggedAlert();
                break;

            case 'inpro':
                $loggedState = new View\DiscussionPage();
                $loggedState->loggedPageSection();
                break;

            case 'addRecommendation':
                $loggedState = new View\DiscussionPage();
                $loggedState->loggedDisplayRecommendation();
                break;

            case 'updateRecommendation':
                $loggedState = new View\DiscussionPage();
                $loggedState->loggedUpdateRecommendation();
                break;

            case 'invitation':
                $loggedState = new View\DiscussionPage();
                $inviteEmail = sanitize_text_field( $_GET['inviteEmail'] );
                $loggedState->send_invite($inviteEmail);
                break;
        }

        wp_die();
    }
}