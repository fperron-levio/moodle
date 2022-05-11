
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

$page_title = get_string('catalog_page_title', 'mod_rsg');
$page_title2 = get_string('catalog_page_title2', 'mod_rsg');
//$page_message = get_string('catalog_page_message', 'mod_rsg');

$PAGE->set_url('/mod/rsg/catalogue', array('current_page_nameslug' => 'catalogue'));
$PAGE->set_context(context::instance_by_id(1)); /* context system */
require_login();

$PAGE->set_pagelayout('catalogue');
$PAGE->set_title($page_title);

require_once __DIR__ . '/../affichageliste/affichageliste_common.php';

// # 3719
//$capsule_data_catalogue = capsule::getCapsuleCatalog(null, capsule::SORT_ALPHA);
$capsule_data_catalogue = capsule::getCapsuleCatalog(null, capsule::SORT_DEFAULT);

$noResultMsg = get_string('catalog_empty', 'mod_rsg'); /* Ne devrait jamais arriver, va afficher une erreur. */

echo '<div class="rsg_page_title">'.$page_title.'</div>'; // #3719

/* Initialisation */
$PAGE->requires->js_init_call("init_rsgApp", null, false);
$PAGE->requires->js_init_call("init_rsgCatalog", array(array(
    "capsuleData"=>$capsule_data_catalogue, 
    "capsuleDataType"=>"catalogue",
    "capsuleTemplateName"=> "capsule", 
    "infoCategories"=>$infoCategories,
    "noResultsMsg"=>$noResultMsg)), false);

include(__DIR__."/../statique/bandeau_catalogue.html");
echo '<div class="rsg_page_title">'.$page_title2.'</div>';
//echo '<b>'.$page_message.'</b><br><br>';
include(__DIR__."/../statique/affichageCatalogue.html");

echo $OUTPUT->footer();


