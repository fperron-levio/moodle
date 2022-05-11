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
 * User change email form.
 *
 * @package    auth
 * @subpackage rsg
 * @copyright  2017 Andrei Boris
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/auth/rsg/classes/RSG_Form.php');
 
class rsg_myemail_form extends RSG_Form {

    function definition() {
        global $USER, $CFG, $PAGE, $DB;
    
        $mform3 = $this->_form;
        $mform3->addElement('html', '<div id="emailchange">');

        $mform3->addElement('text', 'old_courriel', get_string('old_courriel','mod_rsg'), 'maxlength="32" size="12"');
        $mform3->setType('old_courriel', PARAM_NOTAGS);
        $mform3->addRule('old_courriel', get_string('missing_courriel','mod_rsg'), 'required', null, 'client');
       
        $mform3->addElement('text', 'courriel', get_string('type_new_courriel','mod_rsg'), 'maxlength="32" size="12"');
        $mform3->setType('courriel', PARAM_NOTAGS);
        $mform3->addRule('courriel', get_string('missing_courriel','mod_rsg'), 'required', null, 'client');
        
        $mform3->addElement('text', 'confirmcourriel', get_string('retype_new_courriel','mod_rsg'), 'maxlength="32" size="12"');
        $mform3->setType('confirmcourriel', PARAM_NOTAGS);
        $mform3->addRule('confirmcourriel', get_string('missing_new_courriel','mod_rsg'), 'required', null, 'client');
        
        // buttons
        $this->add_action_buttons(FALSE,  get_string('rsgmyprofileformsubmit', 'mod_rsg'));
		$mform3->addElement('html', '</div>');
  
    }

    
    function validation($data, $files) {
        global $USER, $DB;
        $errors = parent::validation($data, $files);
             
        $current_user_email = $USER->email;
        
        // ignore submitted old email
        if ($USER->email != $data['old_courriel']) {
            $errors['old_courriel'] = get_string('wrong_old_courriel','mod_rsg');
            return $errors;
        }
        
        if ($data['courriel'] <> $data['confirmcourriel']) {
            $errors['courriel'] = get_string('wrong_courriels_match', 'mod_rsg');
            return $errors;
        }
        
        // We can not send emails to invalid addresses
        if (!validate_email($data['courriel'])) {
            $errors['courriel'] = get_string('wrong_courriel_content', 'mod_rsg');
            return $errors;
        }
        
        if ($current_user_email == $data['courriel']){
            $errors['courriel'] = get_string('same_courriel_content', 'mod_rsg');
            return $errors;
        }
        
        // invalide si cette adresse de courriel est déjà enregistré
        if($DB->record_exists('user', array('email' => $data['courriel']))) {
            $errors['courriel'] = get_string('wrong_courriel_exists', 'mod_rsg');
            return $errors;
        }
        
        return $errors;

    }

    /**
     * Returns whether or not the captcha element is enabled, and the admin settings fulfil its requirements.
     * @return bool
     */
    function signup_captcha_enabled() {
        global $CFG;
        return !empty($CFG->recaptchapublickey) && !empty($CFG->recaptchaprivatekey) && get_config('auth/email', 'recaptcha');
    }

}
