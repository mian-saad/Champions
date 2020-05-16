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
                $html .= " <td> ";
                $html .= " <p>" . $Data[$counter] -> alert_status_flp . "</p> ";
                $html .= " </td> ";
                $html .= " <td> ";
                $html .= " <p>" . $Data[$counter] -> alert_status_mutual . "</p> ";
                $html .= " </td> ";
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
            $html = $this->transition($id);
        }
        return $html;
    }

    public function decide_case_state($decision) {
        $result = explode('-', $decision);
        $decision_result = $result[0];
        $decision_entry = $result[1];
        $this->update_entry($decision_result, $decision_entry);
        echo $this->transition($decision_entry);
    }

    public function transition($id) {
        $html = "";
        $html .= "<button id='Closed-". $id ."' class='button decide close_case'>Close Case</button>";
        return $html;
    }

    public function validate_entry($decision_entry) {
        //        if (other alert case are closed)
        $LoadData = new LoadData();
        $DataFLP = $LoadData->loadAlertData('alert_status_flp', $decision_entry);
        $DataMutual = $LoadData->loadAlertData('alert_status_mutual', $decision_entry);
        if (empty($DataFLP) || empty($DataMutual)) {
            return false;
        }
        return true;
    }

    public function update_entry($result, $entry) {
        global $wpdb;
        $wpdb->update("wp_tra_reports", array('alert_case_status' => $result), array('report_id' => $entry));
    }

}