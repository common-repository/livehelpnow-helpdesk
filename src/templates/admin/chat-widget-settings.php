<?php
/**
 * Should display chat on given page settings
 *
 * @package LHNChat
 */

$meta_widget_id = get_post_meta( get_the_ID(), 'chat_widget_id', true );
$chats = new \WP_Query([
	'posts_per_page' => -1,
	'post_type' => 'lhn-chat',
	'post_status' => 'publish',
]);
?>

<?php if ( $chats->have_posts() ) : ?>
	<select name="chat_widget_id">
		<?php foreach ( $chats->get_posts() as $chat ) : ?>
			<option value="<?php echo $chat->ID; ?>" <?php selected( $chat->ID, $meta_widget_id ); ?>>
				<?php echo apply_filters( 'the_title', $chat->post_title ); ?>
			</option>
		<?php endforeach; ?>
	</select>
<?php endif;
