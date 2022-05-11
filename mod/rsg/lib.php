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
 * Library of interface functions and constants for module newmodule
 *
 * All the core Moodle functions, neeeded to allow the module to work
 * integrated in Moodle should be placed here.
 * All the newmodule specific functions, needed to implement all the module
 * logic, should go to locallib.php. This will help to save some memory when
 * Moodle is performing actions across all modules.
 *
 * @package    mod_rsg
 * @copyright  2011 Your Name
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/** Il faut mettre les catégories correspondantes du système */
$rsg_conf=get_config('rsg');

// todo:  Le mécanisme ne semble pas bien fonctionner lors de l'ajout de nouvelle catégorie (avant update du plugin).
// Le problème pourrait être causé par fonction de locallib, par ex. "getIdCategories" qui utilise toutes les constantes sans revalider si elle sont définies.
if (isset($rsg_conf->rose_cat)) {
   define('RSG_CAT_ROSE', $rsg_conf->rose_cat);  
}

if (isset($rsg_conf->violet_cat)) {
    define('RSG_CAT_VIOLET', $rsg_conf->violet_cat);
}

if (isset($rsg_conf->green_cat)) {
   define('RSG_CAT_GREEN',  $rsg_conf->green_cat); 
}

if (isset($rsg_conf->orange_cat)) {
    define('RSG_CAT_ORANGE',  $rsg_conf->orange_cat);
}

/* ------------------------------------------ */
class RSG_CONST{
    static $CONTENT=0;
    static $OUTIL=1;
    static $EVAL=2;
}

////////////////////////////////////////////////////////////////////////////////
// Moodle core API                                                            //
////////////////////////////////////////////////////////////////////////////////

/**
 * Returns the information on whether the module supports a feature
 *
 * @see plugin_supports() in lib/moodlelib.php
 * @param string $feature FEATURE_xx constant for requested feature
 * @return mixed true if the feature is supported, null if unknown
 */
function rsg_supports($feature) {
    switch($feature) {
        case FEATURE_GROUPS:                  return false;
        case FEATURE_GROUPINGS:               return false;
        case FEATURE_GROUPMEMBERSONLY:        return false;
        case FEATURE_MOD_INTRO:               return false;
        case FEATURE_COMPLETION_TRACKS_VIEWS: return true;
        case FEATURE_GRADE_HAS_GRADE:         return false;
        case FEATURE_GRADE_OUTCOMES:          return false;
        case FEATURE_RATE:                    return false;
        case FEATURE_BACKUP_MOODLE2:          return false;
        case FEATURE_SHOW_DESCRIPTION:        return false;

        default:                        return null;
    }
}

/**
 * Saves a new instance of the rsg into the database
 *
 * Given an object containing all the necessary data,
 * (defined by the form in mod_form.php) this function
 * will create a new instance and return the id number
 * of the new instance.
 *
 * @param object $rsg An object from the form in mod_form.php
 * @param mod_rsg_mod_form $mform
 * @return int The id of the newly inserted rsg record
 */
