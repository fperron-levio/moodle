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
 * Question type class for the rsgtype question type.
 *
 * @package    qtype
 * @subpackage rsgtype
 * @copyright  Cégep à distance
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/questionlib.php');


/**
 * The rsgtype question type.
 *
 * @copyright  2005 Mark Nielsen
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class qtype_rsgtype extends question_type {

    /**
     * La table qtype_rsgtype_options est créée automatiquement une fois que l'on ajoute le
     * nouveau type.
     * @param object $question
     * @return bool|void
     */
    public function get_question_options($question) {
        global $DB;
        $question->options = $DB->get_record('qtype_rsgtype_options',
                array('questionid' => $question->id), '*', MUST_EXIST);
        parent::get_question_options($question);
    }

    public function save_question_options($formdata) {

        global $DB;
        $context = $formdata->context;

        $options = $DB->get_record('qtype_rsgtype_options', array('questionid' => $formdata->id));
        if (!$options) {
            $options = new stdClass();
            $options->questionid = $formdata->id;
            $options->id = $DB->insert_record('qtype_rsgtype_options', $options);
        }
        /*
         * je vais modifier les éléments démandés
         */
        $options->responsefieldlines = $formdata->responsefieldlines;
        $options->checkshow = (isset($formdata->checkshow)?$formdata->checkshow:0);

        $DB->update_record('qtype_rsgtype_options', $options);
    }

    protected function initialise_question_instance(question_definition $question, $questiondata) {
        parent::initialise_question_instance($question, $questiondata);
        $question->responsefieldlines = $questiondata->options->responsefieldlines;
        $question->checkshow = (isset($questiondata->options->checkshow)?$questiondata->options->checkshow:0);
    }

    public function delete_question($questionid, $contextid) {
        global $DB;

        $DB->delete_records('qtype_rsgtype_options', array('questionid' => $questionid));
        parent::delete_question($questionid, $contextid);
    }


    /**
     * @return array the choices that should be offered for the input box size.
     */
    public function response_sizes() {
        $choices = array();
        for ($lines = 5; $lines <= 40; $lines += 5) {
            $choices[$lines] = get_string('nlines', 'qtype_rsgtype', $lines);
        }
        return $choices;
    }

}

