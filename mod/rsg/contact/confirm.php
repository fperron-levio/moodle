<?php
// http://10.4.2.4/mod/rsg/contact/index.php
require_once('../../../config.php');
$PAGE->set_context(context_system::instance());

$page_title = get_string('contact' . '_page_title', 'mod_rsg');
$PAGE->set_title($page_title);
$PAGE->set_url('/mod/rsg/contactform', array('current_page_nameslug' => 'contactform'));
$PAGE->set_pagelayout('mypublic');

echo $OUTPUT->header();

echo '<div class="rsg_form_title">' . get_string('contact_form_confirmation_ok_title', 'mod_rsg') . '</div>';
echo '<div class="rsg_form_text">' . get_string('contact_form_confirmation_ok_text2', 'mod_rsg') . '</div>';

echo $OUTPUT->footer();
