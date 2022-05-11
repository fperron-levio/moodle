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

require_once($CFG->dirroot . '/auth/rsg/classes/RSG_Form.php');
//require_once($CFG->libdir.'/formslib.php');

/* todo: Plusieurs textes à sortir */

class rsg_signup_form extends RSG_Form {

    function definition() {
        global $USER, $CFG, $PAGE, $OUTPUT;

        /* Utilise "false" pour dom ready, doit passer avant code du formulaire. */
        $regiondata = $this->get_adm_region_data();

        $PAGE->requires->js_init_call('M.form.init_view_page', array(array('officedata' => ($this->get_office_data($regiondata)))), false);

        $PAGE->set_pagelayout('front');
        $mform = $this->_form;

        $mform->addElement('html', '<div class="rsg_form_title">' . get_string('auth_rsgsignupformtitle', 'auth_rsg') . '</div>');

        $mform->addElement('html', '<div class="rsg_form_info alert alert-info">' . get_string('auth_rsg_inscription_intro', 'auth_rsg') . '</div>');

        $mform->addElement('text', 'lastname', get_string('lastname'), 'maxlength="100" size="30" style="width:302px;"');
        $mform->setType('lastname', PARAM_TEXT);
        $mform->addRule('lastname', get_string('mustbefilled'), 'required', null, 'server');

        $firstname = $mform->addElement('text', 'firstname', get_string('firstname'), 'maxlength="100" size="30" class="control" style="width:302px;" ');
        $mform->setType('firstname', PARAM_TEXT);
        $mform->addRule('firstname', get_string('mustbefilled'), 'required', null, 'server');

        $phone = $mform->addElement('text', 'phone1', get_string('phone'), 'placeholder="999 999-9999" maxlength="100" size="30" class="control" style="width:302px;" ');
        $mform->setType('phone1', PARAM_TEXT);
        $mform->addRule('phone1', get_string('mustbefilled'), 'required', null, 'server');
        $mform->addRule('phone1', get_string('auth_rsgphone_rule_must_have_format', 'auth_rsg'), 'regex', '/^([0-9]{3})?([ .-]?)([0-9]{3})?([ .-]?)([0-9]{4})$/', 'server');

        $mform->addElement('text', 'email', get_string('email'), 'maxlength="100" size="25" style="width:302px;" onCopy="return false" onPaste="return false"');
        $mform->setType('email', PARAM_NOTAGS);
        $mform->addRule('email', get_string('mustbefilled'), 'required', null, 'server');
        $mform->addRule('email', get_string('auth_rsgemail_rule_invalid', 'auth_rsg'), 'email', null, 'server');
        /* NOTE: Pas de rule pour 'email'. Voir fonction 'validation' plus bas pour traitement spécial email. */
        $mform->addElement('text', 'confirmemail', get_string('auth_confirmemail', 'auth_rsg'), 'maxlength="100" size="25" style="width:302px;" onCopy="return false" onPaste="return false"');
        $mform->setType('confirmemail', PARAM_NOTAGS);
        $mform->addRule('confirmemail', get_string('mustbefilled'), 'required', null, 'server');
        $mform->addRule('confirmemail', get_string('auth_rsgemail_rule_invalid', 'auth_rsg'), 'email', null, 'server');

        //$statusdata= $this->get_statusdata(); //TODO cette ligne devra être ajouté à la phase 2 pour avoir la liste au complet
        $statusdata[1] = get_string('auth_rsg_form_status_1', 'auth_rsg'); //TODO cette ligne devra être enlevée à la phase 2 pour ne pas mettre ce choix par défaut!

        $mform->addElement('select', 'rsgstatus', get_string('auth_rsgstatus', 'auth_rsg'), $statusdata, array('style' => 'width:316px;'));
        $mform->setType('rsgstatus', PARAM_TEXT);
        // Tâche #3660 >
        $helpicon_inscription_rsgstatus = new help_icon('inscription_rsgstatus', 'rsg');
        $helpbuton_inscription_rsgstatus = $OUTPUT->render($helpicon_inscription_rsgstatus);
        $mform->addElement('html', $helpbuton_inscription_rsgstatus);
        // < Tâche #3660
        //$mform->setDefaults(array('rsgstatus' => '0'));
        $mform->addRule('rsgstatus', get_string('auth_rsgstatus_rule_missing', 'auth_rsg'), 'regex', '/^[1-9]{1}[0-9]*$/', 'server');
        $mform->addRule('rsgstatus', get_string('auth_rsgstatus_rule_missing', 'auth_rsg'), 'required', null, 'server'); /* get_string('missingrsgstatus')*/

        $mform->addElement('select', 'rsgregion', get_string('auth_rsgregion', 'auth_rsg'), $regiondata, array('style' => 'width:316px;'));
        $mform->setDefaults(array('rsgregion' => '0'));
        $mform->setType('rsgregion', PARAM_TEXT);

        $mform->addRule('rsgregion', get_string('auth_rsgoffice_rule_missing', 'auth_rsg'), 'regex', '/^[1-9]{1}[0-9]*$/', 'server');
        $mform->addRule('rsgregion', get_string('auth_rsgoffice_rule_missing', 'auth_rsg'), 'required', null, 'server');

        /* Attention: doit être absolument hierselect pour fonctionner (data assigné en javascript). */
        $mform->addElement('hierselect', 'rsgoffice', get_string('auth_rsgoffice', 'auth_rsg'), array('id' => 'rsgoffice', 'style' => 'width:316px;'));
        $mform->setType('rsgoffice', PARAM_TEXT);
        $mform->setDefaults(array('rsgoffice' => '0'));
        $mform->addGroupRule('rsgoffice', get_string('auth_rsgoffice_rule_missing2', 'auth_rsg'), 'regex', '/^[1-9]{1}[0-9]*$/', 'server');
        $mform->addRule('rsgoffice', get_string('auth_rsgoffice_rule_missing2', 'auth_rsg'), 'required', null, 'server');

        //Icone help ajouté après le champ mot de passe descend toujours en bas la ligne pour contrer ce bougue on ajoute le tag table
        $mform->addElement('html', '<table><tr><td>');
        $mform->addElement('text', 'numeroidentification', get_string('auth_numeroidentification', 'auth_rsg'), 'maxlength="100" size="30" style="width:302px;"');
        $mform->setType('numeroidentification', PARAM_TEXT);
        $mform->addElement('html', '</td><td valign="top">');
        // Tâche #3659 >
        $helpicon_numeroidentification = new help_icon('numeroidentification', 'rsg');
        $helpbuton_numeroidentification = $OUTPUT->render($helpicon_numeroidentification);
        $mform->addElement('html', $helpbuton_numeroidentification);
        $mform->addElement('html', '</td></tr></table>');

        // < Tâche #3659
        $mform->addRule('numeroidentification', get_string('auth_rsgnumeroidentification_rule_missing', 'auth_rsg'), 'required', null, 'server');

        $mform->addElement('html', '<div class="rsg_form_password_section_start"></div>');

        //Icone help ajouté après le champ mot de passe descend toujours en bas la ligne pour contrer ce bougue on ajoute le tag table
        $mform->addElement('html', '<table><tr><td>');
        $mform->addElement('password', 'password', get_string('auth_rsgpassword', 'auth_rsg'), 'maxlength="100" style="width:309px;height:25px;border:1px solid #c1bcbc;"');
        $mform->setType('password', PARAM_RAW);
        $mform->addElement('html', '</td><td valign="top">');
        $helpicon_password = new help_icon('inscription_password', 'rsg');
        $helpbuton_password = $OUTPUT->render($helpicon_password);
        $mform->addElement('html', $helpbuton_password);
        $mform->addElement('html', '</td></tr></table>');
        $mform->addRule('password', get_string('missingpassword'), 'required', null, 'server');
        $mform->addElement('password', 'confirmpassword', get_string('auth_rsgconfpass', 'auth_rsg'), 'maxlength="100" style="width:309px;height:25px;border:1px solid #c1bcbc;" ');
        $mform->setType('confirmpassword', PARAM_RAW);
        $mform->addRule('confirmpassword', get_string('auth_rsgemptyconfpass', 'auth_rsg'), 'required', null, 'server');

        $mform->addElement('html', '<div class="rsg_form_password_section_end"></div>');
        // À changer 
        $mform->addRule(array('password', 'confirmpassword'), get_string('auth_rsgpassworddontmatch', 'auth_rsg'), 'compare', 'eq');

        if ($this->signup_captcha_enabled()) {
            $mform->addElement('recaptcha', 'recaptcha_element', get_string('recaptcha', 'auth'), array('https' => $CFG->loginhttps));
            $mform->addHelpButton('recaptcha_element', 'recaptcha', 'auth');
        }

        //  todo: il faudra voir avec Alain et/ou Jenny si on met un texte juste avant la case à cocher.
        // $mform->addElement('header', 'terms', get_string('auth_rsgsignupformpolicytitle', 'auth_rsg'), '');
        // $mform->setExpanded('terms');
        //$mform->addElement('static', 'policylink', '', '<a href="' . $CFG->sitepolicy . '" onclick="this.target=\'_blank\'">' . get_String('policyagreementclick') . '</a>');
        $mform->addElement('checkbox', 'policyagreed', get_string('auth_rsgsignupformpolicyagreed', 'auth_rsg'));
        $mform->addRule('policyagreed', get_string('auth_rsgsignupformpolicyagreederror', 'auth_rsg'), 'required', null, 'server');
        $mform->addElement('html',  get_string('auth_inscriptioninfo', 'auth_rsg') );
        //$mform->addElement('html', '<input id="submitbutton" name="submitbutton" value="' . get_string('auth_rsgsignupformsubmit', 'auth_rsg') . '" type="submit">');
        $this->add_action_buttons(FALSE,  get_string('auth_rsgsignupformsubmit', 'auth_rsg'));
        $mform->addElement('html', '<script src="' . $CFG->wwwroot . '/auth/rsg/form/form1.js"></script>');
    }

