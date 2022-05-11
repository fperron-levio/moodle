<?php

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {
    $settings->add(new admin_setting_heading('rsg_method_heading', get_string('generalconfig', 'rsg'),
                       get_string('explaingeneralconfig', 'rsg')));
    
    global $DB;
    
    $options = array();
    $course_cat=$DB->get_records('course_categories');
    
    foreach ($course_cat as $cat){
        $options[$cat->id]=$cat->name;
    }
    
    //die();
   
    $settings->add(new admin_setting_configselect('rsg/green_cat', get_string('green_cat', 'rsg'),
                       get_string('config_green_cat', 'rsg'), null, $options));
    
    $settings->add(new admin_setting_configselect('rsg/rose_cat', get_string('rose_cat', 'rsg'),
                       get_string('config_rose_cat', 'rsg'), null, $options));
    
    $settings->add(new admin_setting_configselect('rsg/violet_cat', get_string('violet_cat', 'rsg'),
                       get_string('config_violet_cat', 'rsg'), null, $options));

    $settings->add(new admin_setting_configselect('rsg/orange_cat', get_string('orange_cat', 'rsg'),
                       get_string('config_orange_cat', 'rsg'), null, $options));

}
