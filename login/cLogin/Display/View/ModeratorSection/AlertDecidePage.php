<?php

namespace Contain\Display\View\ModeratorSection;

use Contain\Display\Controller\LoadData;

class AlertDecidePage {

    function __construct(){
        $this->language = $_SESSION['strings'];
    }

    public function case_decision($Country) {


        $LoadData = new LoadData();
        $Data = $LoadData->loadAlertData();
        $length = count($Data);

        $html = " <h2>".$this->language['accept_reject_alert']."</h2> ";
        $html .= " <table> ";
        $html .= " <tr> ";

        $html .= " <td> ";
        $html .= " <b>".$this->language['subject']."</b> ";
        $html .= " </td> ";

        $html .= " <td> ";
        $html .= " <b>".$this->language['event_time']."</b> ";
        $html .= " </td> ";

        $html .= " <td> ";
        $html .= " <b>".$this->language['category']."</b> ";
        $html .= " </td> ";

        $html .= " <td> ";
        $html .= " <b>".$this->language['country']."</b> ";
        $html .= " </td> ";

        $html .= " <td> ";
        $html .= " <b>".$this->language['flp_status']."</b> ";
        $html .= " </td> ";

        $html .= " <td> ";
        $html .= " <b>".$this->language['arena_status']."</b> ";
        $html .= " </td> ";

        $html .= " <td> ";
        $html .= " <b>".$this->language['alert_status']."</b> ";
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
                $html .= " <p>" . str_replace('~~~', ', ',$Data[$counter] -> alert_category) . "</p> ";
                $html .= " </td> ";
                $html .= " <td> ";
                $html .= " <p>" . $Data[$counter] -> alert_country . "</p> ";
                $html .= " </td> ";


                if ($Data[$counter] -> alert_status_flp === null) {
                    $html .= " <td> ";
                    $html .= " <p>".$this->language['open']."</p> ";
                    $html .= " </td> ";
                }
                else {
                    $html .= " <td> ";
                    $html .= " <p>" . $Data[$counter] -> alert_status_flp . "</p> ";
                    $html .= " </td> ";
                }
                if ($Data[$counter] -> alert_status_mutual === null) {
                    $html .= " <td> ";
                    $html .= " <p>".$this->language['open']."</p> ";
                    $html .= " </td> ";
                }
                else {
                    $html .= " <td> ";
                    $html .= " <p>" . $Data[$counter] -> alert_status_mutual . "</p> ";
                    $html .= " </td> ";
                }
                $html .= " <td class='table_entry'> ";
                $html .= $this->infoModal(
                    $Data[$counter] -> alert_report_time,
                    $Data[$counter] -> alert_country,
                    $Data[$counter] -> alert_city,
                    $Data[$counter] -> alert_time,
                    $Data[$counter] -> alert_category,
                    $Data[$counter] -> alert_location,
                    $Data[$counter] -> alert_target,
                    $Data[$counter] -> alert_subject,
                    $Data[$counter] -> alert_description,
                    $Data[$counter] -> alert_deadline,
                    $counter
                );;
                $html .= " <p id='".$Data[$counter] -> alert_id."'>" . $this->decide($counter, $Data[$counter] -> alert_id, $Data[$counter] -> alert_case_status) . "</p> ";
                $html .= " </td> ";
                $html .= " </tr> ";
            }
        }
        $html .= " </table> ";
        $html .= " <button id='back' class='button'>".$this->language['back']."</button> ";
        echo $html;

    }

    public function infoModal($alert_report_time, $alert_country, $alert_city, $alert_time,
                                $alert_category, $alert_location, $alert_target, $alert_subject,
                                $alert_description, $alert_deadline, $counter  ) {

        $html = "<div id='alert".$counter."' class='modal'>
                            <p><b>Report Time: </b>".$alert_report_time."</p>
                            <p><b>Country: </b>". $alert_country ."</p>
                            <p><b>City: </b>". $alert_city ."</p>
                            <p><b>Time: </b>". $alert_time ."</p>
                            <p><b>Category: </b>". str_replace('~~~', ', ',$alert_category) ."</p>
                            <p><b>Location: </b>". str_replace('~~~', ', ',$alert_location) ."</p>
                            <p><b>Target: </b>". str_replace('~~~', ', ',$alert_target) ."</p>
                            <p><b>Subject: </b>". $alert_subject ."</p>
                            <p><b>Description: </b>". $alert_description ."</p>
                            <p><b>Deadline: </b>". $alert_deadline ."</p>
                            <!-- <a href=\"#\" rel=\"modal:close\">Close</a> -->
                          </div>
                          <!-- Link to open the modal -->
                          <a class='button decide' href='#alert".$counter."' rel='modal:open'>".$this->language['alert_info']."</a>";
        return $html;
    }

    public function decide($counter, $id, $status) {
        if (empty($status)) {
            $html = "<button id='Accepted-". $id ."' class='button decide decide_case decide-".$counter."'>".$this->language['accept']."</button>";
            $html .= "<button id='Rejected-". $id ."' class='button decide decide_case decide-".$counter."'>".$this->language['reject']."</button>";
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
            // run algorithm here and send mails to everyone
            $this->static_matching($decision_entry);

            $this->update_entry($decision_result, $decision_entry);
            $this->SendMail($decision_entry, 'Approved');
            echo $this->transition($decision_result, $decision_entry);
        }

    }

    public function transition($res, $id) {
        $html = "";
        if ($res === 'Rejected') {
            $html .= "<button id='Closed-". $id ."' class='button decide close_case' disabled>".$this->language['rejected']."</button>";
        }
        else if ($res === 'Closed') {
            $html .= "<button id='Closed-". $id ."' class='button decide close_case' disabled>".$this->language['closed']."</button>";
        }
        else {
            $html .= "<button id='Closed-". $id ."' class='button decide close_case'>".$this->language['close']."</button>";
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
        $flp_id = $wpdb->get_results( "SELECT flp_id, alert_subject FROM {$wpdb->prefix}alert WHERE alert_id='$UserID'", OBJECT );
        $id = $flp_id[0]->flp_id;
        $subject = $flp_id[0]->alert_subject;
        $Email = $wpdb->get_results( "SELECT flp_email, flp_locale FROM {$wpdb->prefix}arena WHERE flp_id = '$id'", OBJECT );
        $language = $Email[0]->flp_locale;
        $Email = $Email[0]->flp_email;
        $plugin_path = plugin_dir_path( dirname(__FILE__, 4));
        $string_file = json_decode(file_get_contents($plugin_path . "assets/base/" . $language . "/alert_strings.json"), true);

        if ($State == 'Closed') {
            wp_mail( "$Email", $string_file['arena_case_module'], $string_file['your_case']." ".$State.".", array('Content-Type: text/html; charset=UTF-8'));
        }
        elseif ($State = 'Approved') {
            wp_mail( "$Email", $string_file['arena_case_module'], $string_file['the_alert'] . $subject . $string_file['your_case_approved'], array('Content-Type: text/html; charset=UTF-8'));
        }
    }


    // this algorithm will run upon case approval from Moderator
    // algorithm will return all alert ids which map to flp - this is done statically from json mapping
    public function static_matching($alert) {
        global $wpdb;
        $final_list = [];

        $alert = $wpdb->get_results( "SELECT alert_id, alert_category, alert_location, alert_target, alert_country, alert_city, flp_id FROM {$wpdb->prefix}alert WHERE alert_id='$alert'", OBJECT );
        $country = $alert[0]->alert_country;
        $city = $alert[0]->alert_city;
        $flp_of_alert = $alert[0]->flp_id;
        foreach ($alert as $data) {
            $alert = $this->alert_mapping($data);
        }

        $flps = $wpdb->get_results( "SELECT flp_id, flp_experience_with_radicalisation, flp_working_with, flp_area_of_expertise, flp_visibility_level, flp_city, flp_country FROM {$wpdb->prefix}arena WHERE flp_status='Accepted' AND flp_id!='$flp_of_alert' AND flp_country='$country'", OBJECT );
        foreach ($flps as $flp) {
            if (empty($flp->flp_visibility_level)) {
                $final_list = $final_list + $this->flp_mapping($flp, $alert);
            }
            elseif (!empty($flp->flp_visibility_level)) {
                if ($city === $flp->flp_city) {
                    $final_list = $final_list + $this->flp_mapping($flp, $alert);
                }
            }
        }

        // remove elements with 0 value -- no match in this case
        foreach ($final_list as $item => $value) {
            if ($value == 0) {
                unset($final_list[$item]);
            }
        }

        // sort array in descending order
        arsort($final_list);

//        return array_keys($final_list);

        $this->mail_to_matching_flps(array_keys($final_list), $data->alert_id);
    }

    public function flp_mapping($data, $alert) {
        $ident_list = [];
        $plugin_path = plugin_dir_path( dirname(__FILE__, 4));
        $json_data = json_decode(file_get_contents($plugin_path . "assets/base/flp_map.json"), true);

        $ident_radicalisation = explode("~~~", $data->flp_experience_with_radicalisation);
        $ident_working = explode("~~~", $data->flp_working_with);
        $ident_expertise = explode("~~~", $data->flp_area_of_expertise);


        $metadata = array_merge($ident_radicalisation, $ident_working, $ident_expertise);

        foreach ($metadata as $iteration) {
            $iteration = trim($iteration);
            foreach ($json_data as $item => $value) {
                if ($item === $iteration) {
                    array_push($ident_list, $value);
                }
            }
        }

        // Remove duplicates from flp list
        $imploding_in_string = implode(',', $ident_list);
        $explodng_in_array = explode(',', $imploding_in_string);
        $flps = array_unique($explodng_in_array);
        $match_count = $this->alert_flp($alert, $flps);
        $ascending_match_list[$data->flp_id] = $match_count;

        return $ascending_match_list;
    }

    public function alert_mapping($data) {
        $ident_list = [];
        $plugin_path = plugin_dir_path( dirname(__FILE__, 4));
        $json_data = json_decode(file_get_contents($plugin_path . "assets/base/alert_map.json"), true);

        $ident_category = explode("~~~", $data->alert_category);
        $ident_location = explode("~~~", $data->alert_location);
        $ident_target = explode("~~~", $data->alert_target);

        $metadata = array_merge($ident_category, $ident_location, $ident_target);

        foreach ($metadata as $iteration) {
            $iteration = trim($iteration);
            foreach ($json_data as $item => $value) {
                if ($item === $iteration) {
                    array_push($ident_list, $value);
                }
            }
        }
        return $ident_list;
    }

    public function alert_flp($alert_list, $flp_list) {
        $a = $alert_list;
        $b = $flp_list;
        $count = 0;

        foreach ($flp_list as $flp) {
            $flp = explode(',', $flp);
            foreach ($alert_list as $alert) {
                if (in_array($alert, $flp)) {
                    $count++;
                    break;
                }
            }
        }
        return $count;
    }

    public function mail_to_matching_flps($flp_list, $alert) {
        // $alert is passed here just in case they want information regarding the alert in the email.
        global $wpdb;
        foreach ($flp_list as $flps) {
            $flp = $wpdb->get_results( "SELECT flp_email, flp_locale FROM {$wpdb->prefix}arena WHERE flp_id='$flps'", OBJECT );
            $language = $flp[0]->flp_locale;
            $email = $flp[0]->flp_email;

            $plugin_path = plugin_dir_path( dirname(__FILE__, 4));
            $string_file = json_decode(file_get_contents($plugin_path . "assets/base/" . $language . "/alert_strings.json"), true);

            wp_mail( "$email", $string_file['alert_invitation'], $string_file['matching_invitation'], array('Content-Type: text/html; charset=UTF-8'));
        }
    }

}