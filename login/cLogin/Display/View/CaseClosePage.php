<?php

namespace Contain\Display\View;

use Contain\Display\Controller\LoadData;

class CaseClosePage {

    public function case_decision() {

        $LoadData = new LoadData();
        $Data = $LoadData->loadAlertData();
        $length = count($Data);

        $html = " <h2>Close Alert Cases</h2> ";
        $html .= " <table> ";
        for ($counter = 0; $counter<$length; $counter++) {
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

            // one column to show status of case by other alert_status_flp, alert_status_mutual
            $html .= " <td> ";
            $html .= " <p>" . $Data[$counter] -> alert_status_flp . "</p> ";
            $html .= " </td> ";
            $html .= " <td> ";
            $html .= " <p>" . $Data[$counter] -> alert_status_mutual . "</p> ";
            $html .= " </td> ";

            // closed or active
//            $html .= " <td> ";
//            $html .= " <p>" . $Data[$counter] -> event_description . "</p> ";
//            $html .= " </td> ";
            $html .= " <td class='table_entry'> ";
            $html .= " <p>" . $this->decide($counter, $Data[$counter] -> report_id, $Data[$counter] -> alert_status_moderator) . "</p> ";
            $html .= " </td> ";
            $html .= " </tr> ";

        }
        $html .= " </table> ";
        $html .= " <button id='back' class='button'>Back</button> ";
        echo $html;
    }

    public function decide($counter, $id, $status) {
        if (empty($status)) {
            $html = "<button id='Closed-". $id ."' class='button decide close_case Case-".$counter."'>Close Case</button>";
        }
        else {
            $html = "<button class='button decide close_case' disabled>Close Case</button>";
        }

        return $html;
    }

    public function decide_case_state($decision) {
        $result = explode('-', $decision);
        $decision_result = $result[0];
        $decision_entry = $result[1];
        if ($this->validate_entry($decision_entry) === true) {
            $this->update_entry($decision_result, $decision_entry);
        }
        else {
            echo "alert";
        }
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
        $wpdb->update("wp_tra_reports", array('alert_status_moderator' => $result), array('report_id' => $entry));
    }


}