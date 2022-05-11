<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Forgot password page.
 *
 * @package    core
 * @subpackage auth
 * @copyright  2006 Petr Skoda {@link http://skodak.org}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/auth/rsg/classes/RSG_Form.php');

/**
 * Reset forgotten password form definition.
 *
 * @package    core
 * @subpackage auth
 * @copyright  2006 Petr Skoda {@link http://skodak.org}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
 
class login_forgot_numeroidentification_form extends RSG_Form {

    /**
     * Define the forgot password form.
     */
	 
	 
    function definition() {
        $mform    = $this->_form;
        $mform->setDisableShortforms(true);


        $mform->addElement('html', '<div class="rsg_form_info alert alert-info">' . get_string('contact_form_rsg_form_emailrequired_note','mod_rsg') . '</div>');

        $mform->addElement('text', 'email', get_string('contact_form_rsg_form_emailrequired','mod_rsg'));
        $mform->setType('email', PARAM_RAW);
        $mform->addRule('email', get_string('mustbefilled'), 'required', null, 'client');

        $submitlabel = get_string('search');
        $mform->addElement('submit', 'submitbuttonsearch', $submitlabel);
    }

    /**
     * Validate user input from the forgot password form.
     * @param array $data array of submitted form fields.
     * @param array $files submitted with the form.
     * @return array errors occuring during validation.
     */
    function validation($data, $files) {
        global $CFG, $DB;

        $errors = parent::validation($data, $files);

        if ((!empty($data['username']) and !empty($data['email'])) or (empty($data['username']) and empty($data['email']))) {
            $errors['username'] = get_string('usernameoremail');
            $errors['email']    = get_string('missingemail');

        } else if (!empty($data['email'])) {
            if (!validate_email($data['email'])) {
                $errors['email'] = get_string('invalidemail');

            } else if ($DB->count_records('user', array('email'=>$data['email'])) > 1) {
                $errors['email'] = get_string('forgottenduplicate');

            } else {
                if ($user = get_complete_user_data('email', $data['email'])) {
                    if (empty($user->confirmed)) {
                        $errors['email'] = get_string('confirmednot');
                    }
                }
                if (!$user and empty($CFG->protectusernames)) {
                    $errors['email'] = get_string('contact_form_rsg_form_emailrequired_attention','mod_rsg');
                }
                if (!$user) {
                    $errors['email'] = get_string('contact_form_rsg_form_emailrequired_attention','mod_rsg');
                }
            }
        } 
        return $errors;
    }

}
