<?php

namespace Contain\Display\Controller;

use Contain\Display\View;

class ControlCenter {

    // Redirection Conditions
    public function controlLogic($clicked) {

        switch ($clicked) {

            case 'FormLogin':
                $loggedState = new View\LoginForm();
                $loggedState->arenaLogin();
                break;

            case 'alertBack':
            case 'login':
                $loggedState = new View\LandingPage();
                $loggedState->loggedMain();
                break;

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