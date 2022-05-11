<?php
require_once('config.php');

$PAGE->set_context(context_system::instance());

$page_title = get_string('error404_page_title', 'mod_rsg');
$PAGE->set_title($page_title);
$PAGE->set_url('/', array('current_page_nameslug' => '404'));
$PAGE->set_pagelayout('mypublic');

echo $OUTPUT->header();
// echo '<div class="rsg_page_title">'.$page_title.'</div>';

echo '<div class="modal dialog-error-404 fade in" tabindex="-1" role="dialog">
        <div class="modal-body">  
            <div class="pixeldesign_fix text-center ">
                <p class="modal-title ">' . get_string("error404_dialog_title", "mod_rsg") . '
                </p>
                <a class="dialog-green-button-rsg btn-block" role="button" href="/">'
                   . get_string("error404_dialog_button_return_home", "mod_rsg") . 
                '</a>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade in"></div>';

echo $OUTPUT->footer();