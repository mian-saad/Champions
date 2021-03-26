<?php

namespace Contain\Display\View;


class PasswordSent {


    public function Render($email, $string_file) {

        // generate new random password
        $pass = $this->password(9);

        // check to see if it exists in database
        global $wpdb;
        $arenaData = $wpdb->get_results( "SELECT flp_email FROM {$wpdb->prefix}arena WHERE flp_email='$email'", OBJECT );

        // replace that in the database
        if (!empty($arenaData)) {
            $wpdb->update("wp_arena", array('flp_password' => $pass), array('flp_email' => $email));

            // send to user in email
            wp_mail( $email, $string_file['new_password'], $string_file['please_change_password']. $pass ."");
        }

        // send message to user
        $html = "<section>".$string_file['your_new_password']."</section> ";

        echo $html;
    }

    public function password($chars) {

        $data = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz';
        return substr(str_shuffle($data), 0, $chars);
    }
}