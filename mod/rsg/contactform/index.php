<?php
// http://10.4.2.4/mod/rsg/contact/index.php

require_once('../../../config.php');
require_once($CFG->dirroot . '/mod/rsg/contactform/form/contact_form.php');

global $CFG, $USER;

$PAGE->set_context(context_system::instance());

$PAGE->set_title('Formulaire de contact'); // todo: Ajouter à la table de string.
$PAGE->set_url('/mod/rsg/contactform', array('current_page_nameslug' => 'contactform'));
$PAGE->set_pagelayout('popupform');

echo $OUTPUT->header();

$contactform = new rsg_contact_form();

// Needed to parse rules.
if ($contactform->is_validated()) {

    $data = $contactform->get_data();

    $supportuser = \core_user::get_support_user();
    $subject = get_string('contact_form_email_title', 'mod_rsg');

    $email_data = new \stdClass();
    $email_data->user_message = $data->message;
    
    if (!isloggedin()) {
        // Usager non-connecté (pourrait quand même être un usager RSG).
        // Récupère les infos de la forme (qui affiche des champs supplémentaires pour gérer ce cas).
        $email_data->lastname = $data->lastname;
        $email_data->firstname = $data->firstname;
        $email_data->email = $data->email;
        $messageStringId = 'contact_form_email_content_notloggedin';
    } else {
        // Usager connecté (usager RSG).
        // Récupè les infos de la bd.
        $email_data->lastname = $USER->lastname;
        $email_data->firstname = $USER->firstname;
        $email_data->email = $USER->email;
        $messageStringId = 'contact_form_email_content_loggedin';
    }

    // Envoyer base message. Plain pour le moment.
    $message_string = get_string($messageStringId, 'mod_rsg', $email_data);
    echo '<pre>';
    var_dump($message_string);
    var_dump($supportuser);
    echo '</pre>';
    die();
    $result = email_to_user($supportuser, $supportuser, $subject, $message_string, null);

    if ($result) {
        echo '<div class="rsg_form_title">' . get_string('contact_form_confirmation_ok_title', 'mod_rsg') . '</div>';
        echo '<div class="rsg_form_text">' . get_string('contact_form_confirmation_ok_text', 'mod_rsg') . '</div>';
		echo $OUTPUT->footer();   //kane
    } else {
        /* Exemple erreur possible = smtp mal configuré lors de test local. */
        echo '<div class="rsg_form_title">' . get_string('contact_form_confirmation_error_title', 'mod_rsg') . '</div>';
        echo '<div class="rsg_form_text">' . get_string('contact_form_confirmation_error_text', 'mod_rsg') . '</div>';
    }
} else {
    // Afficher la forme (première fois et/ou erreur à corriger par l'utilisateur.
    $contactform->display();
}

echo $OUTPUT->footer();
