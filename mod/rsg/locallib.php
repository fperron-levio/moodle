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
 * Internal library of functions for module newmodule
 *
 * All the newmodule specific functions, needed to implement the module
 * logic, should go here. Never include this file from your lib.php!
 *
 * @package    mod_newmodule
 * @copyright  2011 Your Name
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

/**
 * Does something really useful with the passed things
 *
 * @param array $things
 * @return object
 */
//function newmodule_do_something_useful(array $things) {
//    return new stdClass();
//}

require_once __dir__ . '/lib.php';


class capsule {

    const RSG_CAT_CLOSED_CAPSULE_MAX = 4;
    const RSG_UEC_TO_HOUR_MULTIPLICATOR = 10;
    const RSG_HOME_CAPSULE_LIMIT = 4;
    
    const SORT_DEFAULT = 0;
    const SORT_MOST_RECENT_TO_OLDEST = 1;
    const SORT_ALPHA = 2;
    
    // Pourrait/devrait provenir de la BD. Pourrait avoir param. de tri.
    // todo: Valider ordre, actuellement correspond à "4_grands_sujets_grandes-bannieres.jpg"
    // En fait, attention conflit avec catalogue: tri par nombre de capsules dans la catégorie.
    public static function getIdCategories() {
        return array(RSG_CAT_GREEN, RSG_CAT_VIOLET, RSG_CAT_ROSE, RSG_CAT_ORANGE);
    }
    
    /* Attention data utilisé du côté client js. */
    public static function getInfoCategories() {
        // Les strings devraient être définies au niveau de la table de string pour être localisable.
       $result = array();        
       
       $idCategories = self::getIdCategories(); 
       foreach ($idCategories as &$category) {
           // 2 call de fonction pour remplir la structure.. à revoir.
           array_push($result, array("id"=>$category, "name"=> self::getCategoryName($category), "color"=>self::getCategoryColorName($category)));
       }        
       
       return $result;
    }
   
    /* cas particulier. Mapping couleur pour affichage du côté client (css). */
    /* Il y aurait une meilleur méthode pour faire ça? */
    /* Attention data utilisé du côté client js. */
    public static function getCategoryColorName($catConstant) {
        switch ($catConstant) {
            case RSG_CAT_GREEN:
                return 'green';
            case RSG_CAT_VIOLET:
                return 'violet';
            case RSG_CAT_ROSE:
                return 'pink';
            case RSG_CAT_ORANGE:
                return 'orange';
        }
    }
    // todo: String hardcodés, devrait provenir de la table de string ou la catégorie même.
    public static function getCategoryName($catConstant) {
        switch ($catConstant) {
            case RSG_CAT_GREEN:
                return get_string('category_title_green', 'mod_rsg');
            case RSG_CAT_VIOLET:
                return get_string('category_title_violet', 'mod_rsg');
            case RSG_CAT_ROSE:
                return get_string('category_title_pink', 'mod_rsg');
            case RSG_CAT_ORANGE:
                return get_string('category_title_orange', 'mod_rsg');    
        }
    }
    
    public static function getUserUEC($userid){
        global $DB;
        $query='select coalesce(sum(uec),0) ';
        $query.='from {rsg} a, {rsg_track} b ';
        $query.='where a.id=b.rsgid ' ;
        $query.='and b.userid='.$userid;
        $query.=' and b.timeadduec <>0 ';
        
        $uecs=$DB->get_field_sql($query);
        
        // BD retourne float (0.00?). UEC précision au dixième (1h = 0.1).
        // Corrige précision de la valeur avant de la retourner.
        // 0.00 va afficher 0
        // 0.01 va afficher 0
        // 0.11 va afficher 0.1
        // ..
        $uecs = (floor($uecs * 10) / 10);
        
        return $uecs;
    }

    public static function getUserUEC_CurrentSubscription($userid) {
        /* todo: COMPLÉTER */
        
        /* Note: Ce concept est à revalider. On en a discuté avec Mohamed et Nelson
        * et le refactoring devra être fait (récupération des données en minimisant le nombre
        * de requêtes, caching (via memcache?).
        * Pourrait être getUserUEC avec param CurrentSubscriptionYear?
        */
        
        $totalUEC = 0;
        //return $totalUEC;

        //C'est un peu trop lourd, mais c'est ce qui était dans la production du certificat.
        global $CFG, $USER, $DB;
        require_once $CFG->dirroot.'/auth/rsg/classes/RSGUser.php';

        $user=new \auth\rsg\RSGUser($USER,false);
        $data_tot=$user->getCertificateInfoArray();
        $end=count($data_tot);

        $data=$data_tot[$end-1];

        foreach($data as $rsginfo){
            $vars=explode('-',$rsginfo);
            $rsg=$DB->get_record('rsg',array('id'=>$vars[0]));
            $totalUEC +=$rsg->uec;

        }

        return $totalUEC;
    }

