<?php
class BR_question_templates {
    public function render_question($question){
                                                        //TODO: lägg till css-classer
        $output = '<div class="br-question>"
                    <h3>'. $question['question_name'] .'</h3>
                    <p>'. $question['question_desc'] .'</p>';

        $output .= '<div class="br-answer-options">';

        $options = $question['options'];
        $type = $question['question_type_name'];

        // print_r($type);
        // exit;

        if($type == 'Checkbox') {

            $output .= $this->render_checkbox($options);
        }
        else if($type == 'Textfield') {

            $output .= $this->render_textfield($options);
        }

        else if($type == 'Scale' || $type == 'Radio') {

            $output .= $this->render_radio($options, $question);
        }

        $output .=         '</div>';

        $output .= '</div>';

        return $output;
    }

    public function render_checkbox($options) {
        $output;
        foreach ($options as $option) {
            $output .= '<input type="checkbox" name="'. $option .'" value="'. $option .'"></input>
                        <label>'. $option .'</label>';
        }
        //TODO: gruppera ??

        return $output;
    }

    public function render_textfield($options) {

        $output = '<textarea name="'. $option .'"></textarea>';

        return $output;
    }

    public function render_radio($options, $question) {    //kanske ett 3e arg för hur det ska stylas??
        $output;
        foreach ($options as $option) {
            $output .= '<input type="radio" name="'. $question['question_name'] .'" value="'. $option .'"></input>
                        <label>'. $option .'</label>';
        }
        return $output;
    }
}