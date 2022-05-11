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
 * Defines the editing form for the rsgtype question type.
 *
 * @package    qtype
 * @subpackage rsgtype
 * @copyright  2007 Jamie Pratt me@jamiep.org
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();


/**
 * rsgtype question type editing form.
 *
 * @copyright  2007 Jamie Pratt me@jamiep.org
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class qtype_rsgtype_edit_form extends question_edit_form {

    protected function definition_inner($mform) {
        $qtype = question_bank::get_qtype('rsgtype');

        $mform->addElement('checkbox', 'checkshow', 'Afficher select');
        $mform->setDefault('checkshow', 1);

        $mform->addElement('select', 'responsefieldlines',
                get_string('responsefieldlines', 'qtype_rsgtype'), $qtype->response_sizes());
        $mform->setDefault('responsefieldlines', 5);

    }

    protected function data_preprocessing($question) {
        //var_dump($question);
        //die();
        $question = parent::data_preprocessing($question);

        if (empty($question->options)) {
            return $question;
        }

        $question->responsefieldlines = $question->options->responsefieldlines;
        $question->checkshow = $question->options->checkshow;

        return $question;
    }

    public function qtype() {
        return 'rsgtype';
    }
}
