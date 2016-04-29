<?php
class BR_question_templates {
    public function render_question($question){
                                                        //TODO: lägg till classer
        $output = '<div class="br-question>"
                    <h3>'. $question['question_name'] .'</h3>
                    <p>'. $question['question_desc'] .'</p>';

        $output .= '<div class="br-answer-options">';

        $options = $question['options'];
        $type = $question['question_type_name'];

        if($type = 'checkbox') {

            $output .= render_checkbox($options);
        }
        else if($type = 'textfield') {

            $output .= render_textfield($options);
        }

        else if($type = 'scale' || $type = 'radio') {            //TODO: kanske fel namn??

            $output .= render_radio($options, $question);
        }

        $output .=         '</div>';

        $output .= '</div>';

        return $output;
    }

    public function render_checkbox($options) {

        foreach ($options as $option) {        //TODO: name???
            $output .= '<input type="checkbox" name="'. $option['name'] .'" value="'. $option['name'] .'"></input>
                        <label>'. $option['name'] .'</label>';
        }
        //TODO: gruppera ??

        return $output;
    }

    public function render_textfield($options) {

        //foreach ($options as $option) {        //TODO: name???
            $output .= '<textarea name="'. $option['name'] .'"></textarea>';
        //}
        return $output;
    }

    public function render_radio($options, $question) {    //kanske ett 3e arg för hur det ska stylas??

        foreach ($options as $option) {        //TODO: name???
            $output .= '<input type="radio" name="'. $question['name'] .'" value="'. $option['name'] .'"></input>
                        <label>'. $option['name'] .'</label>';
        }
        return $output;
    }
}