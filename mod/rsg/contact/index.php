<?php
// http://10.4.2.4/mod/rsg/contact/index.php

require_once('../../../config.php');
require_once($CFG->dirroot . '/mod/rsg/contactform/form/contact_form.php');
require_once($CFG->dirroot . '/mod/rsg/contactform/form/contact_form2.php');

global $CFG, $USER, $DB;

$PAGE->set_context(context_system::instance());

$page_title = get_string('contact' . '_page_title', 'mod_rsg');
$PAGE->set_title($page_title);
$PAGE->set_url('/mod/rsg/contactform', array('current_page_nameslug' => 'contactform'));
$PAGE->set_pagelayout('mypublic');

echo $OUTPUT->header();

$contactform = new rsg_contact_form();
$data = $contactform->get_data();
// #3131 On met l'adresse courriel de la personne à conctacter pour le formulaire Nous joindre dans la page config.php et paramètre de la fonction email_to_user nous oblige à aller chercher son id
$supportuser = $DB->get_record_sql('SELECT u.id id, u.email email FROM mdl_user u WHERE u.email = :email', array('email'=>$CFG->rsg_email_contact));

// Needed to parse rules.
if ($contactform->is_validated()) {

    //$supportuser = \core_user::get_support_user();
    $subject = get_string('contact_form_email_title', 'mod_rsg');
    $email_data = new \stdClass();
    $email_data->user_message = $data->message;
    $email_data->email = $data->email;
    $email_data->phone =  $data->phone1;
    $email_data->rsgstatus = get_string('contact_form_rsg_form_status_1', 'mod_rsg');
    $email_data->rsgnature = get_string(('contact_form_rsg_form_nature_demande_' . $data->rsgnature), 'mod_rsg');

    if (!isloggedin()) {
        // Usager non-connecté (pourrait quand même être un usager RSG).
        // Récupère les infos de la forme (qui affiche des champs supplémentaires pour gérer ce cas).
        $email_data->name = $data->name;
        $email_data->noidentification = $data->noidentification;
        $messageStringId = 'contact_form_email_content_notloggedin';
    } else {
        // Usager connecté (usager RSG).
        // Récupè les infos de la bd.
        $email_data->name = $USER->firstname. ' ' . $USER->lastname;
        $email_data->noidentification = $USER->username;
        $messageStringId = 'contact_form_email_content_loggedin';
    }

    // Envoyer base message. Plain pour le moment.
    $message_string = html_to_text(get_string($messageStringId, 'mod_rsg', $email_data));

    $result = email_to_user($supportuser, $email_data->email, $subject, $message_string);

    if ($result) {
        echo '<div class="rsg_form_title">' . get_string('contact_form_confirmation_ok_title', 'mod_rsg') . '</div>';
        echo '<div class="rsg_form_text">' . get_string('contact_form_confirmation_ok_text', 'mod_rsg') . '</div>';
		echo $OUTPUT->footer();   //kane
        die ();
    } else {
        /* Exemple erreur possible = smtp mal configuré lors de test local. */
        echo '<div class="rsg_form_title">' . get_string('contact_form_confirmation_error_title', 'mod_rsg') . '</div>';
        echo '<div class="rsg_form_text">' . get_string('contact_form_confirmation_error_text', 'mod_rsg') . '</div>';
       echo $OUTPUT->footer();   //kane
	   die ();
    }
} else {
    // Afficher la forme (première fois et/ou erreur à corriger par l'utilisateur.
    $contactform->display();
}

$contactform2 = new rsg_contact_form2();
$data2 = $contactform2->get_data();

// Needed to parse rules.
if ($contactform2->is_validated()) {
    //$supportuser = \core_user::get_support_user();
    $subject2 = get_string('contact_form_email_title2', 'mod_rsg');

    if (isloggedin()) {
        $email_data2 = new \stdClass();
        $email_data2->user_message = $data2->message2;
        $email_data2->name = $USER->firstname. ' ' . $USER->lastname ;
        $email_data2->email = $USER->email;
        $email_data2->noidentification = $USER->username;
        $messageStringId2 = 'contact_form_email_content_loggedin2';
        // Envoyer base message. Plain pour le moment.
        $message_string2 = html_to_text(get_string($messageStringId2, 'mod_rsg', $email_data2));

        $result2 = email_to_user($supportuser, $email_data2->email, $subject2, $message_string2);

        if ($result2) {
            redirect('confirm.php');
            die ();
        } else {
            /* Exemple erreur possible = smtp mal configuré lors de test local. */
            echo '<div class="rsg_form_title">' . get_string('contact_form_confirmation_error_title', 'mod_rsg') . '</div>';
            echo '<div class="rsg_form_text">' . get_string('contact_form_confirmation_error_text', 'mod_rsg') . '</div>';
            echo $OUTPUT->footer();  //kane
			die ();
        }
    }
}
else {
    // Afficher la forme (première fois et/ou erreur à corriger par l'utilisateur.
    $contactform2->display();
}

if (!isloggedin()) {
 
   echo '<a style=""  href="javascript:window.history.back();"><u><img src="/theme/cleanrsg/pix/btn_retour_precedent.png" alt="Retour"></u></a>';
}

echo $OUTPUT->footer();
