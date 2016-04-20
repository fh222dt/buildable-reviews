<?php

/**
 * View all details of review
 */
require_once( ABSPATH . 'wp-content/plugins/buildable-reviews/admin/sql-quieries.php' );
$sql = new BR_SQL_Quieries();
?>
<div class="wrap">
    <h2><?php _e( 'Review details', 'textdomain' ); ?></h2>
    <?php
    //review id
    $review_id;
    if(isset($_GET['review-id'])) {        //TODO: do i need post??
        $review_id = $_GET['review-id'];
    }
    else {
        echo '<p>Invalid review id</p>';
    }
    
    ?>
    <form method="post">
        <table class="form-table">
            <tbody>
        <?php
        $answers = $sql->get_review_answers($review_id);

        foreach ($answers as $answer) {
            if($answer->question_type_name == 'Textfield') {
                echo '<tr valign="top">
                        <th scope="row">'.$answer->question_name.'</th>
                        <td>
                            <textarea id="desciption" name="desciption" type="text" maxlength="350" cols="100" required >'
                            . $answer->answer.
                            '</textarea>
                        </td>
                    </tr>';
            }
            else {
                echo '<tr valign="top">
                        <th scope="row">'.$answer->question_name.'</th>
                        <td>
                            '. $answer->answer.'
                        </td>
                    </tr>';
            }
        }
        ?>


        </tbody>
    </table>
        <fieldset>
          <legend>Status</legend>
            <label for="1">Godkänd</label>
            <input type="radio" name="status" id="1" value="male">
            <label for="2">Ej granskad</label>
            <input type="radio" name="status" id="2" value="female">
            <label for="3">Anmäld</label>
            <input type="radio" name="status" id="3" value="other">
        </fieldset>
        <?php
		submit_button( __( 'Uppdatera', 'textdomain' ), 'primary');
        submit_button( __( 'Ta bort hela recensionen', 'textdomain' ), 'delete' );
		?>
     </form>


</div>
