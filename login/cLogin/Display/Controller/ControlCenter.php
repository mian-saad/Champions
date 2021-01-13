<?php

namespace Contain\Display\Controller;

use Contain\Display\View;
use Contain\Display\Model;

class ControlCenter {

    // Redirection Conditions
    public function controlLogic($clicked) {

        $plugin_path = plugin_dir_path( dirname(__FILE__, 3));

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
                    $loggedState = new View\ModeratorSection\DirectionPage();
                    $loggedState->direction_buttons();
                    break;
                }
                else {
                    $loggedState = new View\FailurePage();
                    $loggedState->RenderErrorPage();
                    break;
                }

            case 'decide_expert':
                $loggedState = new View\ModeratorSection\ExpertDecidePage();
                $loggedState->loggedMain($_SESSION['Country']);
                break;

            case 'go_back':
                $loggedState = new View\ModeratorSection\DirectionPage();
                $loggedState->direction_buttons();
                break;

            case 'CloseCase':
                $decision = sanitize_text_field( $_GET['close'] );
                $loggedState = new View\ModeratorSection\AlertDecidePage();
                $loggedState->decide_case_state($decision);
                break;

            case 'alert_case':
                $loggedState = new View\ModeratorSection\AlertDecidePage();
                $loggedState->case_decision($_SESSION['Country']);
                break;

            case 'decide_expert_case':
                $decisionId = sanitize_text_field( $_GET['DecideId'] );
                $loggedState = new View\ModeratorSection\ExpertDecidePage();
                $loggedState->decide_state($decisionId, $_SESSION['Country']);
                break;

            case 'AcceptRejectCase':
                $decisionId = sanitize_text_field( $_GET['DecideId'] );
                $loggedState = new View\ModeratorSection\AlertDecidePage();
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
                    $loggedState = new View\FlpSection\LandingPage($_SESSION['strings']);
                    $loggedState->RenderPage($_SESSION['Email']);
                    break;
                }
                else {
                    $loggedState = new View\FailurePage();
                    $loggedState->RenderErrorPage();
                    break;
                }

            case 'ArenaClickableButtons':
                    $loggedState = new View\FlpSection\LandingPage($_SESSION['strings']);
                    $loggedState->ClickButton(sanitize_text_field( $_GET['ID'] ), $_SESSION['Email']);
                    break;

            case 'OnClickSubject':
                $loggedState = new View\FlpSection\DiscussionPage($_SESSION['strings']);
                $loggedState->Render($_SESSION['Email'], sanitize_text_field( $_GET['ID']));
                break;

            case 'Recommend':
                $loggedState = new View\FlpSection\DiscussionPage($_SESSION['strings']);
                $loggedState->RenderRecommendation($_SESSION['Email'], sanitize_text_field( $_GET['ID']));
                break;

            case 'LanguageSelect':
                $loggedState = new View\SelectLanguage();
                $loggedState->Render();
                break;

            case 'MainPage':
                $loggedState = new View\MainPage();
                if (!empty($_GET['data'])) {
                    $_SESSION['language'] = $_GET['data'];
                    $string_file = json_decode(file_get_contents($plugin_path . "assets/base/" . sanitize_text_field( $_GET['data'] ) . "/alert_strings.json"), true);
                    $_SESSION['strings'] = $string_file;
                    $loggedState->Render($_SESSION['strings']);
                }
                else {
                    $loggedState->Render($_SESSION['strings']);
                }

                break;

            case 'Forgot':
                $loggedState = new View\ForgotPassword();
                $loggedState->Render($_SESSION['strings']);
                break;

            case 'ForgotPassword':
                $loggedState = new View\PasswordSent();
                $loggedState->Render(sanitize_text_field( $_GET['data'] ));
                break;

            case 'Edit':
                $loggedState = new View\FlpSection\EditProfile($_SESSION['strings'], sanitize_text_field( $_GET['data']));
                $loggedState->render();
                break;

            case 'Update':
                $loggedState = new View\FlpSection\ProfileUpdated();
                $loggedState->update($_GET['data'], $_SESSION['Email']);
                break;

            case 'AddRecommendation':
                $loggedState = new View\FlpSection\DiscussionPage($_SESSION['strings']);
                $loggedState->InsertRecommendation(sanitize_text_field( $_GET['ID']), $_SESSION['Email'], sanitize_text_field( $_GET['Data']));
                break;

            case 'UpdateRecommendation':
                $loggedState = new View\FlpSection\DiscussionPage($_SESSION['strings']);
                $loggedState->UpdateRecommendation(sanitize_text_field( $_GET['ID']), $_SESSION['Email'], sanitize_text_field( $_GET['Data']), sanitize_text_field( $_GET['RecommendationId']));
                break;

            case 'OnClickBack':
                $loggedState = new View\FlpSection\DiscussionPage($_SESSION['strings']);
                $loggedState->ClickButton($_SESSION['Email']);
                break;

            case 'OnClickComment':
                $loggedState = new View\FlpSection\DiscussionPage($_SESSION['strings']);
                $loggedState->CommentsLogic(sanitize_text_field( $_GET['ID']), $_SESSION['Email'], sanitize_text_field( $_GET['Data']));
                break;

            case 'InvitationMechanism':
                $loggedState = new Model\LoggedComponents();
                $loggedState->InviteExperts(sanitize_text_field( $_GET['InvitationEmail']), $_GET['alert'] );
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
        $DataExpertType = $LoadData->loadArenaData('title', $Email);
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