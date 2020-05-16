<?php

namespace Contain\Display\View;

use Contain\Display\Controller\LoadData;
use Contain\Display\Model\LoggedComponents;

class ExpertDecidePage {

    public function loggedMain() {

        $LoadData = new LoadData();
        $DataEmail = $LoadData->loadArenaData();
        $length = count($DataEmail);

        $html = " <h2>Accept/Reject FLP</h2> ";
        $html .= " <table> ";
        for ($counter = 0; $counter<$length; $counter++) {
            $html .= " <tr> ";
                $html .= " <td> ";
                    $html .= " <p>" . $DataEmail[$counter] -> title . "</p> ";
                $html .= " </td> ";
                $html .= " <td> ";
                    $html .= " <p>" . $DataEmail[$counter] -> first_name . "</p> ";
                $html .= " </td> ";
                $html .= " <td> ";
                    $html .= " <p>" . $DataEmail[$counter] -> last_name . "</p> ";
                $html .= " </td> ";
                $html .= " <td> ";
                    $html .= " <p>" . $DataEmail[$counter] -> email . "</p> ";
                $html .= " </td> ";
                $html .= " <td> ";
                    $html .= " <p>" . $DataEmail[$counter] -> country . "</p> ";
                $html .= " </td> ";
                $html .= " <td> ";
                    $html .= " <p>" . $DataEmail[$counter] -> skill . "</p> ";
                $html .= " </td> ";
                $html .= " <td> ";
                    $html .= " <p>" . $DataEmail[$counter] -> description . "</p> ";
                $html .= " </td> ";
                $html .= " <td> ";
                    $html .= " <p>" . $DataEmail[$counter] -> expert_type . "</p> ";
                $html .= " </td> ";
                $html .= " <td class='table_entry'> ";
                    $html .= " <p>" . $this->decide($DataEmail[$counter] -> expert_type, $counter, $DataEmail[$counter] -> report_id, $DataEmail[$counter] -> expert_status) . "</p> ";
                $html .= " </td> ";
            $html .= " </tr> ";

        }
        $html .= " </table> ";
        $html .= " <button id='back' class='button'>Back</button> ";
        echo $html;
    }

    public function decide($type, $counter, $id, $status) {
        if ($type !== "Moderator") {
            if (empty($status)) {
                $html = "<button id='Accepted-". $id ."' class='button decide decide_expert decide-".$counter."'>Accept</button>";
                $html .= "<button id='Rejected-". $id ."' class='button decide decide_expert decide-".$counter."'>Reject</button>";
            }
            else {
                $html = "<button class='button decide decide_expert' disabled>Accept</button>";
                $html .= "<button class='button decide decide_expert' disabled>Reject</button>";
            }
        }
        else {
            $html = "";
        }


        return $html;
    }

    public function decide_state($decision) {
        $result = explode('-', $decision);
        $decision_result = $result[0];
        $decision_entry = $result[1];
        $this->update_entry($decision_result, $decision_entry);
    }

    public function update_entry($result, $entry) {
        global $wpdb;
        $wpdb->update("wp_arena", array('expert_status' => $result), array('report_id' => $entry));
    }


}