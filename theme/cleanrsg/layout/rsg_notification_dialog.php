<?php 
    global $USER, $PAGE;
    $event=  isset($USER->rsg->on)?$USER->rsg->on:NULL;
    if ($event!=NULL){
        require_once $CFG->dirroot.'/auth/rsg/classes/RSGEvent.php';
        foreach ($event as $key => $value) {
            $on=$key.'_'.$value;
            RSGEvent::$on();
        }
        unset($USER->rsg->on);
    }
//le true à la fin du call inndique que le call est fait après que le DOM est ready
$PAGE->requires->js_init_call('M.auth_rsg.showPopUp',null,true);