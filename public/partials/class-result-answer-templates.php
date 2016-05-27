<?php
//1-5 visa svar i medeltal (skala)

//ja/nej varje svars-alt i % (envalsfråga)

//förmåner % som kryssat för varje??
class BR_result_answer_templates {
    public function render_answer($answer){
        $options = $answer['options'];
        $type = $answer['question_type_name'];

        // <h4>Frågans namn</h4>
        // <div>Resultat, olika beroende på fråge-typ</div>

        $output = '<h4>'. esc_attr($question['question_name']) .'</h4>';


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

        return $output;
    }

    public function render_checkbox($options, $question) {        //vet ej hur det ska se ut
        $output ='';


        foreach ($options as $option) {


        }

        return $output;
    }

    public function render_textfield($answer) {
        $output ='<div class="br-display-answer"><p>'. esc_attr($answer['result']) .'</p></div>';

        return $output;
    }

    public function render_radio($options, $answer) {
        $output ='<div class="br-display-answer><p>'. esc_attr($answer['result']) .'</p></div>';

        return $output;
    }

    public function render_benefits($options, $answer) {
        //get all benefits
        $display = new BR_public_display_form();
        $benefits = $display->do_benefits();        //benefits => id, name, category

        $output ='<div class="br-display-answer>';

        $comparable_category = $benefits[0]['category'];        //set category to the first to be found

        $output .= '<p class="benefits-category">'. esc_attr($comparable_category) .'</p>';

        foreach ($benefits as $benefit) {
            if ($benefit['category'] != $comparable_category) {
                $output .= '<p class="benefits-category">'. esc_attr($benefit['category']) .'</p>';

                $comparable_category = $benefit['category'];
            }

            $class = .'class="grey"';

            if(in_array($benefit, $answer)) {
                $class = .'class="bold"';
            }

            $output .= '<p '.$class .'>'. $benefit['name'] .'</p>';
        }

        $output .= '</div>';

        return $output;
    }

}