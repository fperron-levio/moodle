<?php
/**
 * Created by PhpStorm.
 * User: nmoller
 * Date: 14-06-30
 * Time: 15:11
 */

$observers = array(
    array(
        'eventname' => '\mod_quiz\event\attempt_submitted', //j'essaye avec le legacy_eventname
        //'includefile' => '/mod/rsg/classes/rsg_event.php',
        'callback' => 'mod_rsg_observers::test',

    ),
);