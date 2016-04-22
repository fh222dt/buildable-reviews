<?php

/**
 * View all details of review
 */

require_once( ABSPATH . 'wp-content/plugins/buildable-reviews/admin/sql-quieries.php' );
$sql = new BR_SQL_Quieries();

if(isset($_GET['review-id'])) {        //TODO: do i need post??
    $review_id = $_GET['review-id'];
    //the review we are looking at
    $where = 'WHERE R.review_id = '.$review_id.' ';
    $result = $sql->get_reviews(25, 1, $where);
    $review =$result[0];

}
else if(isset($_POST['review-id'])) {
    $review_id = $_POST['review-id'];

    //the review we are looking at
    $where = 'WHERE R.review_id = '.$review_id.' ';
    $result = $sql->get_reviews(25, 1, $where);
    $review =$result[0];
}
else {                        //TODO: fånga om det är ett id som inte finns
    echo '<p>Invalid review id</p>';
    die;
}
?>
<div class="wrap">
    <h2><?php _e( 'Granska recension', 'textdomain' ); ?></h2>
    <form method="post" action="#">
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
                    if($answer['question_type_name'] == 'Textfield') {
                        echo '<tr valign="top">
                                <th scope="row">'.$answer['question_name'].'</th>
                                <td>
                                    <textarea id="answer-id" name="answer-id-'. $answer['answer_id'] .'" type="text" maxlength="350" cols="100">'
                                    . $answer['answer'].
                                    '</textarea>
                                </td>
                            </tr>';
                    }
                    else {
                        echo '<tr valign="top">
                                <th scope="row">'.$answer['question_name'].'</th>
                                <td>
                                    '. $answer['answer'].'
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
        <input type="hidden" name="br_update_review" value="true" />
        <?php
		submit_button( __( 'Uppdatera', 'textdomain' ), 'primary');
        //submit_button( __( 'Ta bort hela recensionen', 'textdomain' ), 'delete' );
		?>
     </form>


</div>

<?php
function br_update_review() {
    global $wpdb;
    $sql = new BR_SQL_Quieries();

    if(isset($_GET['review-id'])) {        //TODO: do i need post??
        $review_id = $_GET['review-id'];
    }
    else if(isset($_POST['review-id'])) {
        $review_id = $_POST['review-id'];
    }
    //echo $review_id;
    $answers = $sql->get_review_answers($review_id);
    print_r($answers);
    foreach ($answers as $answer) {
        if($answer['question_type_name'] == 'Textfield'){
            $answer['answer'] = ($_POST['answer-id-'. $answer['answer_id']]);

            $wpdb->update($wpdb->prefix . Buildable_reviews::TABLE_NAME_REVIEW_QUESTION_ANSWER, array('answer' => $answer['answer']),
            array('answer_id' => $answer['answer_id']));         //TODO: kankse ange datatyper?
        }
    }
    //wp_redirect( 'admin.php?page=buildable-reviews-details&review-id=997' );
    //TODO: update status
}


if ( isset( $_POST['br_update_review'] ) && $_POST['br_update_review'] == 'true' ) {
    echo $_POST['answer-id-'. $answer['answer_id']];
    //add_action('admin_init', 'br_update_review');
    // require_once( ABSPATH . 'wp-content/plugins/buildable-reviews/admin/class-update-review.php' );
    // $update = new BR_update_review();
    // $update->br_update_review();
    br_update_review();
    //wp_redirect('admin.php?page=buildable-reviews-details&review-id=996');
}