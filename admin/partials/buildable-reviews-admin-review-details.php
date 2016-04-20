<?php

/**
 * View all details of review
 */
require_once( ABSPATH . 'wp-content/plugins/buildable-reviews/admin/sql-quieries.php' );
$sql = new BR_SQL_Quieries();

if(isset($_GET['review-id'])) {        //TODO: do i need post??
    $review_id = $_GET['review-id'];

    //the review we are looking at
    $result = $sql->get_reviews(25, 1, $review_id);
    $review =$result[0];
}
else {                        //TODO: fånga om det är ett id som inte finns
    echo '<p>Invalid review id</p>';
    die;
}
?>
<div class="wrap">
    <h2><?php _e( 'Granska recension', 'textdomain' ); ?></h2>
    <form method="post">
        <table class="form-table">
            <tbody>
                <tr valign="top">
                    <th scope="row">Uppgiftslämnare</th>
                    <td><?php echo $review['user']?></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Datum</th>
                    <td><?php echo $review['created_at']?></td>
                </tr>
                <tr>
                    <th scope="row">Frågor</th>
                </tr>
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
            <input type="radio" name="status" id="1" value="0" <?=($review['status_name'] == 'Godkänd')?'checked':''?>/>Godkänd
            <input type="radio" name="status" id="2" value="0" <?=($review['status_name'] == 'Ej granskad')?'checked':''?>/>Ej granskad
            <input type="radio" name="status" id="3" value="0" <?=($review['status_name'] == 'Anmäld')?'checked':''?>/>Anmäld
        </fieldset>
        <?php
		submit_button( __( 'Uppdatera', 'textdomain' ), 'primary');
        submit_button( __( 'Ta bort hela recensionen', 'textdomain' ), 'delete' );
		?>
     </form>


</div>
