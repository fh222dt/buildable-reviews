<?php
/**
 *
 */
require_once( ABSPATH . 'wp-content/plugins/buildable-reviews/admin/sql-quieries.php' );
class BR_Settings
{
    private $sql;

    function __construct() {
        $this->sql = new BR_SQL_Quieries();
    }

    function br_init_settings() {
        add_settings_section(
            'general_settings_section',    //id
            'General Settings',            //name title
            array($this, 'br_general_settings_callback'),    //callback
            'buildable-reviews-settings'        //slug_name
        );

        add_settings_field(
            'standard_status',        //id
            'Standard status',                //titel
            array($this, 'br_standard_status_callback'),    //callback
            'buildable-reviews-settings',        //page slug_name
            'general_settings_section'        //section slug_name id??
        );

        add_settings_field(
            'question_algorithm',        //id
            'Frågealgoritm',                //titel
            array($this, 'br_question_algorithm_callback'),    //callback
            'buildable-reviews-settings',        //page slug_name
            'general_settings_section'        //section slug_name id??
        );

        register_setting(            //TODO: callback som tar hand om sparandet
            'general_settings_section',
            'standard_status'
        );

    }

    public function br_standard_status_callback() {
        //hämta alla statusars namn
        $status_names = $this->sql->get_all_status_names();
        ?>
        <select name="default_status" id="default_status">
        <?php
        //gör dropdown med vald status markerad
        foreach ($status_names as $name) {
            echo '<option value="'.$name['status_name'].'">'.$name['status_name'].'</option>';        //TODO: auto välj tidigare sparad inställning
        }
        ?>
        </select>
        <label>Välj vilken status en recension ska ha som standard när den lämnas av en användare</label>
        <?php
    }

    public function br_question_algorithm_callback(){

        echo '<p>Här ställer du in vilken procent en fråga ska väga in i totalbetyget av en recension. Du kan ange mellan 0-100.
        Den totala summan av procentsatserna måste bli 100%</p><br>';
        $questions = $this->sql->get_all_questions();

        foreach ($questions as $q) {
            echo
            '<p class="br-admin">'
                . $q['question_name'] .
                    '<span class="br-description">'. $q['question_type_name'] . '</span>
                    <input type="text" name="'. $q['question_name'] .'" class="br-textfield" value="10" />
            </p>';            //TODO: ad value to input

        }
    }

    public function

    public function br_general_settings_callback() {
        //echo '<p>Här kan du ändra inställningar för recensionerna</p>';
    }
}



