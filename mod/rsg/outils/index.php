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
 * List of RSGS in course
 *
 * @package    mod
 * @subpackage rsg
 * @copyright  2014 onwards Cégep@distance
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once('../../../config.php');
require_once __DIR__ . '/../locallib.php';

global $USER;

$PAGE->set_context(context::instance_by_id(1)); /* context system */
require_login();

$page_title = get_string('mytoolbox_page_title', 'mod_rsg');

/* SPECIFIC TO PAGE */
$PAGE->set_url('/mod/rsg/outils', array('current_page_nameslug' => 'outils'));
$PAGE->set_title($page_title);

$PAGE->set_pagelayout('incourse'); /* Todo: revoir nommenclature. */

require_once __DIR__ . '/../affichageliste/affichageliste_common.php';

$capsule_data_user_toolbox = capsule::getUserToolbox($USER->id);

/* Initialisation */
$PAGE->requires->js_init_call("init_rsgApp", null, false);
$PAGE->requires->js_init_call("init_rsgCatalog", array(array("capsuleData"=>$capsule_data_user_toolbox, "capsuleDataType"=>"user_toolbox", "capsuleTemplateName"=> "outil" ,"infoCategories"=>$infoCategories/*, "noResultsMsg"=>$noResultMsg*/)), false);

echo '<div class="rsg_page_title">'.$page_title.'</div>';

if (!empty($capsule_data_user_toolbox)) {
    $entete_outil_msg = get_string('mytoolbox_click_on_tool_to_open_msg', 'mod_rsg');
} else {
    $entete_outil_msg = get_string('mytoolbox_empty', 'mod_rsg');
}

include(__DIR__."/../statique/entete_outil.html");
    
include(__DIR__."/../statique/affichageListe2.html");

echo $OUTPUT->footer();
