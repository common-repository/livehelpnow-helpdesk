<?php
/**
 * Chat settings, when user is authenticated
 *
 * @package LHNChat
 */

?>

<input type="hidden" name="authenticated" value="true" />
<p>
	<select name="chat_widget" id="chat_widget">
		<?php if ( $embedded_widgets ) : ?>
			<?php $selected_widget = get_post_meta( get_the_ID(), 'chat_widget_id', true ); ?>
			<?php foreach ( $embedded_widgets as $widget ) : ?>
				<option value="<?php echo $widget->id; ?>" <?php selected( $widget->id, $selected_widget ); ?>>
					<?php echo $widget->name; ?>
				</option>
			<?php endforeach; ?>
		<?php endif; ?>
	</select>
</p>
