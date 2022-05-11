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

/* SPECIFIC TO PAGE */

$page_title = get_string('myjourney_page_title', 'mod_rsg');
$PAGE->set_url('/mod/rsg/monparcours', array('current_page_nameslug' => 'monparcours'));
$PAGE->set_title($page_title);

$PAGE->set_pagelayout('incourse'); /* Todo: revoir nommenclature. */

require_once __DIR__ . '/../affichageliste/affichageliste_common.php';

/* Variables utilisées dans le template suivant (tableau récapitulatif). */
$uecs = capsule::getUserUEC($USER->id);
$uecs_currentSubscription = capsule::getUserUEC_CurrentSubscription($USER->id);
/* Pourrait appeler getUserUECTotalHours mais on a déjà le nombre d'uec total. */
$sum_all_subcription_hours = capsule::getHoursFromUEC($uecs);
$sum_current_subcription_hours = capsule::getHoursFromUEC($uecs_currentSubscription);

// #3718    
 include(__DIR__."/../statique/entete_monparcours.html");  /* #3718 ca été enlever par endrei ie mis en commentaire, je lai  remis par kane pour l'affiche : Total des Unités d’Éducation Continue, du bandeau bleu*/

$capsule_data_user_journey = capsule::getUserJourney($USER->id);

$noResultMsg = get_string('myjourney_empty', 'mod_rsg');

/* Initialisation */
$PAGE->requires->js_init_call("init_rsgApp", null, false);
$PAGE->requires->js_init_call("init_rsgCatalog", array(array("capsuleData"=>$capsule_data_user_journey, "capsuleDataType"=>"user_journey", "capsuleTemplateName"=> "parcours", "infoCategories"=>$infoCategories, "noResultsMsg"=>$noResultMsg)), false);

echo '<div class="rsg_page_title">'.$page_title.'</div>';


echo '<div class="alert alert-info" style="padding-right:0">
 Une fois que vous aurez termin&#233; une capsule et son auto&#233;valuation, vous pourrez les consulter &#224; nouveau &#224; partir du Catalogue de capsules. 
 </div>';

include(__DIR__."/../statique/affichageListe2.html");

include(__DIR__."/../statique/bandeau_sofeduc.html");

echo $OUTPUT->footer();
