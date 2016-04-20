<?php

/**
 * View listing all reviews per user or employer
 */
 include_once(ABSPATH . 'wp-content/plugins/buildable-reviews/admin/class-review-list-table.php');

?>
<div class="wrap">
    <h2>All reviews from XX</h2>

    <form method="post">
        <?php
        $reviews_obj = new BR_reviews_();
        $reviews_obj->prepare_items();
        $reviews_obj->display();
        ?>
    </form>

</div>
