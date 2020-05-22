<?php

namespace Contain\Display\Controller;

use Contain\Display\View;

class ControlCenter {



    // Redirection Conditions
    public function controlLogic($clicked) {

        switch ($clicked) {

            /* <-- Backdoor Login Section --> */

            case 'BackdoorLogin':
                $loggedState = new View\LoginForm();
                $loggedState->BackdoorLogin();
                break;

            case 'Backdoor':
                $_SESSION['Email'] = sanitize_text_field( $_GET['email'] );
                $_SESSION['Password'] = sanitize_text_field( $_GET['pass'] );
                if ($this->VerifyModerator($_SESSION['Email'],$_SESSION['Password']) === true) {
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
                $loggedState = new View\ExpertDecidePage();
                $loggedState->loggedMain($_SESSION['Country']);
                break;

            case 'go_back':
                $loggedState = new View\DirectionPage();
                $loggedState->direction_buttons();
                break;

            case 'CloseCase':
                $decision = sanitize_text_field( $_GET['close'] );
                $loggedState = new View\AlertDecidePage();
                $loggedState->decide_case_state($decision);
                break;

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

            case 'AcceptRejectCase':
                $decisionId = sanitize_text_field( $_GET['DecideId'] );
                $loggedState = new View\AlertDecidePage();
                $loggedState->decide_case_state($decisionId);
                break;


            /* <-- Login Section --> */

            case 'FirstLogin':
                $loggedState = new View\LoginForm();
                $loggedState->arenaLogin();
                break;

            case 'login':
                $_SESSION['Email'] = sanitize_text_field( $_GET['email'] );
                $_SESSION['Password'] = sanitize_text_field( $_GET['pass'] );
                if ($this->VerifyCredentials($_SESSION['Email'],$_SESSION['Password']) === true) {
                    $loggedState = new View\LandingPage();
                    $loggedState->RenderPage($_SESSION['Email']);
                    break;
                }
                else {
                    $loggedState = new View\FailurePage();
                    $loggedState->RenderErrorPage();
                    break;
                }

            case 'ArenaClickableButtons':
                    $loggedState = new View\LandingPage();
                    $loggedState->ClickButton(sanitize_text_field( $_GET['ID'] ), $_SESSION['Email']);
                    break;

            case 'OnClickSubject':
                $loggedState = new View\DiscussionPage();
                $loggedState->Render($_SESSION['Email'], sanitize_text_field( $_GET['ID']));
                break;

            case 'Recommend':
                $loggedState = new View\DiscussionPage();
                $loggedState->RenderRecommendation($_SESSION['Email'], sanitize_text_field( $_GET['ID']));
                break;

            case 'AddRecommendation':
                $loggedState = new View\DiscussionPage();
                $loggedState->InsertRecommendation(sanitize_text_field( $_GET['ID']), $_SESSION['Email'], sanitize_text_field( $_GET['Data']));
                break;

            case 'UpdateRecommendation':
                $loggedState = new View\DiscussionPage();
                $loggedState->UpdateRecommendation(sanitize_text_field( $_GET['ID']), $_SESSION['Email'], sanitize_text_field( $_GET['Data']), sanitize_text_field( $_GET['RecommendationId']));
                break;

            case 'OnClickBack':
                $loggedState = new View\DiscussionPage();
                $loggedState->ClickButton($_SESSION['Email']);
                break;

            case 'OnClickComment':
                $loggedState = new View\DiscussionPage();
                $loggedState->CommentsLogic(sanitize_text_field( $_GET['ID']), $_SESSION['Email'], sanitize_text_field( $_GET['Data']));
                break;










            /* <-- Old Section --> */

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
        }
        wp_die();
    }


    public function VerifyCredentials($Email, $Password) {
        $LoadData = new LoadData();
        $DataEmail = $LoadData->loadArenaData('email', $Email);
        $DataPassword = $LoadData->loadArenaData('password', $Email);
        $DataStatus = $LoadData->loadArenaData('expert_status', $Email);
        $length = count($DataEmail);

        for ($counter = 0; $counter<$length; $counter++) {
            if ($Email === $DataEmail[$counter] && $Password === $DataPassword[$counter] && $DataStatus[$counter] === 'Accepted') {
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
}