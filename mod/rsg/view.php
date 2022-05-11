<?php

/**
 * Description of view
 * Ce fichier gère l'accès à une capsule.
 * @author Nelson Moller <nmoller at cegepadistance.ca>
 */
/**
 * Capsule RSGS in course
 *
 * @package    mod
 * @subpackage rsg
 * @copyright  2014 onwards Cégep@distance
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once('../../config.php');
require_once($CFG->dirroot . '/mod/rsg/lib.php');
require_once($CFG->dirroot . '/mod/rsg/locallib.php');
require_once($CFG->dirroot . '/mod/quiz/lib.php');

$cap_id = required_param('id', PARAM_INT); // capsule id
$part = optional_param('part', RSG_CONST::$CONTENT, PARAM_INT);
$from = optional_param('from',-1, PARAM_INT);

//Ce paramètre veut dire que on accède par le widget. C'est pour garder la compatibilité de MDL que impose que view
//donne accès quand on est en mode édition...
if ($from==0){
    $cap = $DB->get_record('rsg', array('id' => $cap_id), '*', MUST_EXIST);
    $course = $DB->get_record('course', array('id' => $cap->course), '*', MUST_EXIST);
    
    //#3240 on retrace le scorm id (contenu de la capsule) à partir de l'id du cours
    $scorm = $DB->get_record_sql('SELECT * FROM {scorm} WHERE course = :course', array('course'=>$course->id));

    require_login($course);

    $tracking = $DB->get_record('rsg_track', array('rsgid' => $cap_id, 'userid' => $USER->id), '*');

    //#3240 on vérifie dans tracking contenu si lesson_status est en état complété (tous les pages a été vues)
    $tracking_scorm = $DB->get_record_sql('SELECT * FROM {scorm_scoes_track} WHERE element = :element AND value = :value AND scormid = :scormid AND userid = :userid', array('element'=>'cmi.core.lesson_status', 'value'=>'completed', 'scormid'=>$scorm->id, 'userid'=>$USER->id));

    global $USER;

    if ($tracking == NULL) {//c'est la première visite à la capsule
        $track = new stdClass();
        $track->userid = $USER->id;
        $track->rsgid = $cap_id;
        $track->timestarted = time();
        $track->visits = 1;


        $track_id = $DB->insert_record('rsg_track', $track);
        //L'étudiant doit regarder le contenu en premier.
        $evaluationUrl = new moodle_url('/mod/quiz/view.php', array('id' => $cap->cm_quizz_id));
        $link = new moodle_url('/mod/scorm/player.php', array('cm' => $cap->cm_scorm_id, 'display' => 'popup', 'scoid' => '', 'evaluationUrl'=> $evaluationUrl));
    } else {
        $tracking->visits = $tracking->visits + 1;
        $tracking->lastvisit = time();

        switch ($part) {
            case RSG_CONST::$EVAL:
                //#3240 on envoie au contenu de la capsule car le lesson_status n'est pas en état complété
                if ($tracking_scorm == NULL) {
                    $evaluationUrl = new moodle_url('/mod/quiz/view.php', array('id' => $cap->cm_quizz_id));
                    $link = new moodle_url('/mod/scorm/player.php', array('cm' => $cap->cm_scorm_id, 'display' => 'popup', 'scoid' => '', 'evaluationUrl'=> $evaluationUrl));
                    break;
                }
                else {
                    $link = new moodle_url('/mod/quiz/view.php', array('id' => $cap->cm_quizz_id));
                    break;
                }


            case RSG_CONST::$OUTIL:
                //TODO: valider que l'utilisateur
                $link = new moodle_url('/mod/resource/view.php', array('id' => $cap->cm_resou_id));
                break;

            default:  
                $evaluationUrl = new moodle_url('/mod/quiz/view.php', array('id' => $cap->cm_quizz_id));
                $link = new moodle_url('/mod/scorm/player.php', array('cm' => $cap->cm_scorm_id, 'display' => 'popup', 'scoid' => '', 'evaluationUrl'=> $evaluationUrl));
        }

        $DB->update_record('rsg_track', $tracking);
    }

    global $USER;
    if (!isset($USER->rsg)){
        $USER->rsg=new \stdClass();
    }
    //TODO: s'il y a un refactoring... on devrait ne plus utiliser juste le id de capsule... cela epargnerait une requête.
    /* NOTE EG: n'est plus utilisé dans Évaluation (bug). Il faudrait valider si c'est utilisé ailleurs et l'enlever. */
    $USER->rsg->id=$cap_id;
    $USER->rsg->cap=$cap;
    //rediger l'utilisateur vers l'élément souhaité
    redirect($link);
}
else{
    //cette partie ne sera accessible que par les aministrateurs...
    require_login();
    $PAGE->set_context(context::instance_by_id(1));
    $PAGE->set_url('/mod/rsg/view.php', array('id' => $cap_id));
    echo $OUTPUT->header();
    echo $OUTPUT->heading(format_string('Voudriez vous éditer cette instance....'), 2);
    echo 'Voudriez vous éditer cette instance....Donc, revenez au cours et séléctionnez edit en mode édition';
    echo $OUTPUT->footer();
}
