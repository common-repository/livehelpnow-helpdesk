<?php
/**
 * Chat class file
 *
 * @package LHNChat
 */

namespace LHNChat;

/**
 * Chat
 */
class Chat extends Abstracts\WPIntegrator implements Interfaces\Hookable {

	/**
	 * Register all settings
	 *
	 * @action init
	 *
	 * @return WP_Post_Type|WP_Error The registered post type object, or an error object.
	 * @link https://developer.wordpress.org/reference/functions/register_post_type/
	 */
	public function register_chat_post_type() {

		$labels = [
			'name'                  => _x( 'Chats', 'Post type general name', 'lhnchat' ),
			'singular_name'         => _x( 'Chat', 'Post type singular name', 'lhnchat' ),
			'menu_name'             => _x( 'Chats', 'Admin Menu text', 'lhnchat' ),
			'name_admin_bar'        => _x( 'Chat', 'Add New on Toolbar', 'lhnchat' ),
			'add_new_item'          => __( 'Add New Chat', 'lhnchat' ),
			'new_item'              => __( 'New Chat', 'lhnchat' ),
			'edit_item'             => __( 'Edit Chat', 'lhnchat' ),
			'view_item'             => __( 'View Chat', 'lhnchat' ),
			'all_items'             => __( 'All Chats', 'lhnchat' ),
			'search_items'          => __( 'Search Chats', 'lhnchat' ),
			'not_found'             => __( 'No chats found.', 'lhnchat' ),
			'not_found_in_trash'    => __( 'No chats found in Trash.', 'lhnchat' ),
			'filter_items_list'     => _x( 'Filter chats list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'lhnchat' ),
		];

		$args = [
			'labels'             => $labels,
			'capability_type'    => 'post',
			'show_ui'            => true,
			'show_in_menu'       => 'livehelpnow-chat',
			'public'             => false,
			'publicly_queryable' => false,
			'query_var'          => false,
			'rewrite'            => false,
			'has_archive'        => false,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => [
				'title',
			],
		];

		return register_post_type( 'lhn-chat', $args );

	}

	/**
	 * Remove chat slug metabox
	 *
	 * @action add_meta_boxes
	 *
	 * @return bool|void
	 * @link https://developer.wordpress.org/reference/functions/remove_meta_box/
	 */
	public function remove_slug_meta_box() {

		remove_meta_box(
			'slugdiv',
			'lhn-chat',
			'normal'
		);

	}

	/**
	 * Add chat widget metabox
	 *
	 * @action add_meta_boxes
	 *
	 * @return bool|void
	 * @link https://developer.wordpress.org/reference/functions/add_meta_box/
	 */
	public function chat_authentication_metabox() {

		$auth = new API\Authentication();

		if ( $auth->is_user_authenticated() ) {
			return;
		}

		return add_meta_box(
			'chat-settings-authentication',
			__( 'LiveHelpNow Authentication Required', 'lhnchat' ),
			[
				$this,
				'display_chat_authentication_metabox',
			],
			'lhn-chat',
			'normal'
		);

	}

	/**
	 * Display metabox callback
	 *
	 * @param  WP_Post $post post object.
	 * @return void
	 */
	public function display_chat_authentication_metabox( $post ) {

		require_once LHN_CHAT_PLUGIN_DIR_PATH . 'src/templates/admin/authentication-info.php';

	}

	/**
	 * Add chat widget metabox
	 *
	 * @action add_meta_boxes
	 *
	 * @return bool|void
	 * @link https://developer.wordpress.org/reference/functions/add_meta_box/
	 */
	public function chat_widget_metabox() {

		$auth = new API\Authentication();

		if ( ! $auth->is_user_authenticated() ) {
			return;
		}

		return add_meta_box(
			'chat-settings-widget',
			__( 'LiveHelpNow Chat Widget', 'lhnchat' ),
			[
				$this,
				'display_chat_widget_metabox',
			],
			'lhn-chat',
			'normal'
		);

	}

	/**
	 * Display metabox callback
	 *
	 * @param  WP_Post $post post object.
	 * @return void
	 */
	public function display_chat_widget_metabox( $post ) {

		$list = new API\WidgetsList();
		$embedded_widgets = $list->get_widgets();

		require_once LHN_CHAT_PLUGIN_DIR_PATH . 'src/templates/admin/chat-settings-widget.php';

	}

	/**
	 * Add chat options metabox
	 *
	 * @action add_meta_boxes
	 *
	 * @return bool|void
	 * @link https://developer.wordpress.org/reference/functions/add_meta_box/
	 */
	public function chat_options_metabox() {

		$auth = new API\Authentication();

		if ( ! $auth->is_user_authenticated() ) {
			return;
		}

		return add_meta_box(
			'chat-settings-options',
			__( 'Optional: LiveHelpNow Chat Options', 'lhnchat' ),
			[
				$this,
				'display_chat_options_metabox',
			],
			'lhn-chat',
			'normal'
		);

	}

	/**
	 * Display metabox callback
	 *
	 * @param  WP_Post $post post object.
	 * @return void
	 */
	public function display_chat_options_metabox( $post ) {

		$options = new API\WidgetsOptions();
		$widgets_options = $options->get_widgets_options();

		require_once LHN_CHAT_PLUGIN_DIR_PATH . 'src/templates/admin/chat-settings-options.php';

	}

	/**
	 * Add chat dictionary metabox
	 *
	 * @action add_meta_boxes
	 *
	 * @return bool|void
	 * @link https://developer.wordpress.org/reference/functions/add_meta_box/
	 */
	public function chat_dictionary_metabox() {

		$auth = new API\Authentication();

		if ( ! $auth->is_user_authenticated() ) {
			return;
		}

		return add_meta_box(
			'chat-settings-dictionary',
			__( 'Optional: LiveHelpNow Chat Dictionary', 'lhnchat' ),
			[
				$this,
				'display_chat_dictionary_metabox',
			],
			'lhn-chat',
			'normal'
		);

	}

	/**
	 * Display metabox callback
	 *
	 * @param  WP_Post $post post object.
	 * @return void
	 */
	public function display_chat_dictionary_metabox( $post ) {

		$options = new API\WidgetsOptions();
		$widgets_dictionary = $options->get_widgets_options( 'dictionary' );

		require_once LHN_CHAT_PLUGIN_DIR_PATH . 'src/templates/admin/chat-settings-dictionary.php';

	}

	/**
	 * Collapse settings and dictionary metaboxes
	 *
	 * @action current_screen
	 *
	 * @return void
	 */
	public function collapse_metaboxes() {

		$screen = get_current_screen();

		if ( ! is_a( $screen, 'WP_Screen' ) || 'lhn-chat' != $screen->id || ! in_array( $screen->action, ['edit', 'add'] ) ) {
			return;
		}

		update_user_meta( get_current_user_id(), 'closedpostboxes_lhn-chat', array( 'chat-settings-dictionary', 'chat-settings-options' ) );

	}

	/**
	 * Display authentication form in footer
	 *
	 * @action admin_footer
	 *
	 * @param int $post_id post ID.
	 * @return void
	 */
	public function display_authenticaion_form( $post_id ) {

		add_thickbox();
		require_once LHN_CHAT_PLUGIN_DIR_PATH . 'src/templates/admin/authentication-form.php';

	}

	/**
	 * Save form settings
	 *
	 * @action save_post_lhn-chat
	 *
	 * @param int $post_id post ID.
	 * @return void
	 */
	public function update_chat_settings( $post_id ) {

		if ( empty( $_POST['authenticated'] ) ) {
			return;
		}

		if ( ! empty( $_POST['chat_widget'] ) ) {
			update_post_meta( $post_id, 'chat_widget_id', $_POST['chat_widget'] );
		}

		$options = $_POST['chat_option'];
		$this->convert_numbers_to_integers( $options );
		$this->convert_strings_to_booleans( $options );
		$this->filter_empty_values( $options );

		$dictionary = $_POST['chat_dictionary'];
		$this->convert_numbers_to_integers( $dictionary );
		$this->convert_strings_to_booleans( $dictionary );
		$this->filter_empty_values( $dictionary );

		update_post_meta( $post_id, 'chat_options', $options );
		update_post_meta( $post_id, 'chat_dictionary', $dictionary );

	}

	/**
	 * Filters empty values from array (can be multidimensional)
	 * Array is passed by reference
	 *
	 * @param  array $options options array to filter.
	 * @return void           works directly on array reference.
	 */
	protected function filter_empty_values( &$options ) {
		foreach ( $options as $key => &$value ) {
			if ( is_array( $value ) ) {
				$this->filter_empty_values( $value );
			}
			if ( empty( $value ) ) {
				unset( $options[ $key ] );
			}
		}
	}

	/**
	 * Converts strings values from array (can be multidimensional)
	 * to integers if value is valid one
	 * Array is passed by reference
	 *
	 * @param  array $options options array to convert.
	 * @return void           works directly on array reference.
	 */
	protected function convert_numbers_to_integers( &$options ) {
		foreach ( $options as $key => &$value ) {
			if ( is_array( $value ) ) {
				$this->convert_numbers_to_integers( $value );
			}
			if ( ctype_digit( $value ) ) {
				$options[ $key ] = (int) $value;
			}
		}
	}

	/**
	 * Converts strings values from array (can be multidimensional)
	 * to integers if value is valid one
	 * Array is passed by reference
	 *
	 * @param  array $options options array to convert.
	 * @return void           works directly on array reference.
	 */
	protected function convert_strings_to_booleans( &$options ) {
		foreach ( $options as $key => &$value ) {
			if ( is_array( $value ) ) {
				$this->convert_strings_to_booleans( $value );
			}
			if ( in_array( $value, [ 'true', 'false', 'yes', 'no' ] ) ) {
				$options[ $key ] = filter_var( $value, FILTER_VALIDATE_BOOLEAN );
			}
		}
	}
}
