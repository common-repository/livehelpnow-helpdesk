<?php
/**
 * Should display chat on given page settings
 *
 * @package LHNChat
 */

?>

<label for="chat_display">
	<input
		type="checkbox"
		name="chat_display"
		id="chat_display"
		value="1"
		<?php checked( $display, 1 ); ?>
	/>
	<?php
	printf(
		esc_html__( 'Display chat on this %s', 'lhnchat' ),
		get_post_type()
	);
	?>
</label>