function rsg_add_instance(stdClass $rsg, mod_rsg_mod_form $mform = null) {
    global $DB;

    //valider que les éléments nécessaires à la création existent
    //scorm, ressource, quiz... 
    //C'est un return False; dans le cas contraire
    
    //m: module
    $scorm_m_id = $DB->get_field('modules', 'id', array('name' => 'scorm'), MUST_EXIST);
    $resource_m_id = $DB->get_field('modules', 'id', array('name' => 'resource'), MUST_EXIST);
    $quiz_m_id = $DB->get_field('modules', 'id', array('name' => 'quiz'), MUST_EXIST);

    //il y aura une exception si les modules ne sont pas disponibles...ce qui ne devrait arriver jamais :)
    
    //cm: cours module, il faut s'assurer que les composantes existent dans le cours
    //IGNORE_MISSING
    $scorm_cm_id = $DB->get_field('course_modules', 'id', array('course'=>$rsg->course,'module' => $scorm_m_id));
    $resource_cm_id = $DB->get_record('course_modules', array('course'=>$rsg->course,'module' => $resource_m_id), 'id, instance');
    $quiz_cm_id = $DB->get_field('course_modules', 'id', array('course'=>$rsg->course,'module' => $quiz_m_id));
    
    if (!$scorm_cm_id || !($resource_cm_id instanceof stdClass) || !$quiz_cm_id){
        throw new \moodle_exception('error_missing_prerequisite', 'rsg_addinstance', '', get_string("rsg_error_missing_prerequisite", "rsg"));
        return false; /* note eg: !!!!! */
    }
    
    $outil=$DB->get_field('resource', 'name', array('id'=>$resource_cm_id->instance));
    $cat= $DB->get_field('course', 'category', array('id'=>$rsg->course));
    $rsg->category = $cat;
    
    //ce n'est pas optimal de les mettre dans la BD, mais ça épargne des requêtes au moment de l'accès
    $rsg->cm_scorm_id = $scorm_cm_id;
    $rsg->cm_resou_id = $resource_cm_id->id;
    $rsg->cm_quizz_id = $quiz_cm_id;
    $rsg->outil = $outil;
    $rsg->timecreated = time();
    
    $rsg->id = $DB->insert_record('rsg', $rsg);
    
    $cmid = $rsg->coursemodule;
    $DB->set_field('course_modules', 'instance', $rsg->id, array('id'=>$cmid));
    
    return $rsg->id;
}

/**
 * Updates an instance of the rsg in the database
 *
 * Given an object containing all the necessary data,
 * (defined by the form in mod_form.php) this function
 * will update an existing instance with new data.
 *
 * @param object $rsg An object from the form in mod_form.php
 * @param mod_rsg_mod_form $mform
 * @return boolean Success/Fail
 */
function rsg_update_instance(stdClass $rsg, mod_rsg_mod_form $mform = null) {
    global $DB;

    $rsg->timemodified = time();
    $rsg->id = $rsg->instance;

    # You may have to add extra stuff in here #

    return $DB->update_record('rsg', $rsg);
}

/**
 * Update the category associated with the course-rsg.
 * If the original course is changed from its original category this will updates the rsg.
 * @param stdClass $rsg
 * 
 */
function rsg_validate_course_cat(stdClass $rsg){
    global $DB;
    $course=$DB->get_record('course',array('id'=>$rsg->course));
    if ($course->category != $rsg->category){
        $rsg->category=$course->category;
        $rsg->timemodified = time();
        
        return $DB->update_record('rsg',$rsg);
    }
}

/**
 * Removes an instance of the rsg from the database
 *
 * Given an ID of an instance of this module,
 * this function will permanently delete the instance
 * and any data that depends on it.
 *
 * @param int $id Id of the module instance
 * @return boolean Success/Failure
 */
function rsg_delete_instance($id) {
    global $DB;

    if (! $rsg = $DB->get_record('rsg', array('id' => $id))) {
        return false;
    }

    # Delete any dependent records here #
    $DB->delete_records('rsg_track', array('rsgid'=>$rsg->id));

    $DB->delete_records('rsg', array('id' => $rsg->id));

    return true;
}

/**
 * Returns a small object with summary information about what a
 * user has done with a given particular instance of this module
 * Used for user activity reports.
 * $return->time = the time they did it
 * $return->info = a short text description
 *
 * @return stdClass|null
 */
function rsg_user_outline($course, $user, $mod, $rsg) {

    $return = new stdClass();
    $return->time = 0;
    $return->info = '';
    return $return;
}

/**
 * Prints a detailed representation of what a user has done with
 * a given particular instance of this module, for user activity reports.
 *
 * @param stdClass $course the current course record
 * @param stdClass $user the record of the user we are generating report for
 * @param cm_info $mod course module info
 * @param stdClass $rsg the module instance record
 * @return void, is supposed to echp directly
 */
function rsg_user_complete($course, $user, $mod, $rsg) {
}

/**
 * Given a course and a time, this module should find recent activity
 * that has occurred in rsg activities and print it out.
 * Return true if there was output, or false is there was none.
 *
 * @return boolean
 */
