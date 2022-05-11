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
 * rsgtype question definition class.
 *
 * @package    qtype
 * @subpackage rsgtype
 * @copyright  Cégep à distance
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();


/**
 * Represents an rsgtype question.
 *
 * @copyright  2009 The Open University
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class qtype_rsgtype_question extends question_graded_automatically
{
    public $answer;
    public $option;


    /**
     * @param moodle_page the page we are outputting to.
     * @return qtype_rsgtype_format_renderer_base the response-format-specific renderer.
     */
    public function get_format_renderer(moodle_page $page)
    {
        return $page->get_renderer('qtype_rsgtype', 'format_plain');
    }

    /**
     * Return an array of the question type variables that could be submitted
     * as part of a question of this type, with their types, so they can be
     * properly cleaned.
     * @return array variable name => PARAM_... constant.
     */
    public function get_expected_data()
    {
        $expecteddata = array(
            'answer' => PARAM_RAW
        );
        if ($this->checkshow)
            $expecteddata['option'] = PARAM_INT;

        return $expecteddata;
    }

    public function summarise_response(array $response)
    {

        if (isset($response['answer'])) {
            return question_utils::to_plain_text($response['answer'],
                2, array('para' => false));
        } else {
            return null;
        }
    }

    public function get_correct_response()
    {
        return array('answer' => null);
    }

    public function is_complete_response(array $response)
    {
        $condition1 = array_key_exists('answer', $response) && ($response['answer'] !== '');
        if ($this->checkshow){
            $condition2 = array_key_exists('option', $response) && ($response['option'] !== -1);
        }
        else
            $condition2 =true;

        return $condition1 && $condition2;
    }

    //TODO: Valider que le bug se corrige. OK...
    public function is_same_response(array $prevresponse, array $newresponse)
    {

        if (array_key_exists('option', $prevresponse)) {
            $option1 = (string)$prevresponse['option'];
        } else {
            $option1 = '0';
        }
        if (array_key_exists('option', $newresponse)) {
            $option2 = (string)$newresponse['option'];
        } else {
            $option2 = '0';
        }

        if (array_key_exists('answer', $prevresponse)) {
            $value1 = (string)$prevresponse['answer'];
        } else {
            $value1 = '';
        }
        if (array_key_exists('answer', $newresponse)) {
            $value2 = (string)$newresponse['answer'];
        } else {
            $value2 = '';
        }

        return $value1 === $value2 && $option1===$option2;
    }

    /**
     * Grade a response to the question, returning a fraction between
     * get_min_fraction() and get_max_fraction(), and the corresponding {@link question_state}
     * right, partial or wrong.
     * @param array $response responses, as returned by
     *      {@link question_attempt_step::get_qt_data()}.
     * @return array (float, integer) the fraction, and the state.
     */
    public function grade_response(array $response)
    {
        //on donne toujours 100 % des points
        return array(1, question_state::graded_state_for_fraction(1));
    }

    public function check_file_access($qa, $options, $component, $filearea, $args, $forcedownload)
    {

    }

    /**
     * In situations where is_gradable_response() returns false, this method
     * should generate a description of what the problem is.
     * @return string the message.
     */
    public function get_validation_error(array $response)
    {
        // TODO: Implement get_validation_error() method.
    }


}
