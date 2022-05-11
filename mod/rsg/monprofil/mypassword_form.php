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
 * User sign-up form.
 *
 * @package    auth
 * @subpackage rsg
 * @copyright  2014 Nelson Moller
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/auth/rsg/classes/RSG_Form.php');
 
class rsg_mypassword_form extends RSG_Form {

    public $rsg_user_inscription_details;

    function definition() {
        global $USER, $CFG, $PAGE, $DB;
      
        $mform2 = $this->_form;
        $mform2->addElement('html', '<div id="passchange">');

        $mform2->addElement('passwordunmask', 'old_password', get_string('profile_old_password','mod_rsg'), 'maxlength="32" size="12"');
        $mform2->setType('old_password', PARAM_RAW);
        $mform2->addRule('old_password', get_string('profile_old_password_missing','mod_rsg'), 'required', null, 'client');   
        
        $mform2->addElement('passwordunmask', 'password', get_string('profile_new_password','mod_rsg'), 'maxlength="32" size="12"');
        $mform2->setType('password', PARAM_RAW);
        $mform2->addRule('password', get_string('profile_new_password_missing','mod_rsg'), 'required', null, 'client');
              
        $mform2->addElement('passwordunmask', 'confirmpassword', get_string('profile_confirm_new_password','mod_rsg'), 'maxlength="32" size="12"');
        $mform2->setType('confirmpassword', PARAM_RAW);
        $mform2->addRule('confirmpassword', get_string('profile_confirm_new_password_missing','mod_rsg'), 'required', null, 'client');
        
        // buttons
        $this->add_action_buttons(FALSE, get_string('rsgmyprofileformsubmit', 'mod_rsg'));
        
        $mform2->addElement('html', '</div>');
       
    }

    
    function validation($data, $files) {
        global $USER;
        $errors = parent::validation($data, $files);

        // ignore submitted username
        if (!$user = authenticate_user_login($USER->username, $data['old_password'], true)) {
            $errors['old_password'] = get_string('invalidlogin');
            return $errors;
        }

        if ($data['password'] <> $data['confirmpassword']) {
            $errors['password'] = get_string('profile_password_dont_match','mod_rsg');
            $errors['confirmpassword'] = get_string('profile_password_dont_match', 'mod_rsg');
            return $errors;
        }

        if ($data['old_password'] == $data['password']){
            $errors['old_password'] = get_string('mustchangepassword');
            $errors['password'] = get_string('mustchangepassword');
            return $errors;
        }
      
        // 3592 (même que dans auth/rsg/form/signup_form.php)
        $errmsg = '';
        if (!check_password_policy($data['password'], $errmsg)) {
            // Traitement particulier, change en liste pour correspondre au design du doc word "alpha test"...
            $errmsg = "<ul class='form_error_list'>" . str_replace('div','li',$errmsg) . "</ul>"; 
            $errors['password'] = get_string('auth_rsgpassword_rule_must_have', 'auth_rsg') . $errmsg;
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
