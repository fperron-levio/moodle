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
 * Set password form definition.
 *
 * @package    core
 * @subpackage auth
 * @copyright  2006 Petr Skoda {@link http://skodak.org}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir.'/formslib.php');
require_once($CFG->dirroot.'/user/lib.php');
require_once('lib.php');
require_once($CFG->dirroot . '/auth/rsg/classes/RSG_Form.php');

/**
 * Set forgotten password form definition.
 *
 * @package    core
 * @subpackage auth
 * @copyright  2006 Petr Skoda {@link http://skodak.org}
 * @copyright  2013 Peter Bulmer
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class login_set_password_form extends RSG_Form {

    /**
     * Define the set password form.
     */
    public function definition() {
        global $USER, $CFG;
        // Prepare a string showing whether the site wants login password autocompletion to be available to user.
        if (empty($CFG->loginpasswordautocomplete)) {
            $autocomplete = 'autocomplete="on"';
            $autocompletepassword = 'autocomplete="on" onCopy="return false" onPaste="return false"';
        } else {
            $autocomplete = '';
            $autocompletepassword = 'onCopy="return false" onPaste="return false"';
        }

        $mform = $this->_form;
        $mform->setDisableShortforms(true);
        $mform->addElement('header', 'setpassword', get_string('setpassword'), '');

        // Include the username in the form so browsers will recognise that a password is being set.
        $mform->addElement('text', 'username', '', 'style="display: none;" ' . $autocomplete);
        $mform->setType('username', PARAM_RAW);
        // Token gives authority to change password.
        $mform->addElement('hidden', 'token', '');
        $mform->setType('token', PARAM_ALPHANUM);

        // Visible elements.
        $mform->addElement('static', 'username2', get_string('username'));

        if (!empty($CFG->passwordpolicy)) {
            $mform->addElement('static', 'passwordpolicyinfo', '', print_password_policy());
        }
        $mform->addElement('password', 'password', get_string('newpassword'), $autocompletepassword);
        $mform->addRule('password', get_string('mustbefilled'), 'required', null, 'client');
        $mform->setType('password', PARAM_RAW);

        $strpasswordagain = get_string('contact_form_rsg_form_passwordagain', 'mod_rsg');
        $mform->addElement('password', 'password2', $strpasswordagain, $autocompletepassword);
        $mform->addRule('password2', get_string('mustbefilled'), 'required', null, 'client');
        $mform->setType('password2', PARAM_RAW);

        //Impossible de loader le css theme à cause du token donc il faut passer avec un bouton image
        $mform->addElement('html', '<input src="/theme/cleanrsg/pix/btn_enregistrer.png" value="submit" type="image"></div>');
        //$this->add_action_buttons(false);
    }

    /**
     * Perform extra password change validation.
     * @param array $data submitted form fields.
     * @param array $files submitted with the form.
     * @return array errors occuring during validation.
     */
    public function validation($data, $files) {
        global $USER;
        $errors = parent::validation($data, $files);

        // Extend validation for any form extensions from plugins.
        $errors = array_merge($errors, core_login_validate_extend_set_password_form($data, $user));

        // Ignore submitted username.
        if ($data['password'] !== $data['password2']) {
            $errors['password'] = get_string('contact_form_rsgpassworddontmatch','mod_rsg');
            $errors['password2'] = get_string('contact_form_rsgpassworddontmatch','mod_rsg');
            return $errors;
        }

        $errmsg = ''; // Prevents eclipse warnings.
        if (!check_password_policy($data['password'], $errmsg, $user)) {
            $errors['password'] = $errmsg;
            $errors['password2'] = $errmsg;
            return $errors;
        }

        return $errors;
    }
}
