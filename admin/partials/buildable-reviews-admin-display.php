<?php

/**
 * View listing all reviews
 */
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

    }
    else if ($active_tab == 'all-employers') {
            //list table
            $headings = ['ID', 'Företag', 'Samlat betyg', 'Senest recenserat', 'Antal', 'Visa alla'];
    }
    ?>

</div>
