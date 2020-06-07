<?php

namespace Contain\Display\View;

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
        $html .= " <b>Title</b> ";
        $html .= " </td> ";
        $html .= " <td> ";
        $html .= " <b>First Name</b> ";
        $html .= " </td> ";
        $html .= " <td> ";
        $html .= " <b>Last Name</b> ";
        $html .= " </td> ";
        $html .= " <td> ";
        $html .= " <b>Email</b> ";
        $html .= " </td> ";
        $html .= " <td> ";
        $html .= " <b>Country</b> ";
        $html .= " </td> ";
        $html .= " <td> ";
        $html .= " <b>Event Time</b> ";
        $html .= " </td> ";
        $html .= " <td> ";
        $html .= " <b>Category</b> ";
        $html .= " </td> ";
        $html .= " <td> ";
        $html .= " <b>Subject</b> ";
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
            if ($Country[0] === $Data[$counter] -> reporter_residence) {
                $html .= " <tr> ";
                $html .= " <td> ";
                $html .= " <p>" . $Data[$counter] -> title . "</p> ";
                $html .= " </td> ";
                $html .= " <td> ";
                $html .= " <p>" . $Data[$counter] -> reporter_fName . "</p> ";
                $html .= " </td> ";
                $html .= " <td> ";
                $html .= " <p>" . $Data[$counter] -> reporter_lName . "</p> ";
                $html .= " </td> ";
                $html .= " <td> ";
                $html .= " <p>" . $Data[$counter] -> reporter_email . "</p> ";
                $html .= " </td> ";
                $html .= " <td> ";
                $html .= " <p>" . $Data[$counter] -> reporter_residence . "</p> ";
                $html .= " </td> ";
                $html .= " <td> ";
                $html .= " <p>" . $Data[$counter] -> event_time . "</p> ";
                $html .= " </td> ";
                $html .= " <td> ";
                $html .= " <p>" . $Data[$counter] -> event_category . "</p> ";
                $html .= " </td> ";
                $html .= " <td> ";
                $html .= " <p>" . $Data[$counter] -> description_subject . "</p> ";
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
                $html .= " <p id='".$Data[$counter] -> report_id."'>" . $this->decide($counter, $Data[$counter] -> report_id, $Data[$counter] -> alert_case_status) . "</p> ";
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

    public function transition($result, $id) {
        $html = "";
        if ($result === 'Rejected') {
            $html .= "<button id='Closed-". $id ."' class='button decide close_case' disabled>Rejected</button>";
        }
        else if ($result === 'Closed') {
            $html .= "<button id='Closed-". $id ."' class='button decide close_case' disabled>Closed</button>";
        }
        else {
            $html .= "<button id='Closed-". $id ."' class='button decide close_case'>Close Case</button>";
        }
        return $html;
    }

    public function validate_entry($decision_entry) {
        //        if (other alert case are closed)
        $LoadData = new LoadData();
        $DataFLP = $LoadData->loadAlertData('alert_status_flp', $decision_entry);
        $DataMutual = $LoadData->loadAlertData('alert_status_mutual', $decision_entry);
        if (empty($DataFLP[0]) || empty($DataMutual[0])) {
            return false;
        }
        return true;
    }

    public function update_entry($result, $entry) {
        global $wpdb;
        $wpdb->update("wp_alert", array('alert_case_status' => $result), array('report_id' => $entry));
    }

    public function SendMail($UserID, $State) {
        global $wpdb;
        $Email = $wpdb->get_results( "SELECT reporter_email FROM {$wpdb->prefix}alert WHERE report_id='$UserID'", OBJECT );
        $Email = $Email[0]->reporter_email;
        wp_mail( "$Email", "Arena Case Module", "Your Case has been ".$State.".", array('Content-Type: text/html; charset=UTF-8'));
    }

}