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
 * The main rsg configuration form
 *
 * It uses the standard core Moodle formslib. For more info about them, please
 * visit: http://docs.moodle.org/en/Development:lib/formslib.php
 *
 * @package    mod_rsg
 * @copyright  2011 Your Name
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/course/moodleform_mod.php');

/**
 * Module instance settings form
 */
class mod_rsg_mod_form extends moodleform_mod {

    /**
     * Defines forms elements
     */
    public function definition() {

        $mform = $this->_form;

        //-------------------------------------------------------------------------------
        // Adding the "general" fieldset, where all the common settings are showed
        $mform->addElement('header', 'general', get_string('general', 'form'));
        global $COURSE;
        $course_title=$COURSE->fullname;

        // Adding the standard "name" field
        $mform->addElement('text', 'name', get_string('capsulename', 'rsg'), array('size'=>'64', 'value'=>$course_title));
        if (!empty($CFG->formatstringstriptags)) {
            $mform->setType('name', PARAM_TEXT);
        } else {
            $mform->setType('name', PARAM_CLEAN);
        }
        $mform->addRule('name', null, 'required', null, 'client');
        $mform->addRule('name', get_string('maximumchars', '', 255), 'maxlength', 255, 'client');
        $mform->addHelpButton('name', 'modulename', 'rsg'); /* todo: Devrait pas être aide du module mais sur le champs "name". */
        
        // Adding the standard "intro" and "introformat" fields
        //$this->add_intro_editor();

        //-------------------------------------------------------------------------------
        // Adding the rest of rsg settings, spreeading all them into this fieldset
        // or adding more fieldsets ('header' elements) if needed for better logic
        
        //Ajouter le nombre de UEC qui cette capsule ajoute
        $mform->addElement('text', 'uec', get_string('uec', 'rsg'), array('size'=>'64'));
        $mform->setType('uec', PARAM_FLOAT);
        
        $mform->addRule('uec', null, 'required', null, 'client');
        $mform->addRule('uec', null, 'numeric', null,  'client');
        $mform->addHelpButton('uec', 'uec', 'rsg');
        
        // Durée de la capsule en minutes.
        $mform->addElement('text', 'duration_capsule', get_string('duration_capsule', 'rsg'));
        $mform->setType('duration_capsule', PARAM_INT);
        $mform->addRule('duration_capsule', null, 'numeric', null,  'client');
        $mform->addHelpButton('duration_capsule', 'duration_capsule', 'rsg');
        
        // Durée de l'autoevaluation en minutes.
        $mform->addElement('text', 'duration_autoevaluation', get_string('duration_autoevaluation', 'rsg'));
        $mform->setType('duration_autoevaluation', PARAM_INT);
        $mform->addRule('duration_autoevaluation', null, 'numeric', null,  'client');
        $mform->addHelpButton('duration_autoevaluation', 'duration_autoevaluation', 'rsg');
        
        /* TODO: VALIDER formatstringstriptags et PARAM_CLEAN */
        /* On voudrait le texte sans html mais ce param est documenté comme obsolète. */
        $mform->addElement('textarea', 'keywords', get_string('keywords', 'rsg'));
        if (!empty($CFG->formatstringstriptags)) {
            $mform->setType('keywords', PARAM_TEXT);
        } else {
            $mform->setType('keywords', PARAM_CLEAN);
        }
        $mform->addRule('keywords', null, 'maxlength', 1000,  'client');
        $mform->addHelpButton('keywords', 'keywords', 'rsg');
        
        //-------------------------------------------------------------------------------
        // add standard elements, common to all modules
        $this->standard_coursemodule_elements();
        //-------------------------------------------------------------------------------
        // add standard buttons, common to all modules
        $this->add_action_buttons();
    }
}
