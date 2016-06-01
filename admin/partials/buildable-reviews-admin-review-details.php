<?php

/**
 * View all details of review, edit answers from user
 */

require_once( ABSPATH . 'wp-content/plugins/buildable-reviews/admin/sql-quieries.php' );
$sql = new BR_SQL_Quieries();

if(isset($_GET['review-id'])) {
    $review_id = $_GET['review-id'];
    //the review we are looking at
    $where = 'WHERE R.review_id = '.$review_id.' ';
    $result = $sql->get_reviews(25, 1, $where);
    $review =$result[0];
}

else {
    echo '<p>Invalid review id</p>';
    die;
}
?>
<div class="wrap">
    <h2><?php _e( 'Granska recension', 'textdomain' ); ?></h2>
    <form method="post" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>">
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
                <tr valign="top">
                    <th scope="row">Betyg</th>
                    <td><?php $score = Buildable_reviews_Admin::get_total_score_of_review($review_id); echo $score .' av 5'; ?>
                    </td>
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
            <input type="radio" name="status" value="1" <?=($review['status_name'] == 'Godkänd')?'checked':''?>/>Godkänd
            <input type="radio" name="status" value="2" <?=($review['status_name'] == 'Ej granskad')?'checked':''?>/>Ej granskad
            <input type="radio" name="status" value="3" <?=($review['status_name'] == 'Anmäld')?'checked':''?>/>Anmäld
        </fieldset>
        <input type="hidden" name="action" value="br_update_review" />
        <input type="hidden" name="review-id" value="<?php echo $review_id ?>" />
        <?php
		submit_button( __( 'Uppdatera', 'textdomain' ), 'primary');
        //submit_button( __( 'Ta bort hela recensionen', 'textdomain' ), 'delete' );    //TODO: delete review
        ?>
        <input type="submit" name="delete" id="submit" class="button delete" value="Ta bort hela recensionen">
     </form>

</div>