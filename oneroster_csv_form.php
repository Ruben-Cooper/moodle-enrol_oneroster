<?php
require_once($CFG->libdir . '/formslib.php');

class oneroster_csv_form extends moodleform {
    protected function definition() {
        $mform = $this->_form;

        // file picker for CSV file upload
        $mform->addElement('filepicker', 'uploadedzip', 'Upload Zip File', null, array('accepted_types' => '.zip'));
        $mform->addRule('uploadedzip', null, 'required', null, 'client');

        // buttons
        $this->add_action_buttons(true, 'Upload');
    }
}
