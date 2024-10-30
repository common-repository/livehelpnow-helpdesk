<?php
/**
 * Should display chat on given page settings
 *
 * @package LHNChat
 */

?>

<label for="chat_hide">
	<input
		type="checkbox"
		name="chat_hide"
		id="chat_hide"
		value="1"
		<?php checked( $hide, 1 ); ?>
	/>
	<?php
	printf(
		esc_html__( 'Hide chat on this %s', 'lhnchat' ),
		get_post_type()
	);
	?>
</label>
