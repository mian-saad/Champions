<?php

namespace Contain\Display\Controller;

use Contain\Display\View;

class ControlCenter {

    public function VerifyCredentials($Email, $Password) {
        $LoadData = new LoadData();
        $DataEmail = $LoadData->loadArenaData('email', $Email);
        $DataPassword = $LoadData->loadArenaData('password', $Email);
        $length = count($DataEmail);

        for ($counter = 0; $counter<$length; $counter++) {
            if ($Email === $DataEmail[$counter] && $Password === $DataPassword[$counter]) {
                return true;
            }
        }
        return false;
    }

    public function VerifyModerator($Email, $Password) {
        $LoadData = new LoadData();
        $DataEmail = $LoadData->loadArenaData('email', $Email);
        $DataPassword = $LoadData->loadArenaData('password', $Email);
        $DataExpertType = $LoadData->loadArenaData('expert_type', $Email);
        $_SESSION['Country'] = $LoadData->loadArenaData('country', $Email);
        $length = count($DataEmail);

        for ($counter = 0; $counter<$length; $counter++) {
            if ($Email === $DataEmail[$counter] && $Password === $DataPassword[$counter] && $DataExpertType[$counter] === 'Moderator') {
                return true;
            }
        }
        return false;
    }

    // Redirection Conditions
    public function controlLogic($clicked) {

        switch ($clicked) {

            case 'FirstLogin':
                $loggedState = new View\LoginForm();
                $loggedState->arenaLogin();
                break;

            case 'BackdoorLogin':
                $loggedState = new View\LoginForm();
                $loggedState->BackdoorLogin();
                break;

            case 'Backdoor':
                $_SESSION['Email'] = sanitize_text_field( $_GET['email'] );
                $_SESSION['Password'] = sanitize_text_field( $_GET['pass'] );
                // if ($Email === $DataEmail[$counter] && $Password === $DataPassword[$counter] && ($DataType[$counter] === "Moderator"))
                if ($this->VerifyModerator($_SESSION['Email'],$_SESSION['Password']) === true) {
                    $loggedState = new View\DirectionPage();
                    $loggedState->direction_buttons();
                    break;
                }
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

            case 'in_progress':
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

            case 'decide_expert':
                $loggedState = new View\ExpertDecidePage();
                $loggedState->loggedMain();
                break;

            case 'go_back':
                $loggedState = new View\DirectionPage();
                $loggedState->direction_buttons();
                break;

//            case 'decide_case':
//                $loggedState = new View\CaseClosePage();
//                $loggedState->case_decision();
//                break;

            case 'decide_case_state':
                $decision = sanitize_text_field( $_GET['close'] );
                $loggedState = new View\CaseClosePage();
                $loggedState->decide_case_state($decision);
                break;

                //TODO: only those cases should appear for a moderator which belongs to the same country
            case 'alert_case':
                $loggedState = new View\AlertDecidePage();
                $loggedState->case_decision($_SESSION['Country']);
                break;

            case 'decide':
                $decision = sanitize_text_field( $_GET['determine'] );
                $loggedState = new View\LandingPage1();
                $loggedState->decide_state($decision);
                break;

            case 'decide_expert_case':
                $decisionId = sanitize_text_field( $_GET['DecideId'] );
                $loggedState = new View\ExpertDecidePage();
                $loggedState->decide_state($decisionId);
                break;

            case 'decide_alert_case':
                $decisionId = sanitize_text_field( $_GET['DecideId'] );
                $loggedState = new View\AlertDecidePage();
                $loggedState->decide_case_state($decisionId);
//                echo " <button>Test</button> ";
                break;

        }

        wp_die();
    }

}