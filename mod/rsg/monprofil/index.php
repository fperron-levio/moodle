<?php
require_once('../../../config.php');
require_once('../../../cohort/lib.php');
require_once __DIR__ . '/../locallib.php';
require_once __DIR__ . '/myprofile_form.php';
require_once __DIR__ . '/mypassword_form.php';
require_once __DIR__ . '/myemail_form.php';

/**
 *
 * Utilise cette technique pour pouvoir tester minimalement le html en externe.
 * On veut pouvoir utiliser des variables php pour avoir une mécanique de template minimale.
 *
 * Exemple utilisation (chargement):<br/>
 * <code>
 *   $data = new stdClass();
 *   $data->title = "Confirmation d'inscription à la plateforme RSG";
 *   $string = get_html('./rsgconfirm_plain.html.php', $data);
 * </code>
 *
 * Exemple d'utilisation des variables dans le html (le fichier doit avoir extension .php):
 * <code>
 *      <title><?php echo $data->title; ?></title>
 * </code>
 * Important: Les variables définies avant l'appel de la fonction en seront pas visibles.
 * $data est visible et devrait être utilisé pour passer les textes dynamiques.
 * Ajouter parametre au besoin.
 * @param string $filename
 * @param object|array $data
 * @return bool|string
 * @link http://www.php.net/manual/fr/function.include.php
 */
function get_html($filename, $data=NULL) {
    if (is_file($filename)) {
        ob_start();
        include $filename;
        return ob_get_clean();
    }
    return false;
}

/**
 *
 * #3743
 * Cette fonction est une remake de lib/moodlelib.php::update_user_login_times
 * TODO : Supprimer le code inutle 
 * @return bool true
 *
 */
function update_user_email_rsg($courriel) {
    global $USER, $DB;

    if (isguestuser()) {
        // Do not update guest access times/ips for performance.
        return true;
    }

    $now = time();

    $user = new stdClass();
    $user->id = $USER->id;
    $user->email = $courriel;

    // Make sure all users that logged in have some firstaccess.
    if ($USER->firstaccess == 0) {
        $USER->firstaccess = $user->firstaccess = $now;
    }

    // Store the previous current as lastlogin.
    $USER->lastlogin = $user->lastlogin = $USER->currentlogin;

    $USER->currentlogin = $user->currentlogin = $now;

    // Function user_accesstime_log() may not update immediately, better do it here.
    $USER->lastaccess = $user->lastaccess = $now;
    $USER->lastip = $user->lastip = getremoteaddr();

    // Note: do not call user_update_user() here because this is part of the login process,
    //       the login event means that these fields were updated.
    $DB->update_record('user', $user);
    $USER->email = $courriel;
    return true;
}

function update_user_renseignements_rsg($data) {
    global $USER, $DB;
    
    if (isguestuser()) {
        // Do not update guest access times/ips for performance.
        return true;
    }
    
    $now = time();
    
    // DÉBUT : S'occupe du numéro de téléphone
    $phone1 = $data->phone1;
    
    $user = new stdClass();
    $user->id = $USER->id;
    $user->phone1 = $phone1;

    // Make sure all users that logged in have some firstaccess.
    if ($USER->firstaccess == 0) {
        $USER->firstaccess = $user->firstaccess = $now;
    }

    // Store the previous current as lastlogin.
    $USER->lastlogin = $user->lastlogin = $USER->currentlogin;

    $USER->currentlogin = $user->currentlogin = $now;

    // Function user_accesstime_log() may not update immediately, better do it here.
    $USER->lastaccess = $user->lastaccess = $now;
    $USER->lastip = $user->lastip = getremoteaddr();

    // Note: do not call user_update_user() here because this is part of the login process,
    //       the login event means that these fields were updated.
    $DB->update_record('user', $user);
    $USER->phone1 = $phone1;
    // FIN : S'occupe du numéro de téléphone
    
    // DÉBUT : S'occupe du bureau coordonnateur
    $current_inscription = $DB->get_record(RSG_INSCRIPTION, array('userid' => $USER->id));
    //$current_inscription->coordofficeid = $data->rsg_region_office[1]; // le [0] étant la région
    $current_inscription->timemodified = $now; // le [0] étant la région
    $DB->update_record(RSG_INSCRIPTION, $current_inscription);
    // FIN : S'occupe du bureau coordonnateur
    
    return true;
}

function getidcohort($cohortname) {
    global $DB;
    return  $DB->get_record_sql('SELECT c.id FROM mdl_cohort c WHERE c.name = :name', array('name'=>$cohortname));
}


$PAGE->set_url('/mod/rsg/monprofil');

require_login();
$context=  context::instance_by_id(1);
$PAGE->set_context($context);

// Tâche #3845 - Obtenir que « Mon profil » ne s'affiche plus suite à la déconnexion
$PAGE->set_cacheable(false);
// fin Tâche #3845

// load the appropriate auth plugin
$userauth = get_auth_plugin($USER->auth);

if (!$userauth->can_change_password()) {
    print_error('nopasswordchange', 'auth');
}

$mform              = new rsg_myprofile_form();
$mform_mot_de_passe = new rsg_mypassword_form();
$mform_courriel     = new rsg_myemail_form();