function rsg_print_recent_activity($course, $viewfullnames, $timestart) {
    return false;  //  True if anything was printed, otherwise false
}

/**
 * Prepares the recent activity data
 *
 * This callback function is supposed to populate the passed array with
 * custom activity records. These records are then rendered into HTML via
 * {@link rsg_print_recent_mod_activity()}.
 *
 * @param array $activities sequentially indexed array of objects with the 'cmid' property
 * @param int $index the index in the $activities to use for the next record
 * @param int $timestart append activity since this time
 * @param int $courseid the id of the course we produce the report for
 * @param int $cmid course module id
 * @param int $userid check for a particular user's activity only, defaults to 0 (all users)
 * @param int $groupid check for a particular group's activity only, defaults to 0 (all groups)
 * @return void adds items into $activities and increases $index
 */
function rsg_get_recent_mod_activity(&$activities, &$index, $timestart, $courseid, $cmid, $userid=0, $groupid=0) {
}

/**
 * Prints single activity item prepared by {@see rsg_get_recent_mod_activity()}

 * @return void
 */
function rsg_print_recent_mod_activity($activity, $courseid, $detail, $modnames, $viewfullnames) {
}

/**
 * Function to be run periodically according to the moodle cron
 * This function searches for things that need to be done, such
 * as sending out mail, toggling flags etc ...
 *
 * @return boolean
 * @todo Finish documenting this function
 **/
function rsg_cron () {
    global $DB;
    $modif=0;
    $rsgs=$DB->get_recordset('rsg');
    if ($rsgs->valid()) {
        foreach ($rsgs as $rsg) {
            if (rsg_validate_course_cat($rsg))
                $modif++;
            
        }
    }
    $rsgs->close();
    echo $modif.' rsgs ont ete deplaces';
    return true;
}

/**
 * Returns all other caps used in the module
 *
 * @example return array('moodle/site:accessallgroups');
 * @return array
 */
function rsg_get_extra_capabilities() {
    return array();
}

////////////////////////////////////////////////////////////////////////////////
// Gradebook API                                                              //
////////////////////////////////////////////////////////////////////////////////

/**
 * Is a given scale used by the instance of rsg?
 *
 * This function returns if a scale is being used by one rsg
 * if it has support for grading and scales. Commented code should be
 * modified if necessary. See forum, glossary or journal modules
 * as reference.
 *
 * @param int $rsgid ID of an instance of this module
 * @return bool true if the scale is used by the given rsg instance
 */
function rsg_scale_used($rsgid, $scaleid) {
    global $DB;

    /** @example */
    if ($scaleid and $DB->record_exists('rsg', array('id' => $rsgid, 'grade' => -$scaleid))) {
        return true;
    } else {
        return false;
    }
}

/**
 * Checks if scale is being used by any instance of rsg.
 *
 * This is used to find out if scale used anywhere.
 *
 * @param $scaleid int
 * @return boolean true if the scale is used by any rsg instance
 */
function rsg_scale_used_anywhere($scaleid) {
    global $DB;

    /** @example */
    if ($scaleid and $DB->record_exists('rsg', array('grade' => -$scaleid))) {
        return true;
    } else {
        return false;
    }
}

/**
 * Creates or updates grade item for the give rsg instance
 *
 * Needed by grade_update_mod_grades() in lib/gradelib.php
 *
 * @param stdClass $rsg instance object with extra cmidnumber and modname property
 * @param mixed optional array/object of grade(s); 'reset' means reset grades in gradebook
 * @return void
 */
function rsg_grade_item_update(stdClass $rsg, $grades = null) {
    global $CFG, $DB;
    require_once($CFG->libdir.'/gradelib.php');

    /** @example */
    $item = array();
    $item['itemname'] = clean_param($rsg->name, PARAM_NOTAGS);
    $item['gradetype'] = GRADE_TYPE_VALUE;
    
    // Anomalie #3564
    // Au lieu de récupérer le grademax de la table RSG (qui ne contient pas cette valeur) on le récupère du SCORM
    //$item['grademax']  = $rsg->grade;
    // Attention: Dans le cas où il y a plusieurs SCORM possible par cours, il faut plutôt passer par la table course_modules pour récupérer le SCORM
    $scorm = $DB->get_record('scorm', array('course'=>$rsg->course));
    $item['grademax']  = $scorm->maxgrade;
    // fin Anomalie #3564
    
    $item['grademin']  = 0;

    grade_update('mod/rsg', $rsg->course, 'mod', 'rsg', $rsg->id, 0, null, $item);
}

