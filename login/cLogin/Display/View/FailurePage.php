<?php

namespace Contain\Display\View;

class FailurePage {

    public function RenderErrorPage() {
        $html =  " <h1>Email Or Password Incorrect</h1> ";
        $html .= " <button class='button' onclick='window.location.reload();' >Go Back</button> ";
        echo $html;
    }
}