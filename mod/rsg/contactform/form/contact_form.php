<?php

/* Contact form: RSG */
/* todo: Add captcha: voir fonction plus bas. */
/* todo: Traitement de validation de email différent du signup? */
/* todo: /auth/rsg/classes/RSG_Form.php devrait bouger dans un dossier commun? */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/auth/rsg/classes/RSG_Form.php');

class rsg_contact_form extends RSG_Form {

    function definition() {
        global $CFG;

        $mform = $this->_form;

        $char_limit = '500'; /* Pourrait être envoyé en configs. */
        
        $mform->addElement('html', '<div class="rsg_form_title">' . get_string('contact_form_title','mod_rsg') . '</div>');
        $mform->addElement('html', '<h5 class="static-page-title">' . get_string('contact_form_email_title','mod_rsg') . '</h5>');
        $mform->addElement('html', '<div class="rsg_form_info alert alert-info soutien_technique soutien_technique2">' . get_string('contact_form_text','mod_rsg') . '</div>');
        
        /* todo: envoyer style dans css */
        $mform->addElement('html', '<div style="margin-bottom:20px;" class="rsg_form_space"></div>');
        
        // Si usager anonyme/externe/non-connecté.
        if (!isloggedin()) {
            $mform->addElement('text', 'name', get_string('lastname'), 'maxlength="100" size="30" style="width:302px;"');
            $mform->setType('name', PARAM_TEXT);
            $mform->addRule('name', get_string('contact_form_rule_mustbefilled','mod_rsg'), 'required', null, 'server');

            $mform->addElement('text', 'noidentification', get_string('contact_form_numero_identification', 'mod_rsg'), 'maxlength="100" size="30" style="width:302px;"');
            $mform->setType('noidentification', PARAM_TEXT);
            //$mform->addRule('noidentification', get_string('contact_form_rule_mustbefilled','mod_rsg'), 'required', null, 'client');
            
            // todo: string auth devrait être dans mod rsg.
            // todo: addHelpButton ne fait rien?
            // todo: bug "vert" dans le bas. Causé par Iframe ajouté par ccdaptcha...
            // Solution possible = seamless="seamless".
            // todo: Nelson dit que le recaptcha devrait avoir le look google?
            // $mform->addElement('recaptcha', 'recaptcha_element', get_string('recaptcha', 'auth'), array('https' => $CFG->loginhttps));
            // $mform->setType('recaptcha_element', PARAM_TEXT);
            // $mform->addRule('recaptcha_element', "todo:message recapcha requis", 'required', null, 'client');
            // $mform->addHelpButton('recaptcha_element', 'recaptcha', 'auth');
        }

        $mform->addElement('text', 'email', get_string('email'), 'maxlength="100" size="30" style="width:302px;" onCopy="return false" onPaste="return false"');
        $mform->setType('email', PARAM_NOTAGS);
        $mform->applyFilter('email', 'strtolower');
        $mform->addRule('email', get_string('contact_form_rule_mustbefilled','mod_rsg'), 'required', null, 'server');
        $mform->addRule('email', get_string('contact_form_email_format_rule_text', 'mod_rsg'), 'email', null, 'server');

        $mform->addElement('text', 'confirmemail', get_string('contact_form_confirmemail', 'mod_rsg'), 'maxlength="100" size="30" style="width:302px;" onCopy="return false" onPaste="return false"');
        $mform->setType('confirmemail', PARAM_NOTAGS);
        $mform->addRule('confirmemail', get_string('contact_form_rsgemail_rule_missing', 'mod_rsg'), 'required', null, 'server');
        $mform->addRule('confirmemail', get_string('contact_form_email_format_rule_text', 'mod_rsg'), 'email', null, 'server');
        //$mform->addRule( array('email', 'confirmemail'), get_string('contact_form_rsgemailsdontmatch', 'mod_rsg'), 'compare', 'eq', 'client');

        $mform->addElement('text', 'phone1', get_string('contact_form_rsg_form_phone','mod_rsg'), 'placeholder="999 999-9999" maxlength="100" size="30" class="control" style="width:302px;" ');
        $mform->setType('phone1', PARAM_TEXT);
        $mform->addRule('phone1', get_string('contact_form_rsgphone_rule_must_have_format', 'mod_rsg'), 'regex', '/^([0-9]{3})[ ]?([0-9]{3})[-]?([0-9]{4})$/', 'server');
        $mform->addRule('phone1', get_string('contact_form_rsgphone_rule_must_have_format', 'mod_rsg'), 'required', null, 'server');

        //$statusdata= $this->get_statusdata();
        $mform->addElement('select', 'rsgstatus', get_string('contact_form_rsgstatus', 'mod_rsg'), array(get_string('contact_form_rsg_form_status_1', 'mod_rsg')), array('style' => 'width:316px;'));
        $mform->setType('rsgstatus', PARAM_TEXT);
        $mform->setDefaults(array('rsgstatus' => get_string('contact_form_rsg_form_status_1', 'mod_rsg')));  //TODO cette ligne devra être enlevée à la phase 2 pour ne pas mettre ce choix par défaut!
        $mform->addRule('rsgstatus', get_string('contact_form_rule_mustbefilled', 'mod_rsg'), 'required', null, 'client');
        //$mform->addRule('rsgstatus', get_string('contact_form_rsgstatus_rule_missing', 'auth_rsg'), 'regex', '/^[1-9]{1}[0-9]*$/', 'server');
        //$mform->addRule('rsgstatus', get_string('contact_form_rsgstatus_rule_missing', 'auth_rsg'), 'required', null, 'server'); /* get_string('missingrsgstatus')*/

        $naturedata = array();

        for ($i = 0; $i < 8; $i++) {
            $naturedata[] = get_string(('contact_form_rsg_form_nature_demande_' . $i), 'mod_rsg');
        }
        $mform->addElement('select', 'rsgnature', get_string('contact_form_rsg_form_nature_demande', 'mod_rsg'), $naturedata, array('style' => 'width:316px;'));
        $mform->setType('rsgnature', PARAM_TEXT);
        $mform->addRule('rsgnature', get_string('contact_form_rsgnature_rule_missing', 'mod_rsg'), 'regex', '/^[1-7]{1}[1-7]*$/', 'client');
        $mform->addRule('rsgnature', get_string('contact_form_rsgnature_rule_missing', 'mod_rsg'), 'required', null, 'client');

        
        $placeholdertext = get_string('contact_form_message_placeholder','mod_rsg');


        $mform->addElement('textarea', 'message', get_string('contact_form_message_label','mod_rsg'), array('maxlength'=>$char_limit, 'id'=>'message_element', 'placeholder' => $placeholdertext, 'style' => "height:180px; max-height:180px; max-width:302px; width:302px; resize: none;"));
        $mform->setType('message', PARAM_TEXT);
        $mform->addRule('message', get_string('contact_form_rule_mustbefilled', 'mod_rsg'), 'required', null, 'server');
       
        /* Note: Déactive le add_action_buttons et utilise le html correspondant mais sans les control group pour pouvoir forcer l'alignement à gauche comme dans la maquette. */    
        $mform->addElement('html', '<div class="controls"><input id="submitbutton2" name="submitbutton2" value="' . get_string('contact_form_submit_btn_title', 'mod_rsg') . '" type="submit"></div>');
        //$this->add_action_buttons(FALSE,  get_string('contact_form_submit_btn_title', 'mod_rsg'));

    }

    function validation($data, $files)
    {
        global $USER;
        $errors = parent::validation($data, $files);
        $email = '';
        $confirmemail = '';
        if (isset($data['email'])) {
            $email = $data['email'];
        }
        if (isset($data['confirmemail'])) {
            $confirmemail = $data['confirmemail'];
        }

        // Validation pour s'assurer que les 2 courriels sont identiques
        if (($email <> '') || ($confirmemail <> '')) {
            if ($email <> $confirmemail) {
                $errors['confirmemail'] = get_string('contact_form_rsgemailsdontmatch', 'mod_rsg');
                $errors['email'] = get_string('contact_form_rsgemailsdontmatch', 'mod_rsg');
                return $errors;
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