    public static function getHoursFromUEC($uec) {
        return $uec * self::RSG_UEC_TO_HOUR_MULTIPLICATOR;
    }

    /**
     * Cette fonction sera appelée par l'événement '\mod_quiz\event\attempt_submitted'
     * Si jamais, il y a un changement des régles d'affaire....on gardera le changement à plus de 75%
     * @param $cap
     * @param $tracking
     */
    public static function updateUEC($cap, &$tracking) {
        global $USER, $DB;
        $quiz_cm = $DB->get_record('course_modules', array('id' => $cap->cm_quizz_id), '*', MUST_EXIST);
        //Valider l'état de l'évaluation
        //TODO: Revoir
        //

        $user_attemp = quiz_get_user_attempts($quiz_cm->instance, $USER->id, 'finished', true);
        $user_attemp = array_values($user_attemp);

        $N = count($user_attemp);

        if ($N > 0) {
            //ajouter une fois les UEC lors de la première soumission avec plus de 75
            //Ce n'est plus démandé... je le laisse en cas de changement de plans :)
            if ( /* $user_attemp[$N - 1]->sumgrades >= 75 &&*/  $tracking->timeadduec == 0) {
                $tracking->timeadduec = $user_attemp[$N - 1]->timefinish;
            }
        }
    }
    
    /**
    * Get the description of the capsule which is recorded in summary field of the associated course record.
    * note: If "fullname" and "shortname" are used later, a rsg_get_course function or caching course record would limit the db access. 
    * @param stdClass $rsg
    * 
    */
   public static function rsg_get_course_summary(stdClass $rsg){
       global $DB;
       $course=$DB->get_record('course',array('id'=>$rsg->course));
       // No default defined. If summary is empty will return empty.
       return $course->summary;
   }
   
    /**
    * Return the data needed to display the "Mon parcours" page. 
    * @param stdClass $userid
    * 
    */
   public static function getUserJourney($userid) {
        global $DB, $CFG;
        
        $user_journey = array();
        
        
       /* Trié sur timeadduec date le plus réccent autoévaluation terminé */
       $query = 'SELECT rt.id, rt.rsgid, rt.lastvisit, rt.timestarted, rt.timeadduec, r.name, r.category, sst.value ';
       $query .='FROM {scorm_scoes_track} sst, {scorm} s, {rsg} r, {rsg_track} rt ';
       $query .='WHERE rt.userid=sst.userid AND r.id=rt.rsgid AND s.id=sst.scormid AND s.course=r.course AND sst.element=\'cmi.core.lesson_status\' AND sst.userid=' .$userid;
       $query .=' ORDER BY rt.timeadduec DESC';

        // Fix limitation Moodle:
        $custom_date_format = '%d %B %Y'; // 16 avril 2014 au lieu de mercredi, 16 avril 2014 pour get_string('strftimedaydate', 'langconfig').

        $rs = $DB->get_recordset_sql($query);
        
        if ($rs->valid()) {
            foreach ($rs as $record) {

                $capsuleInfo = (array) $record;
                // Évaluer le statut.
                if ($capsuleInfo['timeadduec'] == 0) {
                    if ($capsuleInfo['value'] == 'completed') {
                        $status_text = get_string('myjourney_capsule_status_2', 'mod_rsg');
                        $capsule_completee = 0;
                        $capsule_class = 'journey_certificat_none';
                        $capsule_href =  $CFG->wwwroot . '/mod/rsg/view.php?id=' . $capsuleInfo['rsgid'].'&from=0';
                        $bouton_style = 'display: none';
                        $bouton_href = '#';
                    }
                    else {
                        $status_text = get_string('myjourney_capsule_status_1', 'mod_rsg');
                     //   $status_text = get_string('myjourney_capsule_status_4', 'mod_rsg');
                        $capsule_completee = 0;
                        $capsule_class = 'journey_certificat_none';
                        $capsule_href =  $CFG->wwwroot . '/mod/rsg/view.php?id=' . $capsuleInfo['rsgid'].'&from=0';
                        $bouton_style = 'display: none';
                        $bouton_href = '#';
                    }

                } else {
                    /* uec accordé */
                    $timeaddeduec_date = userdate($capsuleInfo['timeadduec'], $custom_date_format);
                    $status_text = get_string('myjourney_capsule_status_3', 'mod_rsg', array('date' => $timeaddeduec_date));
                    $capsule_completee = 1;
                    $capsule_class = 'journey_afficher_certificat';
                    $capsule_href = $CFG->wwwroot . '/mod/rsg/classes/pdfcertificatecapsule.php?capsule=' . $capsuleInfo['rsgid'];
                    $bouton_style = '';
                    $bouton_href = $CFG->wwwroot . '/mod/rsg/classes/pdfcertificatecapsule.php?capsule=' . $capsuleInfo['rsgid'];
                }

                // Générer le texte à afficher dans l'écran et l'ajouter à l'info provenant de la bd.  
                $capsuleInfo['status'] = $status_text;
                
                // Générer le code du lien à imprimer le certificat  
                $capsuleInfo['capsule_completee']  = $capsule_completee;
                $capsuleInfo['capsule_class'] = $capsule_class;
                $capsuleInfo['capsule_href']  = $capsule_href;
                
                // Générer le code de bouton à imprimer le certificat  
                $capsuleInfo['bouton_style'] = $bouton_style;
                $capsuleInfo['bouton_href']  = $bouton_href;               
     
                array_push($user_journey, $capsuleInfo);
            }
        }
        $rs->close();
        
        return $user_journey;
    }
    
