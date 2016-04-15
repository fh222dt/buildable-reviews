<?php

/**
 * View listing all reviews
 */
 include_once(ABSPATH . 'wp-content/plugins/buildable-reviews/admin/buildable-reviews-admin-tables.php');

 $reviews_obj = new Buildable_reviews_admin_tables();
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
            //list table
            $headings = ['ID', 'Företag', 'Samlat betyg', 'Datum', 'Status', 'Användare', 'Detaljer'];
            $reviews_obj->prepare_items($headings);
            $reviews_obj->display();
    }
    else if ($active_tab == 'all-employers') {
            //list table
            $headings = ['ID', 'Företag', 'Samlat betyg', 'Senest recenserat', 'Antal', 'Visa alla'];
            $this->reviews_obj->prepare_items($headings);
            $this->reviews_obj->display();
    }
    ?>

</div>
