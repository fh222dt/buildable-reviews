<?php
/**
 *
 */
require_once( ABSPATH . 'wp-content/plugins/buildable-reviews/admin/sql-quieries.php' );

class BR_Settings
{
    private $sql;
    private $saved_settings;

    function __construct() {
        $this->sql = new BR_SQL_Quieries();
        //$this->saved_settings= get_option('buildable-reviews-settings');
    }

    function br_init_settings() {
        add_settings_section(
            'general_settings_section',    //id
            'General Settings',            //name title
            array($this, 'br_general_settings_callback'),    //callback
            'buildable-reviews-settings'        //slug_name
        );
        add_settings_field(
            'br_standard_status',        //id
            'Standard status',                //titel
            array($this, 'br_standard_status_callback'),    //callback
            'buildable-reviews-settings',        //page slug_name
            'general_settings_section'        //section slug_name id??
        );
        add_settings_field(
            'br_question_algorithm',        //id
            'Frågealgoritm',                //titel
            array($this, 'br_question_algorithm_callback'),    //callback
            'buildable-reviews-settings',        //page slug_name
            'general_settings_section'        //section slug_name id??
        );
        add_settings_field(
            'br_question_order',        //id
            'Ordning av frågor',                //titel
            array($this, 'br_question_order_callback'),    //callback
            'buildable-reviews-settings',        //page slug_name
            'general_settings_section'        //section slug_name id??
        );
        add_settings_field(
            'br_summarize_min',        //id
            'Min. antal för att sammanställa',                //titel
            array($this, 'br_summarize_min_callback'),    //callback
            'buildable-reviews-settings',        //page slug_name
            'general_settings_section'        //section slug_name id??
        );



        register_setting(
            'br_settings',
            'br_standard_status',
            array($this, 'br_sanitize_standard_status')
        );
        register_setting(
            'br_settings',
            'br_question_algorithm',
            array($this, 'br_sanitize_question_algorithm')
        );
        register_setting(
            'br_settings',
            'br_question_order',
            array($this, 'br_sanitize_question_order')
        );
        register_setting(
            'br_settings',
            'br_summarize_min',
            array($this, 'br_sanitize_summarize_min')
        );
    }

    public function br_standard_status_callback() {
        //hämta alla statusars namn
        $status_names = $this->sql->get_all_status_names();
        //tidigare sparade options
        $option = get_option('br_standard_status');
        ?>
        <select name="br_standard_status" id="br_standard_status">
        <?php

        //gör dropdown med vald status markerad
        foreach ($status_names as $name) {
            echo '<option name="br_standard_status['. $name['status_name'] .']" value="'.$name['status_id'] .'"';
            selected($option, $name['status_id']);
            echo '>'. $name['status_name'] .'</option>';
        }
        ?>
        </select>
        <label>Välj vilken status en recension ska ha som standard när den lämnas av en användare</label>
        <?php
    }

    public function br_question_algorithm_callback() {
        echo '<p>Här ställer du in vilken procent en fråga ska väga in i totalbetyget av en recension. Du kan ange mellan 0-100.
        Den totala summan av procentsatserna måste bli 100%</p><br>';
        $questions = $this->sql->get_all_questions();
        //tidigare sparade option
        $option = get_option('br_question_algorithm');

        foreach ($questions as $q) {
            echo
            '<p class="br-admin">
                Id: '. $q['question_id'] .'   '
                . $q['question_name'] .
                    '<span class="br-description">  '. $q['question_type_name'] . '</span>
                    <input type="text" name="br_question_algorithm['. $q['question_id'] .']" class="br-textfield" value="'. $option[$q['question_id']].'" />
                    % <a href="admin.php?page=buildable-reviews-add-question&question-id='.$q['question_id'].'">Edit question</a>
            </p>';
        }
    }

    public function br_question_order_callback() {
        echo '<p>Ange i vilken ordning du vill att frågorna ska visas. Ange frågornas id-nr. Tex: 3, 7, 1 (3 visas överst).
              Genom att utesluta ett id, kan du välja att en fråga inte visas.</p>';
        //tidigare sparade option
        $option = get_option('br_question_order');
            echo '<input type="text" name="br_question_order" value="'. $option .'" />';
    }

    public function br_summarize_min_callback() {
        echo '<p>Ange hur många recensioner MINST som ska finnas för att en sammanfattning ska visas.</p>';
        //tidigare sparade option
        $option = get_option('br_summarize_min');
            echo '<input type="text" name="br_summarize_min" value="'. $option .'" />';
    }

    /**
     * Only sanitazing, saves to db either way WTF!!!
     * If not returning, saving is performed anyway
     */
    public function br_sanitize_standard_status($input) {
        //TODO kolla av att det är nåt av värdena i db?
        return $input;
    }

    public function br_sanitize_question_algorithm($input) {
        //tidigare sparade option
        $option = get_option('br_question_algorithm');

        foreach($input as $element) {
            if(! is_numeric($element)) {
                add_settings_error('br_question_algorithm', 'br_question_algorithm_error_num', 'Du kan bara ange siffror');
                return $option;
            }
        }

        if(array_sum($input) != 100) {
            add_settings_error('br_question_algorithm', 'br_question_algorithm_error_sum', 'Summan av alla frågor måste vara 100 %');
            return $option;
        }

        else {
            return $input;
        }
    }

    public function br_sanitize_question_order($input) {
        //TODO kolla av att det är nåt av värdena i db?
        return $input;
    }
    public function br_sanitize_summarize_min($input) {
        return $input;
    }

    public function br_general_settings_callback() {
    }


}