$page_title = get_string('myprofile_page_title', 'mod_rsg');
$PAGE->set_pagelayout('incourse');
$PAGE->set_title($page_title);

// TEMPORAIRE: La librairie était auparavant incluse directement (en html) dans pages-statiques.php.
// Lorsqu'on fait travaille avec TOUTES les pages sauf le login, il faut travailler avec le Bootstrap de Moodle.
$PAGE->requires->js("/theme/cleanrsg/javascript/themeByPass/bootstrap_2.3.2/js/bootstrap.min.js", true);
$PAGE->requires->css('/theme/cleanrsg/javascript/bootstrap-editable/css/bootstrap-editable.css');
$PAGE->requires->js('/theme/cleanrsg/javascript/bootstrap-editable/js/bootstrap-editable.js');
$PAGE->requires->js("/mod/rsg/javascript/rsgApp.js");

/* IMPORTANT: Angular doit être chargé dans le header afin que le directive ng-cloak puisque fonctionner correctement. */
$PAGE->requires->js("/mod/rsg/javascript/angular-1.2.23.js", true);
$PAGE->requires->js("/mod/rsg/javascript/angular-animate-1.2.23.js", true);
$PAGE->requires->js_init_call("init_rsgApp", null, false);

if ($mform->is_cancelled() || $mform_mot_de_passe->is_cancelled() || $mform_courriel->is_cancelled()) {
    redirect($CFG->wwwroot.'/mod/rsg/catalogue');
} else if ($data = $mform_mot_de_passe->get_data()) {

    if (!$userauth->user_update_password($USER, $data->password)) {
        print_error('errorpasswordupdate', 'auth');
    }

    // Reset login lockout - we want to prevent any accidental confusion here.
    login_unlock_account($USER);

    // register success changing password
    unset_user_preference('auth_forcepasswordchange', $USER);
    unset_user_preference('create_password', $USER);

    $strpasswordchanged = get_string('profile_passwordchanged','mod_rsg');

    $fullname = fullname($USER, true);

    $PAGE->set_title($strpasswordchanged);
    echo $OUTPUT->header();

    notice($strpasswordchanged, new moodle_url($PAGE->url, array('return'=>1)));

    echo $OUTPUT->footer();
    exit;
} else if ($data = $mform_courriel->get_data()) {
    
    $strcourrielchanged = get_string('courriel_changed','mod_rsg');
    if(!update_user_email_rsg($data->courriel)) {
        $strcourrielchanged = get_string('courriel_not_changed','mod_rsg');
    }   
    
    // Reset login lockout - we want to prevent any accidental confusion here.
    // login_unlock_account($USER);

    // register success changing password
    // unset_user_preference('auth_forcepasswordchange', $USER);
    // unset_user_preference('create_password', $USER);

    // $strpasswordchanged = get_string('passwordchanged');
    // $strcourrielchanged = get_string('courriel_changed','mod_rsg');
    
    $fullname = fullname($USER, true);

    $PAGE->set_title($strcourrielchanged);
    echo $OUTPUT->header();

    notice($strcourrielchanged, new moodle_url($PAGE->url, array('return'=>1)));

    echo $OUTPUT->footer();
    exit;
}else if($data = $mform->get_data()){
    $strRenseignementsChanged = get_string('renseignements_changed','mod_rsg');
    
    if(!update_user_renseignements_rsg($data)) {
        $strRenseignementsChanged = get_string('renseignements_not_changed','mod_rsg');
    }   
    
    $PAGE->set_title($strRenseignementsChanged);
    echo $OUTPUT->header();

    notice($strRenseignementsChanged, new moodle_url($PAGE->url, array('return'=>1)));

    echo $OUTPUT->footer();
    exit;
}

echo $OUTPUT->header();

global $USER;

$string                 = $mform->render();
$string_mot_de_passe    = $mform_mot_de_passe->render();
$string_courriel        = $mform_courriel->render();

$form = new \stdClass();
$form->content = $string;
$form->content_mot_de_passe = $string_mot_de_passe;
$form->content_courriel = $string_courriel;

echo '<div class="rsg_page_title">'.$page_title.'</div>';

// #4787 desactiver la modification de la profil pour tous les utilisateurs qui sont dans la cohorte bc
if (cohort_is_member(getidcohort('bc')->id,$USER->id)) {
    echo get_html(__DIR__.'/view/profile.tmp.bc.php', $form);
}
else {
    echo get_html(__DIR__.'/view/profile.tmp.php', $form);
}

echo "
<script>
$('#myTab a').click(function(e) {
  e.preventDefault();
  $(this).tab('show');
});
// s'il y a des erreurs
if ($('#mon_mot_de_passe').find('.error').length>0) {
  $('#myTab a[href=\"#mon_mot_de_passe\"]').tab('show');
}
else if ($('#mon_courriel').find('.error').length>0) {
  $('#myTab a[href=\"#mon_courriel\"]').tab('show');
}
</script>
";


// #3743
echo '
<script src="' . $CFG->wwwroot . '/mod/rsg/formpassword.js"></script>';

echo $OUTPUT->footer();