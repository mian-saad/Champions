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
                        if ($Data[$counter] -> flp_title != 'Moderator') {
                            $html .= $this->infoModal(
                                $Data[$counter] -> flp_title,
                                $Data[$counter] -> flp_first_name,
                                $Data[$counter] -> flp_last_name,
                                $Data[$counter] -> flp_email,
                                $Data[$counter] -> flp_country,
                                $Data[$counter] -> flp_skills,
                                $Data[$counter] -> flp_organisation,
                                $Data[$counter] -> flp_years_of_experience,
                                $Data[$counter] -> flp_city,
                                $Data[$counter] -> flp_visibility_level,
                                $Data[$counter] -> flp_experience_with_radicalisation,
                                $Data[$counter] -> flp_working_with,
                                $Data[$counter] -> flp_area_of_expertise,
                                $counter
                            );
                        }
                            $html .= " <p>" . $this->decide($Data[$counter] -> flp_title, $counter, $Data[$counter] -> flp_id, $Data[$counter] -> flp_status) . "</p> ";
                        $html .= " </td> ";
                    $html .= " </tr> ";
                }

        }
        $html .= " </table> ";
        $html .= " <button id='back' class='button'>".$this->language['back']."</button> ";
        echo $html;
    }

    public function infoModal($title, $fname, $lname, $email, $country,
                              $skills, $organisation, $years_experience,
                              $city, $visibility, $experience_radicalisation,
                              $working_with, $area_expertise, $counter  ) {

        if ($visibility == '') {$visibility = 'Not Set';}
        $html = "<div id='arena".$counter."' class='modal'>
                            <p><b>Title: </b>".$title."</p>
                            <p><b>First Name: </b>". $fname ."</p>
                            <p><b>Last Name: </b>". $lname ."</p>
                            <p><b>Email: </b>". $email ."</p>
                            <p><b>Country: </b>". $country ."</p>
                            <p><b>Organisation: </b>". $organisation ."</p>
                            <p><b>Years of Experience: </b>". $years_experience ."</p>
                            <p><b>City: </b>". $city ."</p>
                            <p><b>Visibility: </b>". $visibility ."</p>
                            <p><b>Experience With Radicalisation: </b>". $experience_radicalisation ."</p>
                            <p><b>Working With: </b>". $working_with ."</p>
                            <p><b>Area of Expertise: </b>". $area_expertise ."</p>
                            <!-- <a href=\"#\" rel=\"modal:close\">Close</a> -->
                          </div>
                          <!-- Link to open the modal -->
                          <a class='button decide' href='#arena".$counter."' rel='modal:open'>".$this->language['flp_info']."</a>";
        return $html;
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
        wp_mail( "$Email", $this->language['arena_login_module'], $this->language['flp_accepted'], array('Content-Type: text/html; charset=UTF-8'));
    }

}