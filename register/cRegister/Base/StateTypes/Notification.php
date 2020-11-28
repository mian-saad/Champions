<?php

namespace Comprise\Base\StateTypes;

class Notification {

    public function __construct($language) {
        $this->lang = $language;
    }

    public function render() {
        $html = "<h1>".$this->lang['redirect']."</h1>";
        $html .= "<script>setTimeout(function(){window.location.reload(1);}, 3000);</script>";


        echo $html;
    }

}