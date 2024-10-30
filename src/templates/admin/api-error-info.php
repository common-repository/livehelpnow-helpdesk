<?php
/**
 * Information to display when API related error occurs
 *
 * @package LHNChat
 */

?>

<section class="notice notice-error">
	<h3>
		<?php _e( 'There was an error while connecting to LiveHelpNow API', 'lhnchat' ); ?>
	</h3>
	<p>
		<?php printf( __( 'Error code:<br />%s', 'lhnchat' ), sprintf( '<code>%s</code>', $widgets_options->get_error_code() ) ); ?>
	</p>
	<p>
		<?php printf( __( 'Error message:<br />%s', 'lhnchat' ), sprintf( '<code>%s</code>', $widgets_options->get_error_message() ) ); ?>
	</p>
</section>
