<?php
/*
 Description: Restrict access & control redirect 
 // Member
 // Admin
 /-> Path
 /-> Direcotry

 By ACM - Mohamed Alami Chahboune 
*/

if (php_sapi_name() == "cli") {
    // In cli-mode. Routing can only be used when running on web server, so skip routing.
} else {

    global $USER;

    $isRedirect = false;
    $siteadmins = explode(',', $CFG->siteadmins);
    $isadmin 	= in_array($USER->id, $siteadmins);

    if(!$isadmin)
    {
        $wwwroot_parsed = parse_url($CFG->wwwroot);
        if(isset($wwwroot_parsed['path']) && isset( $_SERVER['REQUEST_URI']))
            $clean_uri_path = str_replace($wwwroot_parsed['path'],"", $_SERVER['REQUEST_URI']);
        else
            $clean_uri_path = $_SERVER['REQUEST_URI'];

        // Restrict Path
        foreach ($CFG->rsg_restricted_paths as $path => $config)
        {
            if (strpos($clean_uri_path,$path))
            {
                foreach($config['parameters'] as $key => $value)
                {
                    if(!array_key_exists($key, $_GET))
                        $isRedirect = true;
                    else
                        if (isset($value) && !empty($value))
                            if($_GET[$key] != $value)
                                $isRedirect = true;
                    
                    if($isRedirect)
                    {
                        $home = new \moodle_url($config['url_redirection']);
                        redirect($home);  
                    }
                }        
            } 
        }

        // Restrict Directorys
        foreach($CFG->rsg_blocked_directorys as $current_blocked_directory)
        {
            if (strpos($clean_uri_path,$current_blocked_directory) !== false)
            {
                //On redirige vers le profile rsg de l'utilisateur
                if (strpos($clean_uri_path,'user/profile.php?id=')){
                    $profile = new \moodle_url('/mod/rsg/monprofil/');
                    redirect($profile);
                }

                //echo 'Matched and blocked';
                header("Location:{$CFG->wwwroot}/404.php");
                exit();
            }
        }
   }

    # Restrict
    /* 
        Scorm : the scorm path must have 3 parameters
        Path mod/scorm/player.php
        Rules mandatory {cm / display=popup / evaluationUrl}
    */

    # Allow
    /*
    /login/
    /auth/rsg/
    /mod/quiz/
    /mod/rsg/
    /mod/scorm/
    */
}
