<?php

namespace Contain\Display\View;

class DirectionPage {

    public function direction_buttons() {
        $html = " <button class='button' id='expert'>Accept/Reject FLP</button> ";

        // ----
        $html .= " <button class='button' id='alert_case'>Accept/Reject Alert Case</button> ";
        $html .= " <button class='button' id='case'>Close Arena Case</button> ";
        // after accepting/rejecting close button appears

        echo $html;
    }
}