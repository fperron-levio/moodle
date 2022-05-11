<?php
/**
 * Created by PhpStorm.
 * User: nmoller
 * Date: 14-06-30
 * Time: 15:13
 */


class mod_rsg_observers{
    public static function test($event){
        //error_log('event-triggered');
        global $DB;
        $data=$event->get_data();

        //pour la fase de test, avoir un cours avec plusieurs rsgs simplifie la tâche...
        //sinon la relation cours->mod_rsg devrait être un-à-un.
        //$caps=$DB->get_records('rsg',array('course'=>$data['courseid']),'');
        //on prendra le premier $caps[1];
        global $USER;
        $cap_id=$USER->rsg->id;




        $tracking = $DB->get_record('rsg_track', array('rsgid' => $cap_id, 'userid' => $data['userid']), '*');

        if ($tracking->timeadduec != 0) //nothing to do.....
            return;

        $last_attemp_ob=self::logic($DB,$data['contextinstanceid'],$data['userid']);
        //if ($last_attemp_ob->sumgrades==100){//on s'assure de que tout soit répondu
        if ($last_attemp_ob){ //on valide que l'étudiant aie fait sa soumission
            $tracking->timeadduec=$last_attemp_ob->timefinish;
            $DB->update_record('rsg_track', $tracking);
        }
    }

    public static function logic($DB, $quizId, $userId){
        $quiz_cm = $DB->get_record('course_modules', array('id' => $quizId), '*', MUST_EXIST);
        //Valider l'état de l'évaluation
        //TODO: Revoir
        $user_attemp = quiz_get_user_attempts($quiz_cm->instance, $userId, 'finished', true);
        $attemps= array_values($user_attemp);
        return $attemps[count($attemps)-1];
    }
}