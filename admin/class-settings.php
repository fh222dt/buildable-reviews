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

        register_setting(
            'br_settings',
            'br_standard_status',        //hmm
            array($this, 'br_sanitize_standard_status')
        );

        // register_setting(
        //     'br_settings',
        //     'br_question_algorithm',
        //     array($this, 'br_sanitize_question_algorithm')
        // );
    }

    public function br_standard_status_callback() {
        //hämta alla statusars namn
        $status_names = $this->sql->get_all_status_names();
        //tidigare sparade options
        $option = get_option('br_standard_status');
        ?>
        <select name="br_standard_status" id="br_standard_status">
            <!-- <option name="br_standard_status[g]" value="godkänd">Godkänd</option>
            <option name="br_standard_status[e]" value="ej_granskad">Ej granskad</option>
            <option name="br_standard_status[a]" value="anmäld">Anmäld</option> -->
        <?php
        //gör dropdown med vald status markerad
        foreach ($status_names as $name) {
            echo '<option name="br_standard_status['. $name['status_name'] .']" value="'.$name['status_name'] .'"';
            selected($option, $name['status_name']);
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

        foreach ($questions as $q) {
            echo
            '<p class="br-admin">'
                . $q['question_name'] .
                    '<span class="br-description">'. $q['question_type_name'] . '</span>
                    <input type="text" name="br_q_id_'. $q['question_id'] .'" id="'. $q['question_id'] .'" class="br-textfield" value="'. $this->saved_settings['br_question_algorithm'][$q]['value'] .'" />
            </p>';            //TODO: ad value to input & style

        }
    }

    public function br_sanitize_standard_status($input) {

        return $input;
    }

    public function br_sanitize_question_algorithm($input) {
        //  var_dump($input['br_question_algorithm']['value']);
        //  exit;
        //validera input som siffror, summa 100
        //name => value
        if(! is_numeric($input['br_question_algorithm']['value'])) {
            add_settings_error('br_question_algorithm', 'br_question_algorithm_error_num', 'Du kan bara ange siffor');
        }

        if(array_sum($input['br_question_algorithm']['value']) != 100) {
            add_settings_error('br_question_algorithm', 'br_question_algorithm_error_sum', 'Summan av alla frågor måste vara 100%');
        }
        return $input;
    }

    public function br_general_settings_callback() {
        //echo '<p>Här kan du ändra inställningar för recensionerna</p>';
    }
}



