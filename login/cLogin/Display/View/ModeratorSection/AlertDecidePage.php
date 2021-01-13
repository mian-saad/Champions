<?php

namespace Contain\Display\View\ModeratorSection;

use Contain\Display\Controller\LoadData;

class AlertDecidePage {

    public function case_decision($Country) {

        $LoadData = new LoadData();
        $Data = $LoadData->loadAlertData();
        $length = count($Data);

        $html = " <h2>Accept/Reject Alert Case</h2> ";
        $html .= " <table> ";
        $html .= " <tr> ";

        $html .= " <td> ";
        $html .= " <b>Subject</b> ";
        $html .= " </td> ";

        $html .= " <td> ";
        $html .= " <b>Event Time</b> ";
        $html .= " </td> ";

        $html .= " <td> ";
        $html .= " <b>Category</b> ";
        $html .= " </td> ";

        $html .= " <td> ";
        $html .= " <b>Country</b> ";
        $html .= " </td> ";

        $html .= " <td> ";
        $html .= " <b>FLP Status</b> ";
        $html .= " </td> ";

        $html .= " <td> ";
        $html .= " <b>Arena Status</b> ";
        $html .= " </td> ";

        $html .= " <td> ";
        $html .= " <b>Alert Status</b> ";
        $html .= " </td> ";

        $html .= " </tr> ";
        for ($counter = 0; $counter<$length; $counter++) {
            if ($Country[0] === $Data[$counter] -> alert_country) {
                $html .= " <tr> ";

                $html .= " <td> ";
                $html .= " <p>" . $Data[$counter] -> alert_subject . "</p> ";
                $html .= " </td> ";
                $html .= " <td> ";
                $html .= " <p>" . $Data[$counter] -> alert_time . "</p> ";
                $html .= " </td> ";
                $html .= " <td> ";
                $html .= " <p>" . $Data[$counter] -> alert_category . "</p> ";
                $html .= " </td> ";
                $html .= " <td> ";
                $html .= " <p>" . $Data[$counter] -> alert_country . "</p> ";
                $html .= " </td> ";


                if ($Data[$counter] -> alert_status_flp === null) {
                    $html .= " <td> ";
                    $html .= " <p>Open</p> ";
                    $html .= " </td> ";
                }
                else {
                    $html .= " <td> ";
                    $html .= " <p>" . $Data[$counter] -> alert_status_flp . "</p> ";
                    $html .= " </td> ";
                }
                if ($Data[$counter] -> alert_status_mutual === null) {
                    $html .= " <td> ";
                    $html .= " <p>Open</p> ";
                    $html .= " </td> ";
                }
                else {
                    $html .= " <td> ";
                    $html .= " <p>" . $Data[$counter] -> alert_status_mutual . "</p> ";
                    $html .= " </td> ";
                }
                $html .= " <td class='table_entry'> ";
                $html .= " <p id='".$Data[$counter] -> alert_id."'>" . $this->decide($counter, $Data[$counter] -> alert_id, $Data[$counter] -> alert_case_status) . "</p> ";
                $html .= " </td> ";
                $html .= " </tr> ";
            }
        }
        $html .= " </table> ";
        $html .= " <button id='back' class='button'>Back</button> ";
        echo $html;
    }

    public function decide($counter, $id, $status) {
        if (empty($status)) {
            $html = "<button id='Accepted-". $id ."' class='button decide decide_case decide-".$counter."'>Accept</button>";
            $html .= "<button id='Rejected-". $id ."' class='button decide decide_case decide-".$counter."'>Reject</button>";
        }
        else {
            $html = $this->transition($status, $id);
        }
        return $html;
    }

    public function decide_case_state($decision) {
        $result = explode('-', $decision);
        $decision_result = $result[0];
        $decision_entry = $result[1];
        if ($decision_result === 'Closed') {
            if ($this->validate_entry($decision_entry) === true) {
                $this->update_entry($decision_result, $decision_entry);
                $this->SendMail($decision_entry, 'Closed');
                echo $this->transition($decision_result, $decision_entry);
            }
            else {
                echo 'alert';
            }
        }
        else {
            $this->update_entry($decision_result, $decision_entry);
            $this->SendMail($decision_entry, 'Approved');
            echo $this->transition($decision_result, $decision_entry);
        }

    }

    public function transition($res, $id) {
        $html = "";
        if ($res === 'Rejected') {
            $html .= "<button id='Closed-". $id ."' class='button decide close_case' disabled>Rejected</button>";
        }
        else if ($res === 'Closed') {
            $html .= "<button id='Closed-". $id ."' class='button decide close_case' disabled>Closed</button>";
        }
        else {
            $html .= "<button id='Closed-". $id ."' class='button decide close_case'>Close </button>";
        }
        return $html;
    }

    public function validate_entry($decision_entry) {

        //        if (other alert case are closed)
        $LoadData = new LoadData();
        $DataFLP = $LoadData->loadAlertData('alert_status_flp', $decision_entry);
        $DataMutual = $LoadData->loadAlertData('alert_status_mutual', $decision_entry);
        // when there is only flp and a moderator part of the case => then to close the case exempt the expert
        if ($this->validate_no_expert($decision_entry) && !empty($DataFLP[0])) {
            return true;
        }
        if (empty($DataFLP[0]) || empty($DataMutual[0])) {
            return false;
        }
        if (!empty($DataFLP[0]) && !empty($DataMutual[0])) {
            return true;
        }
        return false;
    }

    public function validate_no_expert($decision_entry) {
        global $wpdb;
        $count = 0;
        $isExpert = $wpdb->get_results( "SELECT flp_associatedAlert FROM {$wpdb->prefix}arena", OBJECT );
        for ($counter = 0; $counter < count($isExpert); $counter++) {
            $values = explode(",", $isExpert[$counter]->flp_associatedAlert);
            for ($iterator = 0; $iterator < count($values); $iterator++) {
                if ($values[$iterator] === $decision_entry) {
                    $count++;
                }
            }
        }
        if ($count < 2) {
            return true;
        }
        return false;
    }

    public function update_entry($results, $entrys) {
        global $wpdb;
        $wpdb->update("wp_alert", array('alert_case_status' => $results), array('alert_id' => $entrys));
    }

    public function SendMail($UserID, $State) {
        global $wpdb;
        $flp_id = $wpdb->get_results( "SELECT flp_id FROM {$wpdb->prefix}alert WHERE alert_id='$UserID'", OBJECT );
        $id = $flp_id[0]->flp_id;
        $Email = $wpdb->get_results( "SELECT flp_email FROM {$wpdb->prefix}arena WHERE flp_id = '$id'", OBJECT );
        $Email = $Email[0]->flp_email;
        wp_mail( "$Email", "Arena Case Module", "Your Case has been ".$State.".", array('Content-Type: text/html; charset=UTF-8'));
    }

}