    /**
    * Return the data needed to display the "Ma boite à outils" page. 
    * @param stdClass $userid
    * 
    */
   public static function getUserToolbox($userid) {
        global $DB;
        
        $user_toolbox = array();
       
        $query = 'SELECT a.id, a.name,a.outil, a.category, a.cm_resou_id, b.timeadduec ';
        $query .=' FROM {rsg} a, {rsg_track} b ';
        $query .=' WHERE b.rsgid = a.id ';
        $query .=' AND b.userid = ' . $userid;
        $query .=' AND b.timeadduec <> 0 ';
        $query .=' ORDER by a.category, b.timeadduec DESC';

        $rs = $DB->get_recordset_sql($query);
        
        if ($rs->valid()) {
            foreach ($rs as $record) {
                $capsuleInfo = (array) $record;
                
                // Add url.
                $id = $record->cm_resou_id;
                $url_ressource_tool = new moodle_url('/mod/resource/view.php', array('id' => $id));
                
                /* À améliorer: mélange identificateurs anglais et français... */
                $capsuleInfo['url_outil'] = $url_ressource_tool->__toString();
                
                array_push($user_toolbox, $capsuleInfo);
            }
        }
        $rs->close();
        
        return $user_toolbox;
    }
    
    private static function getSortString($sortId=null) {
        $sort_string = "";
        
        switch ($sortId) {
            case self::SORT_DEFAULT:
                $sort_string = " ORDER BY name ASC ";
                break;
            case self::SORT_ALPHA:
                $sort_string = " ORDER BY name ASC ";
                break;    
            case self::SORT_MOST_RECENT_TO_OLDEST:
                $sort_string = " ORDER BY timecreated DESC ";
                break;
        }
        
        return $sort_string;
    }
    
    
    /**
    * Return the data needed (all capsules for now) to display the "Catalogue de capsules" page. 
    *
    */
    public static function getCapsuleCatalog($limit=null, $sortId=null) {
        global $DB;
        
        $capsuleCatalog = array();
        
       /* L'ordre ne nous a pas été donné clairement (ou sans considérer réellement ce que pourrait
          vouloir un utilisateurs normal. Le tri par ordre de création (ajout dans le système) pourrait
¸         être nécéssaire pour la page d'acccueil (afficher les 4 dernières capsules (ajouter param sort + limit?)).
          Pourrait éventuellement être reçu en param ou trié du côté client.
          todo: Pourrait définir les différentes options de tri par id (sort_by_name, etc.) */  
        
        //$sort_most_recent_to_oldest = " order by timecreated DESC";
        
        /* Limiter le nombre d'entrées retournées */
        /* todo: Plus tard, il faudrait pouvoir spécifier un "range"? (from?) */
        $limit_string = "";
        
        if ($limit != null) {
            $limit_string = " LIMIT " . $limit;
        }
        
        $query = 'SELECT id, category, course, uec, name, outil, duration_capsule, duration_autoevaluation, keywords FROM {rsg} WHERE duration_capsule>0' . (self::getSortString($sortId)) . $limit_string;
        
        $rs = $DB->get_recordset_sql($query);
        
        if ($rs->valid()) {
            foreach ($rs as $record) {
                
                // Base record from RSG table (see select fields in $query).
                $capsuleInfo = (array) $record;

                // Add other data that is not stored in RSG table or is context dependant (but still related to capsule as a concept).
                // Programmer responsible of not not overwriting existing values (todo: change that behavior?).
               
                // 1) DESCRIPTION: (note: Currently stored in course).
                $description = self::rsg_get_course_summary($record);
                $capsuleInfo['description'] = $description;
                
                // 2) URLS (scorm activity (capsule) and evaluation)
                // todo: Optimisation, fabriquer url du côté client? *mais le data était déjà répété dans la version html! */
                $url_capsule = new moodle_url('/mod/rsg/view.php', array('id' => $record->id, 'from' => '0')); 
                $url_evaluation = new moodle_url('/mod/rsg/view.php', array('id' => $record->id, 'part' => RSG_CONST::$EVAL, 'from' => '0'));         
                $capsuleInfo['url_capsule'] = $url_capsule->out(false);
                $capsuleInfo['url_evaluation'] = $url_evaluation->out(false);
                
                // 3) DURATION (skin of capsule + tooltips)
                // We already have the information but we one to convert the data in minutes to a string representation (ex.: "1 h", "1 h 30 minutes", "30 minutes").
                // This could be implemented server and/or client side. We have decided to implement in once server side.
                // Eventually a class will be created to gather the concepts/acessor of capsule (instead of having multiple purpose function in this file (locallib.php).
                $duration_total = $capsuleInfo['duration_capsule'] + $capsuleInfo['duration_autoevaluation'];
                
                //Emplacement à revoir. On veut pas faire le getstring dans convertToHoursMins (à chaque conversion).
                $time_strings = get_strings(array('hour_one_short', 'hour_many_short', 'minute_one', 'minute_many'), 'mod_rsg');
                
                $capsuleInfo['duration_total_text'] = self::convertToHoursMins($duration_total, $time_strings->hour_one_short, $time_strings->hour_many_short, $time_strings->minute_one, $time_strings->minute_many);
                $capsuleInfo['duration_capsule_text'] = self::convertToHoursMins($capsuleInfo['duration_capsule'], $time_strings->hour_one_short, $time_strings->hour_many_short, $time_strings->minute_one, $time_strings->minute_many);
                $capsuleInfo['duration_autoevaluation_text'] = self::convertToHoursMins($capsuleInfo['duration_autoevaluation'], $time_strings->hour_one_short, $time_strings->hour_many_short, $time_strings->minute_one, $time_strings->minute_many);
                
                // Resultat final.
                array_push($capsuleCatalog, $capsuleInfo); /* Pourquoi un push? pourrait simplement retourner $capsuleInfo? */
            }
        }
        $rs->close();
        
        return $capsuleCatalog;
    }    
    
