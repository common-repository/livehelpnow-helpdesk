<?php
/**
 * Chat settings, when user is authenticated
 *
 * @package LHNChat
 */

?>

<?php if ( ! is_wp_error( $widgets_dictionary ) ) : ?>
	<?php $widgets_dictionary_meta = get_post_meta( get_the_ID(), 'chat_dictionary', true ); ?>
	<?php foreach ( $widgets_dictionary as $option ) : ?>
		<div class="post-attributes-label-wrapper">
			<?php
			echo call_user_func_array(
				[
					new LHNChat\SettingsField( $option, 'dictionary' ),
					$option->type . '_field',
				],
				[
					$widgets_dictionary_meta,
				]
			);
			?>
		</div>
	<?php endforeach; ?>
<?php else : ?>
	<?php require_once LHN_CHAT_PLUGIN_DIR_PATH . 'src/templates/admin/api-error-info.php'; ?>
<?php endif; ?>