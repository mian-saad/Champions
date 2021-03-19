<?php

namespace Contain\Display\View\FlpSection;


class ProfileUpdated {

    public function update($data, $email) {

        // If title more than one implode in one array and if other title exists concatenate in the same array
        if (count($data['flp_title']) > 1) {
            $data['flp_title'] = implode(',', $data['flp_title']);
        }
        if (!empty($data['other_title'])) {
            $data['flp_title'] = $data['flp_title'] .','. $data['other_title'];
        }
        unset($data['other_title']);

        // If skills more than one implode in one array and if other skills exists concatenate in the same array
        if (count($data['flp_experience_with_radicalisation']) > 1) {
            $data['flp_experience_with_radicalisation'] = implode(',', $data['flp_experience_with_radicalisation']);
        }
        if (!empty($data['other_skills'])) {
            $data['flp_experience_with_radicalisation'] = $data['flp_experience_with_radicalisation'] .','. $data['other_skills'];
        }
        unset($data['other_skills']);

        // If skills more than one implode in one array and if other skills exists concatenate in the same array
//        if (count($data['flp_skills']) > 1) {
//            $data['flp_skills'] = implode(',', $data['flp_skills']);
//        }
//        if (!empty($data['other_skills'])) {
//            $data['flp_skills'] = $data['flp_skills'] .','. $data['other_skills'];
//        }
//        unset($data['other_skills']);

        // If skills more than one implode in one array and if other skills exists concatenate in the same array
        if (count($data['flp_area_of_expertise']) > 1) {
            $data['flp_area_of_expertise'] = implode(',', $data['flp_area_of_expertise']);
        }
        if (!empty($data['other_skills'])) {
            $data['flp_area_of_expertise'] = $data['flp_area_of_expertise'] .','. $data['other_skills'];
        }
        unset($data['other_skills']);

        // If skills more than one implode in one array and if other skills exists concatenate in the same array
        if (count($data['flp_working_with']) > 1) {
            $data['flp_working_with'] = implode(',', $data['flp_working_with']);
        }
        if (!empty($data['other_skills'])) {
            $data['flp_working_with'] = $data['flp_working_with'] .','. $data['other_skills'];
        }
        unset($data['other_skills']);

        global $wpdb;
        $wpdb->update("wp_arena", $data, array('flp_email' => $email));
        $this->render();
    }

    public function render() {
        echo "<p id='notify2' style='display: inline; color: #285b8f; font-size: 16px;'>Profile Updated</p>";
    }

}