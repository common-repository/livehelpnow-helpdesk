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
class Assets extends Abstracts\WPIntegrator implements Interfaces\Hookable {

	/**
	 * Enqueue front end scripts and styles
	 *
	 * @action wp_enqueue_scripts
	 *
	 * @return void
	 */
	public function enqueue_scripts() {

		$this->enqueue_chat_script();

	}

	/**
	 * Enqueue chat script
	 *
	 * @return void
	 */
	protected function enqueue_chat_script() {

		$settings = new Settings();

		if ( ! $settings->should_display_chat() ) {
			return;
		}

		$user = wp_get_current_user();

		if ($user) {
			$user_name = property_exists( $user, 'data' ) && property_exists( $user->data, 'display_name' ) ? $user->data->display_name : null;
			$user_email = property_exists( $user, 'data' ) && property_exists( $user->data, 'user_email' )  ? $user->data->user_email : null;
		}

		$woo_cart = '';

		if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
			global $woocommerce;
			$items = $woocommerce->cart->get_cart();
			$total = $woocommerce->cart->total;

			foreach( $items as $item => $values ) {
				$_product =  wc_get_product( $values['data']->get_id() );
				$woo_cart .= $_product->get_title();
				$woo_cart .= ' • Price: ' . get_post_meta( $values['product_id'] , '_price', true );
				$woo_cart .= ' • Quantity: ' . $values['quantity'] . ' | ';
			}

			$woo_cart .= 'Total: ' . $total;
		}

		$options = get_post_meta( $settings->get_chat_widget_to_display(), 'chat_options', true );
		$dictionary = get_post_meta( $settings->get_chat_widget_to_display(), 'chat_dictionary', true );

		wp_enqueue_script( 'lhn-chat', LHN_CHAT_PLUGIN_DIR_URL . 'assets/dist/scripts/chat.js', [], null, true );
		wp_localize_script( 'lhn-chat', 'lhnchat', [
			'application_id' => get_option( 'lhn_oauth_id' ),
			'application_secret' => get_option( 'lhn_oauth_secret' ),
			'widget_id' => get_post_meta( $settings->get_chat_widget_to_display(), 'chat_widget_id', true ),
			'chat_options' => is_array( $options ) ? $options : [],
			'chat_dictionary' => is_array( $dictionary ) ? $dictionary : [],
			'user' => [
				'display_name' => isset( $user_name ) ? $user_name : '',
				'email' => isset( $user_email ) ? $user_email : '',
				'cart' => $woo_cart,
			]
		] );

	}

	/**
	 * Enqueue admin scripts and styles
	 *
	 * @action admin_enqueue_scripts
	 *
	 * @return void
	 */
	public function admin_enqueue_scripts() {

		wp_enqueue_style( 'login' );
		wp_enqueue_style( 'lhn-admin-styles', LHN_CHAT_PLUGIN_DIR_URL . 'assets/dist/styles/admin.css' );

	}
}
