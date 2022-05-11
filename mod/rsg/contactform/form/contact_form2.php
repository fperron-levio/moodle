<?php

/* Contact form: RSG */
/* todo: Add captcha: voir fonction plus bas. */
/* todo: Traitement de validation de email différent du signup? */
/* todo: /auth/rsg/classes/RSG_Form.php devrait bouger dans un dossier commun? */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/auth/rsg/classes/RSG_Form.php');
require_once $CFG->libdir.'/formslib.php';

class rsg_contact_form2 extends RSG_Form {

    function definition() {
        global $CFG;

        // Si usager anonyme/externe/non-connecté.
        if (isloggedin()) {

            $mform = $this->_form;

            $char_limit = '500'; /* Pourrait être envoyé en configs. */

            $mform->addElement('html', '<h5 class="static-page-title">' . get_string('contact_form_title2','mod_rsg') . '</h5>');
            $mform->addElement('html', '<div class="rsg_form_info alert alert-info soutien_technique">' . get_string('contact_form_text2','mod_rsg') . '</div>');

            /* todo: envoyer style dans css */
            $mform->addElement('html', '<div style="margin-bottom:20px;" class="rsg_form_space"></div>');

            $placeholdertext = get_string('contact_form_message_placeholder','mod_rsg');

            $mform->addElement('textarea', 'message2', get_string('contact_form_rsg_form_comment','mod_rsg'), array('maxlength'=>$char_limit, 'id'=>'message_element', 'placeholder' => $placeholdertext, 'style' => "height:180px; max-height:180px; max-width:302px; width:302px; resize: none;"));
            $mform->setType('message2', PARAM_TEXT);
           // $mform->addRule('message2', get_string('contact_form_rule_mustbefilled', 'mod_rsg'), 'required', null, 'client');

            /* Note: Déactive le add_action_buttons et utilise le html correspondant mais sans les control group pour pouvoir forcer l'alignement à gauche comme dans la maquette. */
           $mform->addElement('html', '<div class="controls"><input id="submitbutton2" name="submitbutton2" value="' . get_string('contact_form_submit_btn_title', 'mod_rsg') . '" type="submit"></div>');
            //$this->add_action_buttons(FALSE,  get_string('contact_form_submit_btn_title', 'mod_rsg'));

        }

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
