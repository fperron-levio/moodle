<?php

/**
 *
 * Authentication Plugin: RSG Authentication
 *
 * Controls the signup and the authentication to rsg.
 * Contributed by Nelson Moller <nmoller@cegepadistance.ca>
 * 
 *
 * @package auth_rsg
 * @author Nelson Moller
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir.'/authlib.php');

/**
 * RSG authentication plugin.
 *
 * @category Drives the authentication and the sign-up of a RSG into our instance.
 */
class auth_plugin_rsg extends auth_plugin_base {
   // function auth_plugin_rsg()
   function __construct() {
        $this->authtype = 'rsg';
        $this->config = get_config('auth/rsg');
    }
    
    function can_signup() {
        return true;
    }

    /**
     * Returns the path to the sign_up form of the plugin
     *
     *
     * @return bool
     *
     * @uses /auth/rsg/form/signup_form.php
     */
    function signup_form() {
        global $CFG;
        require_once($CFG->dirroot.'/auth/rsg/form/signup_form.php');
        return new rsg_signup_form();
    } 
    
    
    /**
     * Returns true if this authentication plugin can change the user's
     * password.
     *
     * @return bool
     */
    function can_change_password() {
        return true;
    }

    /**
     * Returns true if this authentication plugin can reset the user's
     * password.
     *
     * @return bool
     */
    function can_reset_password() {
        //override if needed
        return true;
    }
    
    /**
     * Returns true if plugin allows confirming of new users.
     *
     * @return bool
     */
    function can_confirm() {
        return true;
    }

    /**
     * Sign up a new user ready for confirmation.
     * Password is passed in plaintext.
     *
     * Note: le code de cette fonction est majoritairement tiré de auth/email/auth.php
     * Voir tâche #3168 pour détails
     *
     * @param object $user new user object
     * @param boolean $notify print notice with link and terminate
     */
    function user_signup($user, $notify=true) {
        global $CFG, $DB;
        require_once($CFG->dirroot.'/user/profile/lib.php');
        require_once($CFG->dirroot.'/user/lib.php');

        $user->username = $user->numeroidentification;

        if ($userExists = $DB->get_record('user', array('username'=>$user->username))) {
            print_error('auth_rsg_userexists_error','auth_rsg', "$CFG->wwwroot/index.php");
        }

        // Valider si l'utilisateur existe dans la table des RSG représentées (rsg_mfa_import), afin de valider s'il a bien accès au contenu gratuit (active == 1)
        $coordoffice_mfa_id = $DB->get_record('rsg_coord_office', array('id'=>$user->rsgoffice[0]));
        $mfa_import = $DB->get_record('rsg_mfa_import', array('numeroidentification'=>$user->username, 'coordofficeid'=>$coordoffice_mfa_id->officeid));

        if(!$mfa_import) {
            print_error('auth_rsg_recordnotfound_mfa_import','auth_rsg');
        }

        if ($mfa_import->active != 1) {
            print_error('auth_rsgaccessrevoked','auth_rsg');
        }

        $user->password = hash_internal_user_password($user->password);
        if (empty($user->calendartype)) {
            $user->calendartype = $CFG->calendartype;
        }

        $user->id = user_create_user($user, false);

        // Ajout de l'utilisateur à la table mdl_rsg_inscription
        $rsgInscription=new stdClass();
        $rsgInscription->userid=$user->id;
        $rsgInscription->coordofficeid=$user->rsgoffice[0];
        $rsgInscription->numeroidentification=$user->username;
        $rsgInscription->status=$user->rsgstatus;
        $rsgInscription->timecreated=time();
        $rsgInscription->timemodified=0;
        $DB->insert_record('rsg_inscription', $rsgInscription);

        // Save any custom profile field information.
        profile_save_data($user);

        if (! send_confirmation_email($user)) {
            print_error('auth_emailnoemail','auth_email');
        }

        if ($notify) {
            global $CFG, $PAGE, $OUTPUT;
            $emailconfirm = get_string('emailconfirm');
            $PAGE->navbar->add($emailconfirm);
            $PAGE->set_title($emailconfirm);
            $PAGE->set_heading($PAGE->course->fullname);
            echo $OUTPUT->header();
            notice(get_string('emailconfirmsent', '', $user->email), "$CFG->wwwroot/index.php");
        } else {
            return true;
        }
    }

    /**
     * Confirm the new user as registered.
     *
     * @param string $username
     * @param string $confirmsecret
     */
    function user_confirm($username, $confirmsecret) {
        global $DB;
        $user = get_complete_user_data('username', $username);

        if (!empty($user)) {
            if ($user->confirmed) {
                return AUTH_CONFIRM_ALREADY;

            } else if ($user->auth != $this->authtype) {
                return AUTH_CONFIRM_ERROR;

            } else if ($user->secret == $confirmsecret) {   // They have provided the secret key to get in
                $DB->set_field("user", "confirmed", 1, array("id"=>$user->id));
                if ($user->firstaccess == 0) {
                    $DB->set_field("user", "firstaccess", time(), array("id"=>$user->id));
                }
                return AUTH_CONFIRM_OK;
            }
        } else {
            return AUTH_CONFIRM_ERROR;
        }
    }
    
    function prevent_local_passwords() {
        return false;
    }
    
    /**
     * Updates the user's password.
     *
     * called when the user password is updated.
     *
     * @param  object  $user        User table object  (with system magic quotes)
     * @param  string  $newpassword Plaintext password (with system magic quotes)
     * @return boolean result
     *
     */
    function user_update_password($user, $newpassword) {
        $user = get_complete_user_data('id', $user->id);
        // This will also update the stored hash to the latest algorithm
        // if the existing hash is using an out-of-date algorithm (or the
        // legacy md5 algorithm).
        return update_internal_user_password($user, $newpassword);
    }
    
    /**
     * Returns true if the username and password work and false if they are
     * wrong or don't exist.
     *
     * @param string $username The username
     * @param string $password The password
     * @return bool Authentication success or failure.
     */
    function user_login ($username, $password) {
        global $CFG, $DB;
               
        if ($username && $password) {

            // L'usager n'aura pas accès s'il figure dans la table rsg_mfa_import et qu'il est à active == 0
            $mfa_import = $DB->get_record('rsg_mfa_import', array('numeroidentification'=>$username, 'active'=>'1'));
        
            if (!$mfa_import) {
                // Tâche #3843
                /*
                $data = new stdClass();
                $data->faq_url = $CFG->httpswwwroot .'/mod/rsg/faq/#echec_connexion';
                $message = get_string('auth_rsgechecconnexion', 'auth_rsg', $data);      
                notice($message, "$CFG->wwwroot/index.php");
                */
                global $PAGE;
                $PAGE->set_pagelayout('base');
                print_error('invalidlogin');
                // fin Tâche #3843
            }
     
        }

        // #3774 (suppression de commentaires faits dans la tâche #3716)
        if ($user = $DB->get_record('user', array('username'=>$username, 'mnethostid'=>$CFG->mnet_localhost_id))) {
             return validate_internal_user_password($user, $password);
        }

        return false;
    }

    
}