    /* Pour test seulement. Déplacer dans librairie utilitaire. */
    /* Solution ajustée de http://stackoverflow.com/questions/8563535/convert-number-of-minutes-into-hours-minutes-using-php */
    /* Format voulu:
     *  1 h (pas d'espace après le h)
     *  1 h 20 minute
     *  1 minute (pas de s, UNE minute, pas d'espace avant le 1).
     *  1 minutes (pas d'espace avant le 1)
     *  2 h 3 minutes
     *  affichage non défini pour 0 minutes (affichage vide)
     */
    /* Nelson m'a aussi suggéré DateInterval, Mohamed l'objet date avec timestamp */
    /* Pas spécifié, comment afficher 1 heure et 2 heures... 1 heure(s) et 2 heure(s) ou 1 heure et 2 heures??? */ 
    public static function convertToHoursMins($time, $hour_one, $hour_many, $minute_one, $minute_many) {
        // One/many
        settype($time, 'integer');
        if ($time < 1) {
            return "";
        }
        $hours = floor($time / 60);
        $minutes = ($time % 60);
        
        $result = '';
        $displayHours = false;
        
        if ($hours > 0) {
           $result .=  $hours . ' ' . ($hours == 1 ? $hour_one : $hour_many);  
        }
        
        if ($hours > 0 && $minutes > 0) {
            $result .= ' ';
        }
        
        if ($minutes > 0) {
            $result .=  $minutes . ' ' . ($minutes == 1 ? $minute_one : $minute_many);
        }
        
        
        return $result;
    }
    
}
