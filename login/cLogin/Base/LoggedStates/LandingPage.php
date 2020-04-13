<?php

namespace Contain\Base\LoggedStates;

class LandingPage {

    public function loggedMain() {

        $clicked = sanitize_text_field( $_GET['id'] );
        $id = 0;

        global $wpdb;
        if ($clicked != 'alertBack' && $clicked != 'join'){
            $email = sanitize_text_field( $_GET['email'] );
            $pass= sanitize_text_field( $_GET['pass'] );
            $_SESSION['mail'] = $email;
            $_SESSION['pass'] = $pass;
            $r = 0;
        }
        elseif ($clicked != 'join'){
            $r = $_SESSION["iterator"];
        }
        else{
            $email= $_SESSION['mail'];
            $pass = $_SESSION['pass'];
            $r = 0;
        }
        $alert = $wpdb->get_results( "SELECT report_id, tempID, event_category, event_description, description_subject FROM {$wpdb->prefix}tra_reports", OBJECT );
        $arena = $wpdb->get_results( "SELECT first_name, arenaTempID, last_name, email, password, associatedAlert, skill FROM {$wpdb->prefix}arena", OBJECT );
        for ($r; $r<count($arena); $r++) {
            if (($email === $arena[$r]->email && $pass === $arena[$r]->password) OR ($clicked === 'alertBack')) {
                $_SESSION['mail'] = $arena[$r] -> email;
                $_SESSION["iterator"] = $r ;
                if ($clicked != 'alertBack' && $clicked != 'join') {
                    echo "<div class='row'>";
                    echo "<div class='col-9'></div>";
                    echo "<div class=' col-3'>";
                    echo "Logged in as <b class='userEmail'>".$arena[$r] -> first_name." ".$arena[$r] -> last_name."</b>";

                    echo "</div>";

                    echo "</div>";
                    echo "<hr>";
                    echo "<div id='alertPanel' class='row'>";
                }

                $arenaSkills = explode(',', $arena[$r] -> skill);
                for ($j=0; $j<count($alert); $j++) {
                    $alertSkills = explode(',', $alert[$j] -> event_category);
                    for ($k=0; $k<count($alertSkills); $k++) {
                        for ($i=0; $i<count($arenaSkills); $i++) {
                            if (($arenaSkills[$i] === $alertSkills[$k]) || ($arena[$r]->arenaTempID === $alert[$j]->tempID )) {
                                echo "<div value='$j' id='toAlert' class='alertPost col-7'>";
                                echo $alert[$j] -> description_subject;
                                echo "</div>";
                                $arenaID = explode(',', $arena[$r] -> associatedAlert);
                                for ($o=0; $o<count($arenaID); $o++){
                                    if ($arenaID[$o] === $alert[$j] -> report_id){
                                        echo "<button id='inprogress$id' value='$j' class='inprogress col-3' >IN PROGRESS</button><br>";
                                        $o = 999;
                                    }
                                    elseif($arenaID[$o] === ""){
                                        $event = $alert[$j] -> report_id;
                                        echo "<button id='join$id' class='join' value='$event' class='col-3'>JOIN</button>";
                                    }

                                    $id++;
                                }
                                $k = 999;

                            }
                        }
                    }
                }
                echo "</div>";
                wp_die();
            }
        }
    }
}