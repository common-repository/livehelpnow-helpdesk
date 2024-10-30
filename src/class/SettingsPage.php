<?php
/**
 * Plugin settings page class file
 *
 * @package LHNChat
 */

namespace LHNChat;

/**
 * Plugin settings page
 */
class SettingsPage extends Abstracts\WPIntegrator implements Interfaces\Hookable {

	/**
	 * Register settings menu page
	 *
	 * @action admin_menu
	 *
	 * @return string The resulting page's hook_suffix
	 * @link https://developer.wordpress.org/reference/functions/add_menu_page/
	 */
	public function settings_menu_page() {

		return add_menu_page(
			__( 'LiveHelpNow! Chat settings.', 'lhnchat' ),
			__( 'LiveHelpNow!', 'lhnchat' ),
			'manage_options',
			'livehelpnow-chat',
			null,
			'dashicons-format-status',
			85
		);

	}

	/**
	 * Register settings submenu page
	 * It's registerd only for convienience, always redirects to
	 * main settings page
	 *
	 * @action admin_menu
	 *
	 * @return string The resulting page's hook_suffix
	 * @link https://developer.wordpress.org/reference/functions/add_menu_page/
	 */
	public function settings_submenu_page() {

		return add_submenu_page(
			'livehelpnow-chat',
			__( 'LiveHelpNow!', 'lhnchat' ),
			__( 'Settings', 'lhnchat' ),
			'manage_options',
			'livehelpnow-chat',
			[
				$this,
				'settings_page',
			]
		);

	}

	/**
	 * Display plugin main settings page
	 *
	 * @return void
	 */
	public function settings_page() {

		$this->page_header();

		if ( ! get_option( 'lhn_authentication_info' ) ) {
			require_once LHN_CHAT_PLUGIN_DIR_PATH . 'src/templates/admin/authentication-info.php';
			return;
		}

		$this->page_content();

	}

	/**
	 * Display settings page header
	 *
	 * @return void
	 */
	protected function page_header() {

		printf(
			'<div class="wrap">%s</div>',
			sprintf(
				'<h1>%s</h1>',
				esc_html__( 'LiveHelpNow! Settings', 'lhnchat' )
			)
		);

	}

	/**
	 * Display settings page content
	 *
	 * @return void
	 */
	protected function page_content() {

		require_once LHN_CHAT_PLUGIN_DIR_PATH . 'src/templates/admin/settings-page.php';

	}

	/**
	 * Register all plugin settings
	 *
	 * @action admin_init
	 *
	 * @return void
	 */
	public function register_settings() {

		// register a new setting for "livehelpnow-chat" page.
		register_setting( 'livehelpnow-chat', 'lhn_display_chat' );
		register_setting( 'livehelpnow-chat', 'lhn_default_chat' );
		register_setting( 'livehelpnow-chat', 'lhn_remove_plugin_data' );

		// register a new section in the "livehelpnow-chat" page.
		add_settings_section(
			'lhn_general_settings_section',
			esc_html__( 'General Settings', 'lhnchat' ),
			null,
			'livehelpnow-chat'
		);

		// register a new field in the "lhn_general_settings_section" section, inside the "livehelpnow-chat" page.
		add_settings_field(
			'lhn_display_chat_field',
			esc_html__( 'Display chat on every page', 'lhnchat' ),
			[
				$this,
				'lhn_display_chat_field'
			],
			'livehelpnow-chat',
			'lhn_general_settings_section'
		);

		// register a new field in the "lhn_general_settings_section" section, inside the "livehelpnow-chat" page.
		add_settings_field(
			'lhn_default_chat_field',
			esc_html__( 'Default chat to display', 'lhnchat' ),
			[
				$this,
				'lhn_default_chat_field'
			],
			'livehelpnow-chat',
			'lhn_general_settings_section'
		);

		$auth = new API\Authentication();

		if ( $auth->is_user_authenticated() ) {

			// register a new field in the "lhn_general_settings_section" section, inside the "livehelpnow-chat" page.
			add_settings_field(
				'lhn_destroy_session_field',
				esc_html__( 'Logout from LiveHelpNow!', 'lhnchat' ),
				[
					$this,
					'lhn_destroy_session_field'
				],
				'livehelpnow-chat',
				'lhn_general_settings_section'
			);

		}

		// register a new field in the "lhn_general_settings_section" section, inside the "livehelpnow-chat" page.
		add_settings_field(
			'lhn_remove_plugin_data_field',
			esc_html__( 'Remove all plugin data after deactivation', 'lhnchat' ),
			[
				$this,
				'lhn_remove_plugin_data_field'
			],
			'livehelpnow-chat',
			'lhn_general_settings_section'
		);

	}

	/**
	 * Chat display field
	 *
	 * @return void
	 */
	public function lhn_display_chat_field() {

		$display_chat = get_option( 'lhn_display_chat' );

		?>
		<label>
			<input type="checkbox" name="lhn_display_chat" value="1"<?php checked( $display_chat, 1 ); ?> />
			<?php esc_html_e( 'Display chat on every page or post', 'lhnchat' ); ?>
		</label>
		<p class="description">
			<?php esc_html_e( 'Can be overriden per page', 'lhnchat' ); ?>
		</p>
		<?php

	}

	/**
	 * Default chat field
	 *
	 * @return void
	 */
	public function lhn_default_chat_field() {

		$default_chat = get_option( 'lhn_default_chat' );
		$chats = new \WP_Query([
			'posts_per_page' => -1,
			'post_type' => 'lhn-chat',
			'post_status' => 'publish',
		]);
		?>

		<?php if ( $chats->have_posts() ) : ?>
			<select name="lhn_default_chat">
				<?php while ( $chats->have_posts() ) : ?>
					<?php $chats->the_post(); ?>
					<option value="<?php echo get_the_ID(); ?>" <?php selected( get_the_ID(), $default_chat ); ?>>
						<?php the_title(); ?>
					</option>
				<?php endwhile; ?>
			</select>
			<p class="description">
				<?php esc_html_e( 'Can be overriden per page', 'lhnchat' ); ?>
			</p>
		<?php endif;

	}

	/**
	 * Default chat field
	 *
	 * @return void
	 */
	public function lhn_destroy_session_field() {

		?>

		<a href="<?php echo esc_attr( admin_url( 'admin-post.php' ) ); ?>?action=lhn_destroy_session&referer=<?php echo urlencode( basename( $_SERVER['REQUEST_URI'] ) ); ?>" class="button button-primary">
			<?php esc_html_e( 'Logout', 'lhnchat' ); ?>
		</a>
		<p class="description">
			<?php esc_html_e( 'Destroys user session. Can be useful if you want to login to another account.', 'lhnchat' ); ?>
		</p>

		<?php

	}

	/**
	 * Remove plugin data option
	 *
	 * @return void
	 */
	public function lhn_remove_plugin_data_field() {

		$remove_plugin_data = get_option( 'lhn_remove_plugin_data' );

		?>

		<label>
			<input type="checkbox" name="lhn_remove_plugin_data" value="1"<?php checked( $remove_plugin_data, 1 ); ?> />
			<?php esc_html_e( 'Remove all data after deactivation', 'lhnchat' ); ?>
		</label>
		<p class="description">
			<?php esc_html_e( 'Use with caution. This will remove all chats and settings after you deactivate plugin.', 'lhnchat' ); ?>
		</p>

		<?php

	}

}
