<?php
class BR_result_answer_templates {
    public function render_answer($answer){
        $sql = new BR_SQL_Quieries();
        $options = $sql->get_question_options($answer['question_id']);
        $type = $answer['question_type_name'];

        $output = '<h4>'. esc_attr($answer['question_name']) .'</h4>';


        if($type === 'Checkbox') {

            $output .= $this->render_checkbox($options, $answer);
        }
        else if($type === 'Textfield') {

            $output .= $this->render_textfield($answer);
        }

        else if($type === 'Scale' || $type === 'Radio') {

            $output .= $this->render_radio($options, $answer);
        }

        else if($type === 'Benefits') {

            $output .= $this->render_benefits($answer);
        }

        return $output;
    }

    public function render_checkbox($options, $question) {        //TODO behövs denna?
        $output ='';

        return $output;
    }

    public function render_textfield($answer) {
        $output ='<div class="br-display-answer"><p>'. esc_attr($answer['answer']) .'</p></div>';

        return $output;
    }

    public function render_radio($options, $answer) {
        $output ='<div class="br-display-answer"><p>'. esc_attr($answer['answer']) .'</p></div>';

        return $output;
    }

    //will output every benefit with diff. css-classes to them, if they are offered or not
    public function render_benefits($answer) {
        //get all benefits
        $display = new BR_public_display_form();
        $benefits = $display->do_benefits();        //benefits => id, name, category

        //$answer to array
        $answers = explode(', ', $answer['answer']);
        $output ='<div class="br-display-answer">';

        $comparable_category = $benefits[0]['category'];        //set category to the first to be found

        $output .= '<p class="benefits-category">'. esc_attr($comparable_category) .'</p>';

        foreach ($benefits as $benefit) {
            if ($benefit['category'] != $comparable_category) {
                $output .= '<p class="benefits-category">'. esc_attr($benefit['category']) .'</p>';

                $comparable_category = $benefit['category'];
            }
            $class = 'class="grey"';

            if(in_array('term_id '.$benefit['id'], $answers)) {
                $class = 'class="bold"';
            }

            $output .= '<p '.$class .'>'. $benefit['name'] .'</p>';
        }

        $output .= '</div>';

        return $output;
    }

}