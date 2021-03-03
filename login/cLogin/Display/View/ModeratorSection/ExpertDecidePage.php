<?php

namespace Contain\Display\View\ModeratorSection;

use Contain\Display\Controller\LoadData;
use Contain\Display\Model\LoggedComponents;

class ExpertDecidePage {

    public function __construct() {
        $this->language = $_SESSION['strings'];
    }

    public function loggedMain($Country) {

        $LoadData = new LoadData();
        $Data = $LoadData->loadArenaData();
        $length = count($Data);

        $html = " <h2>".$this->language['accept_reject_flp']."</h2> ";
        $html .= " <table> ";
        $html .= " <tr> ";
        $html .= " <td> ";
        $html .= " <b>".$this->language['title']."</b> ";
        $html .= " </td> ";
        $html .= " <td> ";
        $html .= " <b>".$this->language['first_name']."</b> ";
        $html .= " </td> ";
        $html .= " <td> ";
        $html .= " <b>".$this->language['last_name']."</b> ";
        $html .= " </td> ";
        $html .= " <td> ";
        $html .= " <b>".$this->language['email']."</b> ";
        $html .= " </td> ";
        $html .= " <td> ";
        $html .= " <b>".$this->language['country']."</b> ";
        $html .= " </td> ";
        $html .= " <td> ";
        $html .= " <b>".$this->language['skills']."</b> ";
        $html .= " </td> ";
        $html .= " <td> ";
        $html .= " <b class='table_expert_column'>".$this->language['expert_status']."</b> ";
        $html .= " </td> ";
        $html .= " </tr> ";
        for ($counter = 0; $counter<$length; $counter++) {
            if ($Country[0] === $Data[$counter] -> flp_country) {
                    $html .= " <tr> ";
                        $html .= " <td> ";
                            $html .= " <p>" . $Data[$counter] -> flp_title . "</p> ";
                        $html .= " </td> ";
                        $html .= " <td> ";
                            $html .= " <p>" . $Data[$counter] -> flp_first_name . "</p> ";
                        $html .= " </td> ";
                        $html .= " <td> ";
                            $html .= " <p>" . $Data[$counter] -> flp_last_name . "</p> ";
                        $html .= " </td> ";
                        $html .= " <td> ";
                            $html .= " <p>" . $Data[$counter] -> flp_email . "</p> ";
                        $html .= " </td> ";
                        $html .= " <td> ";
                            $html .= " <p>" . $Data[$counter] -> flp_country . "</p> ";
                        $html .= " </td> ";
                        $html .= " <td> ";
                            $html .= " <p>" . $Data[$counter] -> flp_skills . "</p> ";
                        $html .= " </td> ";
                        $html .= " <td class='table_entry'> ";
                            $html .= " <p>" . $this->decide($Data[$counter] -> flp_title, $counter, $Data[$counter] -> flp_id, $Data[$counter] -> flp_status) . "</p> ";
                        $html .= " </td> ";
                    $html .= " </tr> ";
                }

        }
        $html .= " </table> ";
        $html .= " <button id='back' class='button'>".$this->language['back']."</button> ";
        echo $html;
    }

    public function decide($type, $counter, $id, $status) {
        if ($type !== "Moderator") {
            if (empty($status)) {
                $html = "<button id='Accepted-". $id ."' class='button decide decide_expert decide-".$counter."'>".$this->language['accept']."</button>";
                $html .= "<button id='Rejected-". $id ."' class='button decide decide_expert decide-".$counter."'>".$this->language['reject']."</button>";
            }
            elseif ($status === 'Accepted') {
                $html = "<button class='button decide decide_expert' disabled>".$this->language['accepted']."</button>";
            }
            else {
                $html = "<button class='button decide decide_expert' disabled>".$this->language['rejected']."</button>";
            }
        }
        else {
            $html = "";
        }


        return $html;
    }

    public function decide_state($decision, $country) {
        $result = explode('-', $decision);
        $decision_result = $result[0];
        $decision_entry = $result[1];
        $this->update_entry($decision_result, $decision_entry);
        $this->SendMail($decision_entry);
        $this->loggedMain($country);
    }

    public function update_entry($result, $entry) {
        global $wpdb;
        $wpdb->update("wp_arena", array('flp_status' => $result), array('flp_id' => $entry));
    }

    public function SendMail($UserID) {
        global $wpdb;
        $Email = $wpdb->get_results( "SELECT flp_email FROM {$wpdb->prefix}arena WHERE flp_id='$UserID'", OBJECT );
        $Email = $Email[0]->flp_email;
        wp_mail( "$Email", $this->language['arena_login_module'], $this->language['email_approved'], array('Content-Type: text/html; charset=UTF-8'));
    }

}