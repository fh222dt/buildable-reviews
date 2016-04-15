<?php

/**
 * View listing all reviews
 */
 include_once(ABSPATH . 'wp-content/plugins/buildable-reviews/admin/buildable-reviews-admin-tables.php');
 include_once(ABSPATH . 'wp-content/plugins/buildable-reviews/admin/class-reviews-table.php');
?>
<div class="wrap">
    <h2>Mange reviews</h2>
    <?php
    if( isset( $_GET[ 'tab' ] ) ) {
        $active_tab = $_GET[ 'tab' ];
    }
    else if( $active_tab == 'all-employers' ) {
        $active_tab = 'all-employers';
    }
    else {
        $active_tab = 'all-reviews';
    }
    ?>
    <h2 class="nav-tab-wrapper">
        <a href="?page=buildable-reviews&tab=all-reviews" class="nav-tab <?php echo $active_tab == 'all-reviews' ? 'nav-tab-active' : ''; ?>">All reviews</a>
        <a href="?page=buildable-reviews&tab=all-employers" class="nav-tab <?php echo $active_tab == 'all-employers' ? 'nav-tab-active' : ''; ?>">All employers</a>
    </h2>

    <?php
    if ($active_tab == 'all-reviews') {
        ?>
        <form method="post" id="all-reviews">
            <?php
            $reviews_obj = new BR_reviews_table();
            $reviews_obj->prepare_items();
            $reviews_obj->display();
            ?>
        </form>
        <?php
    }
    else if ($active_tab == 'all-employers') {
        ?>
        <form method="post">
            <?php
            $reviews_obj = new BR_review_objects_table();
            $reviews_obj->prepare_items();
            $reviews_obj->display();
            ?>
        </form>
        <?php
    }
    ?>

</div>
