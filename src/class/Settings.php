<?php
/**
 * Plugin settings class file
 *
 * @package LHNChat
 */

namespace LHNChat;

/**
 * Plugin settings
 */
class Settings extends Abstracts\WPIntegrator implements Interfaces\Hookable {

	/**
	 * Decide should chat be displayed on give page
	 *
	 * @TODO make it more clear, avoid using else. Maybe separate to smaller methods
	 *
	 * @return bool
	 */
	public function should_display_chat() {

		if ( ! get_option( 'lhn_authentication_info' ) ) {
			return;
		}

		$display_chat = get_option( 'lhn_display_chat' );

		$display = false;

		if ( $display_chat ) {
			$display = true;

			if ( get_post_meta( get_the_ID(), 'chat_hide', true ) ) {
				$display = false;
			}
		} else {
			$display = false;

			if ( get_post_meta( get_the_ID(), 'chat_display', true ) ) {
				$display = true;
			}
		}

		return $display;

	}

	/**
	 * Add chat settings metabox
	 *
	 * @action add_meta_boxes
	 *
	 * @return void
	 */
	public function meta_boxes() {

		$this->chat_display_metabox();
		$this->chat_hide_metabox();
		$this->chat_widget_to_display_metabox();

	}

	/**
	 * Chat display metabox
	 *
	 * @return bool|void
	 * @link https://developer.wordpress.org/reference/functions/add_meta_box/
	 */
	public function chat_display_metabox() {

		if ( get_option( 'lhn_display_chat' ) ) {
			return;
		}

		return add_meta_box(
			'chat-display-settings',
			__( 'Display LiveHelpNow Chat', 'lhnchat' ),
			[
				$this,
				'display_chat_display_metabox',
			],
			apply_filters( 'lhn_chat_display_post_types', [
				'post',
				'page'
			] ),
			'side'
		);

	}

	/**
	 * Display metabox callback
	 *
	 * @param  WP_Post $post post object.
	 * @return void
	 */
	public function display_chat_display_metabox( $post ) {

		$display = get_post_meta( $post->ID, 'chat_display', true );
		require_once LHN_CHAT_PLUGIN_DIR_PATH . 'src/templates/admin/chat-display-settings.php';

	}

	/**
	 * Add metabox to hide chat on specific pages
	 *
	 * @return bool|void
	 * @link https://developer.wordpress.org/reference/functions/add_meta_box/
	 */
	public function chat_hide_metabox() {

		if ( ! get_option( 'lhn_display_chat' ) ) {
			return;
		}

		return add_meta_box(
			'chat-hide-settings',
			__( 'Hide LiveHelpNow Chat', 'lhnchat' ),
			[
				$this,
				'display_chat_hide_metabox',
			],
			apply_filters( 'lhn_chat_display_post_types', [
				'post',
				'page'
			] ),
			'side'
		);

	}

	/**
	 * Display metabox callback
	 *
	 * @param  WP_Post $post post object.
	 * @return void
	 */
	public function display_chat_hide_metabox( $post ) {

		$hide = get_post_meta( $post->ID, 'chat_hide', true );
		require_once LHN_CHAT_PLUGIN_DIR_PATH . 'src/templates/admin/chat-hide-settings.php';

	}

	/**
	 * Add metabox to hide chat on specific pages
	 *
	 * @return bool|void
	 * @link https://developer.wordpress.org/reference/functions/add_meta_box/
	 */
	public function chat_widget_to_display_metabox() {

		return add_meta_box(
			'chat-widget-settings',
			__( 'Select LiveHelpNow Chat Widget', 'lhnchat' ),
			[
				$this,
				'display_chat_widget_metabox',
			],
			apply_filters( 'lhn_chat_display_post_types', [
				'post',
				'page'
			] ),
			'side'
		);

	}

	/**
	 * Display metabox callback
	 *
	 * @param  WP_Post $post post object.
	 * @return void
	 */
	public function display_chat_widget_metabox( $post ) {

		$hide = get_post_meta( $post->ID, 'chat_widget_id', true );
		require_once LHN_CHAT_PLUGIN_DIR_PATH . 'src/templates/admin/chat-widget-settings.php';

	}

	/**
	 * Decide should custom page widget or default one to display
	 *
	 * @return string chat post ID.
	 */
	public function get_chat_widget_to_display() {

		$meta_widget_id = get_post_meta( get_the_ID(), 'chat_widget_id', true );
		if ( $meta_widget_id ) {
			return $meta_widget_id;
		}

		return get_option( 'lhn_default_chat' );

	}

	/**
	 * Save form settings
	 *
	 * @action save_post
	 *
	 * @param int $post_id post ID.
	 * @return void
	 */
	public function update_post_settings( $post_id ) {

		update_post_meta( $post_id, 'chat_display', (bool) $_POST['chat_display'] );
		update_post_meta( $post_id, 'chat_hide', (bool) $_POST['chat_hide'] );

		if ( ! empty( $_POST['chat_widget_id'] ) ) {
			update_post_meta( $post_id, 'chat_widget_id', $_POST['chat_widget_id'] );
		}

	}
}
