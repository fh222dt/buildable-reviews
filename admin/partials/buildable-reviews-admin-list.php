<?php

/**
 * View listing all reviews per user or employer
 */
 include_once(ABSPATH . 'wp-content/plugins/buildable-reviews/admin/class-review-list-table.php');
 //include_once( ABSPATH . 'wp-content/plugins/buildable-reviews/admin/sql-quieries.php' );
 //$sql = new BR_SQL_Quieries();

 if(isset($_GET['by-user'])) {
     $list_by = $_GET['by-user'];
     $where = 'WHERE R.user_id = '.$list_by.' ';
     $header = 'Alla recensioner per användare';
 }
 else if (isset($_GET['by-employer'])) {
     $list_by = $_GET['by-employer'];
     $where = 'WHERE P.ID = '.$list_by.' ';
     $header = 'Alla recensioner per arbetsgivare';

 }
 else {
     echo '<p>Invalid review id</p>';
     die;
 }
 ?>
<div class="wrap">
    <h2><?php echo $header ?></h2>

    <form method="post">
        <?php
        $reviews_obj = new BR_reviews_list_table($where);
        $reviews_obj->prepare_items();
        $reviews_obj->display();
        ?>
    </form>

</div>
