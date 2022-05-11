<?php

/**
 * Description of RSGUser
 *
 * @author Nelson Moller <nmoller at cegepadistance.ca>
 * Cette classe sera responsable d'implémenter tous les traitement se rapportant à RSG:
  sign_up, abonnement, .....
 */

namespace auth\rsg;
global $CFG;

require_once __DIR__.'/../../../config.php';

/**
 * Class RSGUser
 * @package auth\rsg
 */
class RSGUser {

    /**
     * Au minimum on doit passer l'email de l'utilisateur
     *
     * @var string
     */
    protected $email = '';
    /**
     * Le user de la table mdl_user
     * @var mixed
     */
    protected $user;
    /**
     * L'id du bureau coordonnateur du RSG
     * @var int
     */
    protected $idBureau;
    /**
     * Le telephone de user
     * @var int
     */
    protected $phone1;


    /**
     * on passera ce qui vient du formulaire signup_form
     * pour la suite, abonnment on modifiera selon le deuxième paramètre
     *
     * @param $user
     * @param bool $fromSignUp
     */
    function __construct($user, $fromSignUp=TRUE) {
        global $DB;
        if ($fromSignUp){
            $this->email=$user->email;
            $this->phone1=$user->phone1;
            $this->idBureau = $user->rsgoffice[0];
        }

        if (self::emailExists($user->email)) {
            $this->user = $DB->get_record('user', array('email' => $user->email));
            
            if ($fromSignUp) {
                $changes=FALSE;
                if ($this->user->firstname!==$user->firstname){
                    $changes=TRUE;
                    $this->user->firstname=$user->firstname;
                }
                if ($this->user->lastname!==$user->lastname){
                    $changes=TRUE;
                    $this->user->lastname=$user->lastname;
                }
                if ($this->user->phone1!==$user->phone1){
                    $changes=TRUE;
                    $this->user->phone1=$user->phone1;
                }
                if ($changes){
                    $DB->update_record('user', $this->user);
                }
                //l'utilisateur a choisi un nouveau mot de passe
                $comp=validate_internal_user_password($this->user, $user->password);
                if (!$comp){
                    update_internal_user_password($this->user, $user->password);
                }
            }
        } else {
            // todo: Génération du matricule devrait être plus "visible".
            // fonction devra éventuellement être remplacé lorsque la structure du matricule sera connue.
            $hash = "void";
            $user->username = 'test' . $hash;
            
            $user->confirmed = 0; // on changera l'état au moment de recevoir le payment
            $user->password = hash_internal_user_password($user->password);
            
            $user->id = $DB->insert_record('user', $user);

            $this->user = $DB->get_record('user', array('id' => $user->id));
            events_trigger('user_created', $this->user);
        }
    }

    /**
     * Valide si cet email est déjà enregistré
     *
     * @param $email
     * @return bool
     */
    static function emailExists($email) {
        global $DB;
        return $DB->record_exists('user', array('email' => $email));
    }

    /**
     * Returne l'user moodle
     *
     * @return mixed
     */
    function getUser(){
        return $this->user;
    }

    /**
     * L'utilisateur est-il un RSG?
     * @return bool
     */
    function isRSG() {
        return $this->user->auth === 'rsg';
    }

    /**
     * L'utilisateur est-il déjà confirmé?
     * @return bool
     */
    function isConfirmed() {
        return $this->isRSG() && $this->user->confirmed === 1;
    }

    /**
     * Getter
     * @return string
     */
    function getFirstName() {
        return $this->user->firstname;
    }

    /**
     * Getter
     * @return string
     */
    function getLastName() {
        return $this->user->lastname;
    }

    /**
     * Getter
     * @return string
     */
    function getUsername() {
        return $this->user->username;
    }

    /**
     * Getter
     * @return integer
     */
    function getId(){
        return $this->user->id;
    }

    /**
     * Getter
     * @return long integer
     */
    function getSecret(){
        return $this->user->secret;
    }

    /**
     * Getter
     * @return integer
     */
    function getIdOffice(){
        return $this->idBureau;
    }

    /**
     * cette fonction est pour être appellé au moment de la validation du fomulaire
     * @param $email
     * @return bool
     */
    static function existsUser($email){
        if (self::emailExists($email)){
            global $DB;
            $user=$DB->get_record('user', array('email' => $email), 'auth, confirmed');
            if ($user->auth!=='rsg' )
                return TRUE;
            else if ($user->confirmed==='1'){
                return TRUE;
            }
        }
        return FALSE;
    }

    /**
     * Si l'utilisateur est un RSG retourne le timestamp de son inscription= premier payment
     * @return int
     */
    function getInscriptionTimestamp(){
        if ($this->user->auth=='rsg'){
            global $DB;

            $inscription=$DB->get_record('rsg_inscription',array('userid'=>$this->user->id),'timecreated');
            return $inscription->timecreated;
        } else{
            return 0;
        }
    }

    /**
     * Retourne le nombre d'années depuis son inscription en commencant par 1...
     * @return int
     */
    function getInscriptionYears(){
        $startDate= $this->getInscriptionTimestamp();
        $year_def=365*60*60*24;
        $years=(time()-$startDate)/$year_def;
        return (int)floor($years)+1;
    }

    /**
     * Un array contenant les timestamps de début et fin...
     * [0]->inscription
     * [1]->fin premier année
     * [2]->fin deuxième anné
     * Cette fonction est prête pour produire les certificats selon la date d'obtention des UECs entre dans la période..
     * @return array
     */
    function getPeriodsEndTimestamps(){
        $years=$this->getInscriptionYears();
        $ret[0]=$this->getInscriptionTimestamp();
        for ($i=1;$i< $years+1;$i++){
            $ret[$i]=strtotime('+'.$i.' year', $ret[0]);
        }

        return $ret;

    }

    /**
     * Retourne un array avec les éléments de track correspondants aux rsg_mod complétés (l'autoévaluation a été
     * finie) ordonnés en ordre asc selon le timeadduec
     *
     * @return array
     */
    function getEndedTracks(){
        global $DB;
        $where="userid=".$this->user->id." and timeadduec <> '0'";
        $tracks=$DB->get_records_select('rsg_track',$where,null,'timeadduec');
        $ret=array_values($tracks);
        return $ret;
    }

    /**
     * On a un array commencant en 0 pour chaque année qui contient un array
     * avec rsgid-timeadduec... Je prends comme modèle le certificat produit lors du demo
     * contenant Nom de la capsule/ Date de finalisation/ UEC
     *
     * @return array
     */
    function getCertificateInfoArray(){
        $tracks=$this->getEndedTracks();
        $ret=array();

        $periods=$this->getPeriodsEndTimestamps();
        for($i=1;$i<count($periods);$i++){
            $ret[$i-1]=array();
            foreach($tracks as $track){
                if ($periods[$i-1]<= $track->timeadduec && $track->timeadduec < $periods[$i]){
                    array_push($ret[$i-1],$track->rsgid.'-'.$track->timeadduec);
                }
                if ($track->timeadduec >= $periods[$i])
                    break;
            }
        }
        return $ret;
    }

}
