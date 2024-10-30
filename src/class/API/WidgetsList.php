<?php
/**
 * Class for managing embedded list widgets with LiveHelpNow API
 *
 * @package LHNChat
 */

namespace LHNChat\API;

/**
 * WidgetsList plugin class
 */
class WidgetsList extends \LHNChat\Abstracts\WPIntegrator implements \LHNChat\Interfaces\Hookable {

	/**
	 * Gets all widgets (chats) added in LiveHelpNow admin panel
	 *
	 * @return array|WP_Error list of all available widgets.
	 */
	public function get_widgets() {

		$api = new API();
		$response = $api->get_response( 'https://developer.livehelpnow.net/api/ui/embedded/list' );

		if ( ! is_object( $response ) || ! property_exists( $response, 'payload' ) ) {
			return new \WP_Error(
				'wrong-response',
				'There is something wrong with API response. It\'s either empty, or not an object or doesn\'t have "payload" property'
			);
		}

		return $response->payload;

	}
}
