<?php

/**
 * Login library file of login/password related Moodle functions.
 *
 * @package    rsg_core
 * @subpackage lib
 * @copyright  CRosemont
 * @copyright  Mohamed Alami Chahboune
 */
define('PWRESET_STATUS_NOEMAILSENT', 1);
define('PWRESET_STATUS_TOKENSENT', 2);
define('PWRESET_STATUS_OTHEREMAILSENT', 3);
define('PWRESET_STATUS_ALREADYSENT', 4);


/**
 *  Processes a user's request to set a new password in the event they forgot the old one.
 *  If no user identifier has been supplied, it displays a form where they can submit their identifier.
 *  Where they have supplied identifier, the function will check their status, and send email as appropriate.
 */
function rsg_core_login_process_username_resend_request() {

    global $DB, $OUTPUT, $CFG, $PAGE;
    $systemcontext = context_system::instance();
    $mform = new login_forgot_numeroidentification_form();

    if ($mform->is_cancelled()) {
        redirect(get_login_url());

    } else if ($data = $mform->get_data()) {
        // Requesting user has submitted form data.
        // Next find the user account in the database which the requesting user claims to own.
       
        if (!empty($data->email)) {
            // Try to load the user record based on email address.
            // this is tricky because
            // 1/ the email is not guaranteed to be unique - TODO: send email with all usernames to select the account for pw reset
            // 2/ mailbox may be case sensitive, the email domain is case insensitive - let's pretend it is all case-insensitive.

            $select = $DB->sql_like('email', ':email', false, true, false, '|') .
                    " AND mnethostid = :mnethostid AND deleted=0 AND suspended=0";
            $params = array('email' => $DB->sql_like_escape($data->email, '|'), 'mnethostid' => $CFG->mnet_localhost_id);
            $user = $DB->get_record_select('user', $select, $params, '*', IGNORE_MULTIPLE);
        }

        // Target user details have now been identified, or we know that there is no such account.
        // Send email address to account's email address if appropriate.
         
        $pwresetstatus = PWRESET_STATUS_NOEMAILSENT;
        if ($user and !empty($user->confirmed)) {

            $userauth = get_auth_plugin($user->auth);

            if (!empty($data->email)) {
                $sendresult = rsg_send_username_email($user);
                if ($sendresult) {
                    $pwresetstatus = PWRESET_STATUS_TOKENSENT;
                } else {
                    print_error('cannotmailconfirm');
                }
            }
        }


        // Any email has now been sent.
        // Next display results to requesting user if settings permit.
        echo $OUTPUT->header();

        if (!empty($CFG->protectusernames)) {
            // Neither confirm, nor deny existance of any username or email address in database.
            // Print general (non-commital) message.
            // notice(get_string('emailpasswordconfirmmaybesent'), $CFG->wwwroot.'/index.php');
            notice(get_string('emailusernameconfirmmaybesent'), $CFG->wwwroot.'/index.php');
            die; // Never reached.
        } else if (empty($user)) {
            // Protect usernames is off, and we couldn't find the user with details specified.
            // Print failure advice.
            notice(get_string('emailpasswordconfirmnotsent'), $CFG->wwwroot.'/forgot_password.php');
            die; // Never reached.
        } else if (empty($user->email)) {
            // User doesn't have an email set - can't send a password change confimation email.
            notice(get_string('emailpasswordconfirmnoemail'), $CFG->wwwroot.'/index.php');
            die; // Never reached.
        } else if ($pwresetstatus == PWRESET_STATUS_ALREADYSENT) {
            // User found, protectusernames is off, but user has already (re) requested a reset.
            // Don't send a 3rd reset email.
            $stremailalreadysent = get_string('emailalreadysent');
            notice($stremailalreadysent, $CFG->wwwroot.'/index.php');
            die; // Never reached.
        } else if ($pwresetstatus == PWRESET_STATUS_NOEMAILSENT) {
            // User found, protectusernames is off, but user is not confirmed.
            // Pretend we sent them an email.
            // This is a big usability problem - need to tell users why we didn't send them an email.
            // Obfuscate email address to protect privacy.
            $protectedemail = preg_replace('/([^@]*)@(.*)/', '******@$2', $user->email);
            $stremailpasswordconfirmsent = get_string('emailpasswordconfirmsent', '', $protectedemail);
            notice($stremailpasswordconfirmsent, $CFG->wwwroot.'/index.php');
            die; // Never reached.
        } else {
            // Confirm email sent. (Obfuscate email address to protect privacy).
            $protectedemail = preg_replace('/([^@]*)@(.*)/', '******@$2', $user->email);
            // This is a small usability problem - may be obfuscating the email address which the user has just supplied.
            $stremailresetconfirmsent = get_string('emailresetconfirmsent', '', $protectedemail);
            notice($stremailresetconfirmsent, $CFG->wwwroot.'/index.php');
            die; // Never reached.
        }
        die; // Never reached.
    }

    // Make sure we really are on the https page when https login required.
    $PAGE->verify_https_required();

    // DISPLAY FORM.

    echo $OUTPUT->header();
   // echo $OUTPUT->box(get_string('passwordforgotteninstructions2'), 'generalbox boxwidthnormal boxaligncenter');
    echo $OUTPUT->box(get_string('auth_usernameforgotten','auth_rsg'),'rsg_form_title');
    $mform->display();

    echo $OUTPUT->footer();
}


function rsg_send_username_email($user) {
    global $CFG;

    $site = get_site();
    $supportuser = core_user::get_support_user();
    $pwresetmins = isset($CFG->pwresettime) ? floor($CFG->pwresettime / MINSECS) : 30;

    $data = new stdClass();
    $data->firstname = $user->firstname;
    $data->lastname  = $user->lastname;
    $data->username  = $user->username;
    $data->sitename  = format_string($site->fullname);
   // $data->link      = $CFG->httpswwwroot .'/login/forgot_password.php?token='. $resetrecord->token;
    $data->admin     = generate_email_signoff();
    $data->resetminutes = $pwresetmins;

    $message = html_to_text(get_string('auth_usernameshow', 'auth_rsg', $data));
    $subject = get_string('auth_usernameshowsubject', 'auth_rsg', format_string($site->fullname));

    // Directly email rather than using the messaging system to ensure its not routed to a popup or jabber.
    return email_to_user($user, $supportuser, $subject, $message);

}