    function validation($data, $files) {
        global $CFG, $DB;

        $errors = array();

        if (!validate_email($data['email'])) {
            $errors['email'] = get_string('auth_rsgemail_rule_invalid', 'auth_rsg');
        } else {
            require_once $CFG->dirroot . '/auth/rsg/classes/RSGUser.php';
            if (auth\rsg\RSGUser::existsUser($data['email'])) {
                $errors['email'] = get_string('emailexists') . ' <a href="/login">' . get_string('auth_gobacktologin', 'auth_rsg') . '</a>';
            }
        }
        // Validation en double de validate_email?
        if (!isset($errors['email'])) {
            if ($err = email_is_not_allowed($data['email'])) {
                $errors['email'] = $err;
            }
        }

        // Validation pour s'assurer que les 2 courriels sont identiques
        if ($data['confirmemail'] != $data['email']) {
            $errors['confirmemail'] = get_string('auth_rsgemailsdontmatch', 'auth_rsg');
        }

        //TODO TEMPORAIRE - Validation pour s'assurer que l'utilisateur est bien une RSG représentée, car abonnement pour utilisateurs payants ne sera dispo qu'à la Phase 2
        if ($data['rsgstatus'] != 1) {
            $errors['rsgstatus'] = get_string('auth_rsgnotavailable', 'auth_rsg') . ' <a href="/mod/rsg/faq/">' . get_string('auth_rsgmoredetails', 'auth_rsg') . '</a>';
        }

        $errmsg = '';
        if (!check_password_policy($data['password'], $errmsg)) {
            // Traitement particulier, change en liste pour correspondre au design du doc word "alpha test"...
            $errmsg = "<ul class='form_error_list'>" . str_replace('div','li',$errmsg) . "</ul>";
            $errors['password'] = get_string('auth_rsgpassword_rule_must_have', 'auth_rsg') . $errmsg . "« Assurez-vous que la touche Verr. Maj./Caps Lock n’est pas activée. »";
        }

       /* $errmsg02 = '';
        if (!check_password_policy($data['confirmpassword'], $errmsg02)) {
            // Traitement particulier, change en liste pour correspondre au design du doc word "alpha test"...
            $errmsg02 = "<ul class='form_error_list'>" . str_replace('div','li',$errmsg02) . "</ul>";
            $errors['confirmpassword'] = get_string('auth_rsgpassword_rule_must_have', 'auth_rsg') . $errmsg02 . "« Assurez-vous que la touche Verr. Maj./Caps Lock n’est pas activée. »";
        } */

// todo: Revalider, utilisé?        
        if ($this->signup_captcha_enabled()) {
            $recaptcha_element = $this->_form->getElement('recaptcha_element');
            if (!empty($this->_form->_submitValues['recaptcha_challenge_field'])) {
                $challenge_field = $this->_form->_submitValues['recaptcha_challenge_field'];
                $response_field = $this->_form->_submitValues['recaptcha_response_field'];
                if (true !== ($result = $recaptcha_element->verify($challenge_field, $response_field))) {
                    $errors['recaptcha'] = $result;
                }
            } else {
                $errors['recaptcha'] = get_string('missingrecaptchachallengefield');
            }
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
