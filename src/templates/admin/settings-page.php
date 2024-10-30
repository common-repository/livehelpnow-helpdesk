<?php
/**
 * Settings page template
 *
 * @package LHNChat
 */

?>

<form action="options.php" method="post">
	<?php settings_errors(); ?>
	<?php settings_fields( 'livehelpnow-chat' ); ?>
	<?php do_settings_sections( 'livehelpnow-chat' ); ?>
	<?php submit_button(); ?>
</form>
