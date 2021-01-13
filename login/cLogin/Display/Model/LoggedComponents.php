<?php

namespace Contain\Display\Model;

class LoggedComponents {

    public function login() {
        $html = "
                <h2>Welcome to ARENA Login Module</h2><br>
                <form id=\"arena-login\" method=\"POST\" action=\"#\" >
                    <div class='row'>
                        <div class='col-3'></div>
                        <div class='col-6'>
                            <label>Email</label>
                            <input id=\"naam\" type=\"email\" name=\"email\"><br>
                                  
                            <label>Password</label>
                            <input id=\"paas\" type=\"password\" name=\"password\" minlength=\"4\">
                              
                            <button class='button login-button' id=\"Login\" type=\"submit\" onclick=\"return false;\">Login</button>
                            <input type=\"hidden\" name=\"action\" value=\"getData\">
                        </div>
                        <div class='col-3'></div>
                    </div>
                </form>";
        echo $html;
    }

    public function backdoor_login() {
        $html = "
                <h2>Backdoor Login</h2><br>
                <form id=\"arena-login\" method=\"POST\" action=\"#\" >
                    <div class='row'>
                        <div class='col-3'></div>
                        <div class='col-6'>
                            <label>Email</label>
                            <input id=\"naam\" type=\"email\" name=\"email\"><br>
                                  
                            <label>Password</label>
                            <input id=\"paas\" type=\"password\" name=\"password\" minlength=\"4\">
                              
                            <button class='button login-button' id=\"BackdoorLogin\" type=\"submit\" onclick=\"return false;\">Login</button>
                            <button class='button login-button' id=\"backto\" onclick=\"return false;\">Back</button>
                            <input type=\"hidden\" name=\"action\" value=\"getData\">
                        </div>
                        <div class='col-3'></div>
                    </div>
                </form>";
        echo $html;
    }

//    public function header($FirstName, $LastName) {
//        $html = "<div class='row'>";
//            $html .= "<div class='col-9'></div>";
//            $html .= "<div class=' col-3'>";
//                $html .= "Logged in as <b class='userEmail'>".$FirstName." ".$LastName."</b>";
//            $html .= "</div>";
//        $html .= "</div>";
//
//        echo $html;
//    }

//    public function instances($DescriptionSubject, $ButtonStatus, $Id) {
//        $html = "<div class='row'>";
//            $html .= "<div id='CaseSubject.$Id.' class='col-8'>. $DescriptionSubject .</div>";
//            $html .= "<div class='col-3'><button id='CaseProgressStatus.$Id.' class='button'> . $ButtonStatus . </button></div>";
//        $html .= "</div>";
//
//        echo $html;
//    }

    public function InviteExperts($InvitationEmail, $alert) {

        global $wpdb;
        $alert_db = $wpdb->prefix . 'arena';
        $answers = [
            'flp_email' => $InvitationEmail,
            'flp_associatedAlert' => $alert,
            'alert_id' => $alert
        ];
        $wpdb->insert($alert_db, $answers);
        wp_mail( "$InvitationEmail", "Champions - Arena Notification Module", "You have been Invited to the Arena. Please register at https://www.firstlinepractitioners.com/arena/ before you can login.", array('Content-Type: text/html; charset=UTF-8'));
    }
}