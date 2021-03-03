<?php

namespace Contain\Display\View\ModeratorSection;

class DirectionPage {

    public function direction_buttons() {

        $language = $_SESSION['strings'];

        $html = " <button class='button' id='expert'>".$language['accept_reject_flp']."</button> ";
        $html .= " <button class='button' id='alert_case'>".$language['accept_reject_alert']."</button> ";

        echo $html;
    }
}