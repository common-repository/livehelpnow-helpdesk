<?php
/**
 * Main plugin class file
 *
 * @package LHNChat
 */

namespace LHNChat;

/**
 * Main plugin class
 */
class Plugin extends Abstracts\WPIntegrator implements Interfaces\Hookable {

	const REQUIRED_PHP = '5.4';

	/**
	 * Init plugin
	 *
	 * @action plugins_loaded
	 *
	 * @return void
	 */
	public function plugin_init() {

		if ( version_compare( phpversion(), self::REQUIRED_PHP, '<' ) ) {
			return;
		}

		$this->assets();
		$this->settings();
		$this->authentication();
		$this->chat();

	}

	/**
	 * Register all assets
	 *
	 * @return void
	 */
	public function assets() {

		$settings = new Assets();
		$settings->register_hooks();

	}

	/**
	 * Register all settings
	 *
	 * @return void
	 */
	public function settings() {

		$settings = new Settings();
		$settings->register_hooks();

		$settings_page = new SettingsPage();
		$settings_page->register_hooks();

	}

	/**
	 * Add authentication class
	 *
	 * @return void
	 */
	public function authentication() {

		$auth = new API\Authentication();
		$auth->register_hooks();

	}

	/**
	 * Register chat
	 *
	 * @return void
	 */
	public function chat() {

		$chat = new Chat();
		$chat->register_hooks();

	}

	/**
	 * Display required PHP version notice.
	 *
	 * @action admin_notices
	 *
	 * @return void
	 */
	public function display_authentication_notice() {

		if ( version_compare( phpversion(), self::REQUIRED_PHP, '>=' ) ) {
			return;
		}

		?>
		<div class="notice notice-error is-dismissible">
			<p>
				<?php printf( esc_html__( 'LiveHelpNow Help Desk plugin requires PHP %s or higher.', 'lhnchat' ), self::REQUIRED_PHP ); ?>
			</p>
		</div>
		<?php
	}

	/**
	 * Fires on plugin activation
	 *
	 * @return void
	 */
	public function on_plugin_activation() {

		if ( ! function_exists('curl_version') ) {
			printf( '<p>%s<p>', __('This plugin requires cURL extension to be installed and activated in order to work.', 'lhnchat' ) );
			@trigger_error( __( 'This plugin requires cURL extension to be installed and activated.', 'lhnchat' ), E_USER_ERROR );
		}

		if ( ! wp_next_scheduled ( 'lhn_destroy_user_session_schedule' ) ) {
			wp_schedule_event( time(), 'hourly', 'lhn_destroy_user_session_schedule' );
		}

		if ( get_page_by_title( esc_html__( 'Sample Chat', 'lhnchat' ), 'OBJECT', 'lhn-chat') ) {
			return;
		}

		$sample_chat = wp_insert_post( [
			'post_status' => 'publish',
			'post_type' => 'lhn-chat',
			'post_title' => esc_html__( 'Sample Chat', 'lhnchat' ),
		] );

		update_option( 'lhn_first_authentication_passed', false );
		update_option( 'lhn_display_chat', true );
		update_option( 'lhn_default_chat', $sample_chat );

	}

	/**
	 * Fires on plugin deactivation
	 *
	 * @return void
	 */
	public function on_plugin_deactivation() {

		wp_clear_scheduled_hook( 'lhn_destroy_user_session_schedule' );

		if ( ! get_option( 'lhn_remove_plugin_data' ) ) {
			return;
		}

		delete_option( 'lhn_first_authentication_passed' );
		delete_option( 'lhn_authentication_info' );
		delete_option( 'lhn_oauth_id' );
		delete_option( 'lhn_oauth_secret' );
		delete_option( 'lhn_session_destroyed' );
		delete_option( 'lhn_display_chat' );
		delete_option( 'lhn_default_chat' );
		delete_option( 'lhn_remove_plugin_data' );

		$chats = new \WP_Query([
			'posts_per_page' => -1,
			'post_type' => 'lhn-chat',
			'post_status' => 'any',
		]);

		foreach ( $chats->posts as $chat ) {
			delete_post_meta( $chat->ID, 'chat_widget_id' );
			delete_post_meta( $chat->ID, 'chat_options' );
			delete_post_meta( $chat->ID, 'chat_dictionary' );
			wp_delete_post( $chat->ID, true );
		}

		$posts = new \WP_Query([
			'posts_per_page' => -1,
			'post_type' => 'any',
			'post_status' => 'any',
		]);

		foreach ( $posts->posts as $post ) {
			delete_post_meta( $post->ID, 'chat_display' );
			delete_post_meta( $post->ID, 'chat_hide' );
			delete_post_meta( $post->ID, 'chat_widget_id' );
		}

	}
}
