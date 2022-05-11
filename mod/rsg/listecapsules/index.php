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

$page_title = get_string('capsuleslist_page_title', 'mod_rsg');

$PAGE->set_context(context::instance_by_id(1)); /* context system */

$PAGE->set_url('/mod/rsg/listecapsules', array('current_page_nameslug' => 'liste-capsules'));
$PAGE->set_title($page_title);

$PAGE->set_pagelayout('mypubliccatalogue'); /* todo: Copie de mypublic avec tags angular requis pour catalogue. Peut-être possible d'ajouter les tags sans doubler le code? */

require_once __DIR__ . '/../affichageliste/affichageliste_common.php';

$capsule_data_catalogue = capsule::getCapsuleCatalog(null, capsule::SORT_ALPHA);

$noResultMsg = get_string('catalog_empty', 'mod_rsg');

/* Initialisation */
$PAGE->requires->js_init_call("init_rsgApp", null, false);
$PAGE->requires->js_init_call("init_rsgCatalog", array(array("capsuleData"=>$capsule_data_catalogue, "capsuleDataType"=>"catalogue", "capsuleTemplateName"=> "capsule_capsulelist", "infoCategories"=>$infoCategories,  "noResultsMsg"=>$noResultMsg)), false);

// todo: À corriger...
echo '<div class="rsg_page_title">'.$page_title.'</div>';

include(__DIR__."/../statique/affichageListe.html"); /*  */

#4532
// echo '<a href="javascript:window.history.back();"><u>Retour</u></a>';  
 if (!isloggedin()) {
	 echo '<a class="retour" href="javascript:window.history.back();"><u>Retour</u></a>';
 }

echo $OUTPUT->footer();