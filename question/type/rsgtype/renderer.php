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
 * rsgtype question renderer class.
 *
 * @package    qtype
 * @subpackage rsgtype
 * @copyright  2014 Cégep à distance
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();


/**
 * Generates the output for rsgtype questions.
 *
 * @copyright  Cégep à distance
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class qtype_rsgtype_renderer extends qtype_renderer {

    public function formulation_and_controls(question_attempt $qa,
            question_display_options $options) {


        $question = $qa->get_question();


        $responseoutput = $question->get_format_renderer($this->page);

        // Answer field.
        $step = $qa->get_last_step_with_qt_var('answer');

        //$step = $qa->get_last_qt_data(array('answer','option'));
       // var_dump($step);
        //die();

        //C'est lui qui affiche un nouvelle attemp
        if ((!$step->has_qt_var('answer')||($question->checkshow==1 && !$step->has_qt_var('option'))) && empty($options->readonly)) {
            // Question has never been answered, fill it with response template.
            $step = new question_attempt_step(array('answer'=>'','option'=>'-1'));
        }


        if (empty($options->readonly)) {
            $answer = $responseoutput->response_area_input('answer', $qa,
                    $step, $question->responsefieldlines, $options->context);
            $select=$responseoutput->response_select($qa, $step);

        } else {
            $answer = $responseoutput->response_area_read_only('answer', $qa,
                    $step, $question->responsefieldlines, $options->context);
            $select=$responseoutput->response_select_read_only($qa, $step);
        }

        //var_dump($step);
        //die();

       // $select=$responseoutput->response_select($qa, $step);

        if ($question->checkshow !='1'){
            $select=$responseoutput->response_option($qa, $step);
        }

        $result='';
        $result .= html_writer::tag('div', $question->format_questiontext($qa),
                array('class' => 'qtext'));
        $result .= html_writer::start_tag('div', array('class' => 'ablock'));
        $result .= html_writer::tag('div',$select. $answer, array('class' => 'answer'));
        $result .= html_writer::end_tag('div');

        return $result;
    }



    public function manual_comment(question_attempt $qa, question_display_options $options) {
        if ($options->manualcomment != question_display_options::EDITABLE) {
            return '';
        }

        $question = $qa->get_question();
        return html_writer::nonempty_tag('div', $question->format_text(
                $question->graderinfo, $question->graderinfo, $qa, 'qtype_rsgtype',
                'graderinfo', $question->id), array('class' => 'graderinfo'));
    }
}


/**
 * An rsgtype format renderer for rsgtypes where the student should use a plain
 * input box, but with a normal, proportional font.
 *
 * @copyright  2011 The Open University
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class qtype_rsgtype_format_plain_renderer extends plugin_renderer_base {
    /**
     * @return string the HTML for the textarea.
     */
    protected function textarea($response, $lines, $attributes) {
        $attributes['class'] = $this->class_name() . ' qtype_rsgtype_response';
        $attributes['rows'] = $lines;
        $attributes['cols'] = 60;
        return html_writer::tag('textarea', s($response), $attributes);
    }

    protected function class_name() {
        return 'qtype_rsgtype_plain';
    }

    public function response_area_read_only($name, $qa, $step, $lines, $context) {
        return $this->textarea($step->get_qt_var($name), $lines, array('readonly' => 'readonly'));
    }

    public function response_area_input($name, $qa, $step, $lines, $context) {
        $inputname = $qa->get_qt_field_name($name);
        return $this->textarea($step->get_qt_var($name), $lines, array('name' => $inputname)) .
        html_writer::empty_tag('input', array('type' => 'hidden',
            'name' => $inputname . 'format', 'value' => FORMAT_PLAIN));
    }

    public function response_select_read_only($qa, $step){
        $op = $step->get_qt_var('option');
        $op=(isset($op)?$op:-1);
        $texts=array('Choisir...','C\'est une force pour moi.','C\'est un défi pour moi.');
        $message=$texts[$op+1];

        return html_writer::empty_tag('input', array('type' => 'text',
            'readonly' => 'readonly','value'=>$message, 'size'=>25));
    }

    public function response_select($qa, $step){

        //option name
        $opname=$qa->get_qt_field_name('option');

        // option field.
        $op = $step->get_qt_var('option');

        $texts=array('Choisir...','C\'est une force pour moi.','C\'est un défi pour moi.');
        $op=(isset($op)?$op:-1);
        $options='<select name="'.$opname.'" class=" qtype_rsgtype_response">'.PHP_EOL;
        foreach(array(-1,0,1) as $i=>$option){
            $selected=($op==$option)?'selected':'';
            $options.='<option value="'.$option.'" '.$selected.'>'.$texts[$i].'</option>'.PHP_EOL;
        }
        $options.='</select>'.PHP_EOL;

        return $options. html_writer::empty_tag('input', array('type' => 'hidden',
            'name' => $opname . 'format', 'value' => FORMAT_PLAIN));
    }

    public function response_option($qa, $step){

        //option name
        $opname=$qa->get_qt_field_name('option');

        // option field.
        $op = $step->get_qt_var('option');
        $option=html_writer::empty_tag('input', array('type' => 'hidden',
            'name' => $opname , 'value' => 0));

        return $option.html_writer::empty_tag('input', array('type' => 'hidden',
            'name' => $opname . 'format', 'value' => FORMAT_PLAIN));
    }
}

