<?php
class BR_form_question_templates {
    public function render_question($question){
        $options = $question['options'];
        $type = $question['question_type_name'];

                                                        //TODO: lägg till css-classer, remove inline style
        $output = '<div class="br-question">
                    <h6>'. esc_attr($question['question_name']) .'</h6>'
                         .($question['required'] == true ? '<span class="req_q" style="color: red;">Obligatorisk</span>' : '');

        //add error messages thrue session
        if (isset($_SESSION['br_form_error']) ) {
            $error = $_SESSION['br_form_error'];

            if(array_key_exists($question['question_id'], $error)) {
                $output .= '<span style="color: red;">'. $error[$question['question_id']] .'</span>';
            }
        }

        $output .= '<p>'. esc_attr($question['question_desc'] ).'</p>';

        $output .= '<div class="br-answer-options">';

        if($type == 'Checkbox') {

            $output .= $this->render_checkbox($options, $question);
        }
        else if($type === 'Textfield') {

            $output .= $this->render_textfield($question);
        }

        else if($type === 'Scale' || $type === 'Radio') {

            $output .= $this->render_radio($options, $question);
        }

        else if($type === 'Benefits') {

            $output .= $this->render_benefits($options, $question);
        }

        else if($type === 'Email') {

            $output .= $this->render_email($question);
        }

        $output .= '</div>';

        $output .= '</div>';

        return $output;
    }

    public function render_checkbox($options, $question) {
        $output ='';
        $posted_value = null;

        foreach ($options as $option) {
            if(isset($_POST[$question['question_id']]) && is_array($_POST[$question['question_id']])) {
                if (in_array($option, $_POST[$question['question_id']])) {
                    $posted_value = $option;
                }
            }

            $output .= '<input type="checkbox" name="'. $question['question_id'] .'[]" value="'. $option .'" ';
            $output .= checked($option, $posted_value, false) .'></input><label>'. $option .'</label>';
        }

        return $output;
    }

    public function render_textfield($question) {
        $posted_value = null;
        if(isset($_POST[$question['question_id']])) {
            $posted_value = $_POST[$question['question_id']];
        }
        $output = '<textarea name="'. $question['question_id'] .'" '. ($question['required'] == true ? 'required' : '') .'>'. esc_attr($posted_value) .'</textarea>';

        return $output;
    }

    public function render_radio($options, $question) {    //kanske ett 3e arg för hur det ska stylas??
        $output ='';
        $posted_value = null;
        if(isset($_POST[$question['question_id']])) {
            $posted_value = $_POST[$question['question_id']];
        }
        foreach ($options as $option) {
            $output .= '<input type="radio" name="'. $question['question_id'] .'" value="'. $option .'"';
            $output .= ($question['required'] == true ? 'required' : '' ).''.
                        checked($option, esc_attr($posted_value), false).'></input>
                        <label>'. $option .'</label>';
        }
        return $output;
    }

    public function render_benefits($options, $question) { //$options => id, name, category
        $output ='';

        $comparable_category = $options[0]['category'];        //set category to the first to be found

        $output .= '<fieldset><legend>'. esc_attr($comparable_category) .'</legend>';

        foreach ($options as $option) {
            if ($option['category'] != $comparable_category) {
                $output .= '</fieldset><fieldset><legend>'. esc_attr($option['category']) .'</legend>';

                $comparable_category = $option['category'];
            }

            $value = 'term_id '. $option['id'];
            $posted_value = null;
            if(isset($_POST[$question['question_id']]) && is_array($_POST[$question['question_id']])) {
                if (in_array($value, $_POST[$question['question_id']])) {
                    $posted_value = $value;
                }
            }

            $output .= '<input type="checkbox" name="'. $question['question_id'] .'[]" value="'. $value.'" ';
            $output .= checked($value, $posted_value, false) .'"></input><label>'. esc_attr($option['name']) .'</label>';

        }

        $output .= '</fieldset>';

        return $output;
    }

    public function render_email($question) {
        $email_question_id = 7;    //TODO
        $posted_value = null;
        if(isset($_POST[$email_question_id])) {
            $posted_value = $_POST[$email_question_id];
        }
        $output = '<input type="email" name="'. $email_question_id .'" '. ($question['required'] == true ? 'required' : '') .' value="'. esc_attr($posted_value) .'" >';

        return $output;
    }
}