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

class rsg_myprofile_form extends RSG_Form {

    public $rsg_user_inscription_details;
    public $rsg_user_inscription_coordoffice;
    public $rsg_user_inscription_region;

    function definition() {
        global $USER, $CFG, $PAGE, $OUTPUT, $DB;
        
        $this->rsg_user_inscription_details               = $DB->get_record(RSG_INSCRIPTION, array('userid' => $USER->id));
        $this->rsg_user_inscription_coordoffice           = $DB->get_record(RSG_COORDOFFICE, array('id' => $this->rsg_user_inscription_details->coordofficeid));
        $this->rsg_user_inscription_region                = $DB->get_record(RSG_ADMINREGION, array('id' => $this->rsg_user_inscription_coordoffice->regionid));
        
        // Utilise "false" pour dom ready, doit passer avant code du formulaire.
        $PAGE->requires->js_init_call('M.form.init_view_page', array(array('officelabel' => (get_string('auth_rsgoffice', 'auth_rsg')))), false);
        
        $PAGE->set_pagelayout('front');


        $mform = $this->_form;

        // #3869
        // visible elements
        $mform->addElement('static', 'username', get_string('numeroidentification','mod_rsg'), $USER->username);
        // visible elements
        $mform->addElement('static', 'username', get_string('lastname'), $USER->lastname);
        // visible elements
        $mform->addElement('static', 'username', get_string('firstname'), $USER->firstname);
        
        setlocale(LC_TIME, "fr_CA");
        $date_inscription=utf8_encode(strftime("%d %B %Y", $USER->timecreated));
        $mform->addElement('static', 'date_inscription', get_string('inscription_date','mod_rsg'), $date_inscription);
        
        $phone1 = (!empty($USER->phone1)) ? $USER->phone1 : 'Aucun';
        // #3585
        $phone1 = preg_replace('/[^0-9]/', '', $phone1);
        if(strlen($phone1)==10) {
            $phone1 = preg_replace('/(\d{3})(\d{3})(\d{4})/', '$1 $2-$3', $phone1);
        }
	
        $phoneAttributes = array(
            'placeholder' => '999 999-9999',
            'maxlength' => '100',
            'size' => '30',
            'class' => 'control',
            'style' => 'width:302px;'
        );
		$phone1_element = $mform->addElement('text', 'phone1', get_string('contact_form_rsg_form_phone','mod_rsg'), $phoneAttributes);
        $phone1_element->setValue($phone1);
		$mform->setType('phone1', PARAM_TEXT);
		$mform->addRule('phone1', get_string('contact_form_rsgphone_rule_must_have_format', 'mod_rsg'), 'regex', '/^([0-9]{3})[ ]?([0-9]{3})[-]?([0-9]{4})$/', 'client');
		//$mform->addRule('phone1', get_string('contact_form_rsgphone_rule_must_have_format', 'mod_rsg'), 'required', null, 'client');
        
        // STATUS
        //$statusdata= $this->get_statusdata(); //TODO cette ligne devra être ajouté à la phase 2 pour avoir la liste au complet
        $statusdata[1] = get_string('auth_rsg_form_status_1', 'auth_rsg'); //TODO cette ligne devra être enlevée à la phase 2 pour ne pas mettre ce choix par défaut!

        $mform->addElement('select', 'rsgstatus', get_string('auth_rsgstatus', 'auth_rsg'), $statusdata, array('style' => 'width:316px;'));
        $mform->setType('rsgstatus', PARAM_INT);
        // Tâche #3660 >
        $helpicon_inscription_rsgstatus = new help_icon('inscription_rsgstatus', 'rsg');
        $helpbuton_inscription_rsgstatus = $OUTPUT->render($helpicon_inscription_rsgstatus);
        $mform->addElement('html', $helpbuton_inscription_rsgstatus);
        // < Tâche #3660
        //$mform->setDefaults(array('rsgstatus' => '0'));
        $mform->addRule('rsgstatus', get_string('auth_rsgstatus_rule_missing', 'auth_rsg'), 'regex', '/^[1-9]{1}[0-9]*$/', 'server');
        //$mform->addRule('rsgstatus', get_string('auth_rsgstatus_rule_missing', 'auth_rsg'), 'required', null, 'server'); /* get_string('missingrsgstatus')*/

        // RÉGION & BUREAU COORDONNATEUR
        // du javascript a été ajouté dans form1.js pour accommoder le visuel voulu par le client
        $regiondata = $this->rsg_user_inscription_region->regionname;
        $officedata = $this->rsg_user_inscription_coordoffice->officename;
        if ($regiondata != '') {
          $mform->addElement('static', 'rsg_region', get_string('auth_rsgregion','auth_rsg'), $regiondata);
          $mform->addElement('static', 'rsg_region_office', get_string('auth_rsgoffice','auth_rsg'), $officedata);
        }

        /*
        $sel =& $mform->addElement('hierselect', 'rsg_region_office', get_string('auth_rsgregion', 'auth_rsg'), array('style' => 'width:316px;'), null);
        $regiondata = $this->get_adm_region_data();
        $officedata = $this->get_office_data($regiondata);
        $sel->setOptions(array($regiondata, $officedata));
       $rsg_region_office_defaults = array(0,0);
        if(
            (
                $this->rsg_user_inscription_details->coordofficeid
                &&
                $this->rsg_user_inscription_coordoffice->regionid
            )
            &&
            (
                $this->rsg_user_inscription_details->coordofficeid > 0
                &&
                $this->rsg_user_inscription_coordoffice->regionid > 0
            )
        ){
            $rsg_region_office_defaults = array($this->rsg_user_inscription_coordoffice->regionid, $this->rsg_user_inscription_details->coordofficeid);
        }
        $mform->setDefaults(array('rsg_region_office' => $rsg_region_office_defaults));
        $mform->addRule('rsg_region_office', get_string('auth_rsgoffice_rule_missing', 'auth_rsg'), 'callback', 'rsg_region_office_validation', 'server');
        $mform->addRule('rsg_region_office', get_string('auth_rsgoffice_rule_missing', 'auth_rsg'), 'required', null, 'server');
        function rsg_region_office_validation($data){
            // On pourrait valider plus durement en vérifiant la validité des paires de $data
            if(is_array($data)){
                $is_all_ok = '';
                foreach($data as $id){
                    if(preg_match('/^[1-9]{1}[0-9]*$/',$id) !== 1){
                        $is_all_ok = 'nope';
                    }
                }
                if(empty($is_all_ok)){
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }
      */

        if ($this->signup_captcha_enabled()) {
            $mform->addElement('recaptcha', 'recaptcha_element', get_string('recaptcha', 'auth'), array('https' => $CFG->loginhttps));
            $mform->addHelpButton('recaptcha_element', 'recaptcha', 'auth');
        }
        
		$this->add_action_buttons(FALSE,  get_string('rsgmyprofileformsubmit', 'mod_rsg'));
		
		$mform->addElement('html', '<script src="' . $CFG->wwwroot . '/mod/rsg/monprofil/form1.js"></script>');
    }

    function validation($data, $files) {
        global $CFG, $DB;

        $errors = array();
    
        //TODO TEMPORAIRE - Validation pour s'assurer que l'utilisateur est bien une RSG représentée, car abonnement pour utilisateurs payants ne sera dispo qu'à la Phase 2
        if ($data['rsgstatus'] != 1) {
            $errors['rsgstatus'] = get_string('auth_rsgnotavailable', 'auth_rsg') . ' <a href="/mod/rsg/faq/">' . get_string('auth_rsgmoredetails', 'auth_rsg') . '</a>';
        }

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
