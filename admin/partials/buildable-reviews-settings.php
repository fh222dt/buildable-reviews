<?php
include_once(ABSPATH . 'wp-content/plugins/buildable-reviews/admin/class-settings.php');
/**
 * View for settings
 */

?>
<div class="wrap">
    <h2>Settings</h2>
    <?php
    settings_errors();

    if ( isset( $_GET['updated'] ) && isset( $_GET['page'] ) ) {
			add_settings_error('general', 'settings_updated', __( 'Settings saved.', 'textdomain' ), 'updated');
	}

    ?>
    <form method="post" name="buildable reviews settings" action="options.php">
			<?php
			//wp_nonce_field('update-options');
			settings_fields('br_settings');                //settingsfields-section to show
			do_settings_sections('buildable-reviews-settings');        //slug of page to show settings
			submit_button(null, 'primary', 'submit', true, null);
			?>
	</form>

</div>
