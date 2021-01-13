<?php

namespace Contain\Display\View\ModeratorSection;

class DirectionPage {

    public function direction_buttons() {
        $html = " <button class='button' id='expert'>Accept/Reject FLP</button> ";
        $html .= " <button class='button' id='alert_case'>Accept/Reject Alert Case</button> ";

        echo $html;
    }
}