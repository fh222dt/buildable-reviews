<?php
class BR_question_templates {
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
            //if($error[$question['question_id']] == $question['question_id']) {
            if(array_key_exists($question['question_id'], $error)) {
                $output .= '<span style="color: red;">'. $error[$question['question_id']] .'</span>';
            }
        }
        $i = $question['question_id'];
        //print_r($error[$i]);

        $output .= '<p>'. esc_attr($question['question_desc'] ).'</p>';

        $output .= '<div class="br-answer-options">';

        if($type == 'Checkbox') {

            $output .= $this->render_checkbox($options, $question);
        }
        else if($type == 'Textfield') {

            $output .= $this->render_textfield($question);
        }

        else if($type == 'Scale' || $type == 'Radio') {

            $output .= $this->render_radio($options, $question);
        }

        else if($type == 'Benefits') {

            $output .= $this->render_benefits($options, $question);
        }

        $output .= '</div>';

        $output .= '</div>';

        return $output;
    }

    public function render_checkbox($options, $question) {
        $output;
        $post = $_POST[$question['question_id']];
        foreach ($options as $option) {

            $current = esc_attr(in_array($option, $post) );        //'. checked( $option, 'ja').'
            $output .= '<input type="checkbox" name="'. $question['question_id'] .'[]" value="'. $option .'"

                        ></input>
                        <label>'. $option .'</label>';
        }

        return $output;
    }

    public function render_textfield($question) {

        $output = '<textarea name="'. $question['question_id'] .'" '. ($question['required'] == true ? 'required' : '') .'>'. esc_attr($_POST[$question['question_id']]) .'
        </textarea>';
        //$output = '<textarea name="'. $question['question_id'] .'"></textarea>';

        return $output;
    }

    public function render_radio($options, $question) {    //kanske ett 3e arg för hur det ska stylas??
        $output;
        foreach ($options as $option) {        //TODO
            $output .= '<input type="radio" name="'. $question['question_id'] .'" value="'. $option .'"'. ($question['required'] == true ? 'required' : '' ).''.
                        checked($option, esc_attr($_POST[$question['question_id']])).'></input>
                        <label>'. $option .'</label>';
        }
        return $output;
    }

    public function render_benefits($options, $question) {
        $output;
        //$options => id, name, category

        $comparable_category = $options[0]['category'];        //set category to the first to be found

        $output .= '<fieldset><legend>'. esc_attr($comparable_category) .'</legend>';

        foreach ($options as $option) {
            if ($option['category'] != $comparable_category) {
                $output .= '</fieldset><fieldset><legend>'. esc_attr($option['category']) .'</legend>';

                $comparable_category = $option['category'];
            }

            $output .= '<input type="checkbox" name="'. $question['question_id'] .'[]" value="term_id '. $option['id'] .'"></input>
                        <label>'. esc_attr($option['name']) .'</label>';
        }

        $output .= '</fieldset>';

        return $output;
    }
}