<?php

namespace Comprise\Base\StateTypes;

class Notification {

    public function __construct($language) {
        $this->lang = $language;
    }

    public function render($flp_id) {
//        $this->send_mail_new_user($flp_id);
        $html = "<p>".$this->lang['redirect']."</p>";
//        $html .= "<script>setTimeout(function(){window.location.reload(1);}, 15000);</script>";
        $html .= "<a class='button' onclick='location.reload();' >Finish</a>";

        echo $html;
    }

    public function send_mail_new_user($flp_id) {
        global $wpdb;
        $Email = $wpdb->get_results( "SELECT flp_email FROM {$wpdb->prefix}arena WHERE flp_id='$flp_id'", OBJECT );
        $Email = $Email[0]->flp_email;
        wp_mail( "$Email", $this->lang['arena_register_module'], $this->lang['flp_registered'], array('Content-Type: text/html; charset=UTF-8'));
    }

}