/**
 * Update rsg grades in the gradebook
 *
 * Needed by grade_update_mod_grades() in lib/gradelib.php
 *
 * @param stdClass $rsg instance object with extra cmidnumber and modname property
 * @param int $userid update grade of specific user only, 0 means all participants
 * @return void
 */
function rsg_update_grades(stdClass $rsg, $userid = 0) {
    global $CFG, $DB;
    require_once($CFG->libdir.'/gradelib.php');

    /** @example */
    $grades = array(); // populate array of grade objects indexed by userid

    grade_update('mod/rsg', $rsg->course, 'mod', 'rsg', $rsg->id, 0, $grades);
}

////////////////////////////////////////////////////////////////////////////////
// File API                                                                   //
////////////////////////////////////////////////////////////////////////////////

/**
 * Returns the lists of all browsable file areas within the given module context
 *
 * The file area 'intro' for the activity introduction field is added automatically
 * by {@link file_browser::get_file_info_context_module()}
 *
 * @param stdClass $course
 * @param stdClass $cm
 * @param stdClass $context
 * @return array of [(string)filearea] => (string)description
 */
function rsg_get_file_areas($course, $cm, $context) {
    return array();
}

/**
 * File browsing support for rsg file areas
 *
 * @package mod_rsg
 * @category files
 *
 * @param file_browser $browser
 * @param array $areas
 * @param stdClass $course
 * @param stdClass $cm
 * @param stdClass $context
 * @param string $filearea
 * @param int $itemid
 * @param string $filepath
 * @param string $filename
 * @return file_info instance or null if not found
 */
function rsg_get_file_info($browser, $areas, $course, $cm, $context, $filearea, $itemid, $filepath, $filename) {
    return null;
}

/**
 * Serves the files from the rsg file areas
 *
 * @package mod_rsg
 * @category files
 *
 * @param stdClass $course the course object
 * @param stdClass $cm the course module object
 * @param stdClass $context the rsg's context
 * @param string $filearea the name of the file area
 * @param array $args extra arguments (itemid, path)
 * @param bool $forcedownload whether or not force download
 * @param array $options additional options affecting the file serving
 */
function rsg_pluginfile($course, $cm, $context, $filearea, array $args, $forcedownload, array $options=array()) {
    global $DB, $CFG;

    if ($context->contextlevel != CONTEXT_MODULE) {
        send_file_not_found();
    }

    require_login($course, true, $cm);

    send_file_not_found();
}

////////////////////////////////////////////////////////////////////////////////
// Navigation API                                                             //
////////////////////////////////////////////////////////////////////////////////

/**
 * Extends the global navigation tree by adding rsg nodes if there is a relevant content
 *
 * This can be called by an AJAX request so do not rely on $PAGE as it might not be set up properly.
 *
 * @param navigation_node $navref An object representing the navigation tree node of the rsg module instance
 * @param stdClass $course
 * @param stdClass $module il faut remplacer par $plugin
 * @param cm_info $cm
 */
function rsg_extend_navigation(navigation_node $navref, stdclass $course, stdclass $plugin, cm_info $cm) {
}

/**
 * Extends the settings navigation with the rsg settings
 *
 * This function is called when the context for the page is a rsg module. This is not called by AJAX
 * so it is safe to rely on the $PAGE.
 *
 * @param settings_navigation $settingsnav {@link settings_navigation}
 * @param navigation_node $rsgnode {@link navigation_node}
 */
function rsg_extend_settings_navigation(settings_navigation $settingsnav, navigation_node $newmodulenode=null) {
}
