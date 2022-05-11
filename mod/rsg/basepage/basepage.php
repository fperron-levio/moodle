<?php

require_once('../../../config.php');

$base_page_id = $base_page_configs["id"];
$base_page_slug = isset($base_page_configs["slug"]) ? $base_page_configs["slug"] : $base_page_configs["id"]; /*  default = id */

$PAGE->set_context(context_system::instance());

$page_title = get_string($base_page_id . '_page_title', 'mod_rsg');
$PAGE->set_title($page_title);
$PAGE->set_url('/mod/rsg/' . $base_page_id, array('current_page_nameslug' => $base_page_slug));
$PAGE->set_pagelayout('mypublic');

echo $OUTPUT->header();

echo '<div class="rsg_page_title">'.$page_title.'</div>';
include(__DIR__.'/../statique/' . $base_page_id . '.html');
include(__DIR__.'/../statique/' . $base_page_id . '.php');

echo $OUTPUT->footer();