<?php
/**
 * Class for managing widgets options list widgets with LiveHelpNow API
 *
 * @package LHNChat
 */

namespace LHNChat\API;

/**
 * WidgetsOptions plugin class
 */
class WidgetsOptions extends \LHNChat\Abstracts\WPIntegrator implements \LHNChat\Interfaces\Hookable {

	/**
	 * Gets all widgets (chats) options added in LiveHelpNow admin panel
	 *
	 * @param string $type type of options to get. Avaliable options / dictionary.
	 * @return array list of all available widgets.
	 */
	public function get_widgets_options( $type = 'options' ) {

		$api = new API();
		$response = $api->get_response( 'https://developer.livehelpnow.net/api/ui/embedded/options' );

		if ( $type != 'options' && $type != 'dictionary' ) {
			return new \WP_Error(
				'wrong-options-type',
				'The only allowed options in $type argument of get_widgets_options method is "options" or "dictionary"'
			);
		}

		if ( ! is_object( $response ) || ! property_exists( $response, 'payload' ) ) {
			return new \WP_Error(
				'wrong-response',
				'There is something wrong with API response. It\'s either empty, or not an object or doesn\'t have "payload" property'
			);
		}

		if ( ! is_object( $response->payload ) || ! property_exists( $response->payload, $type ) ) {
			return new \WP_Error(
				'wrong-response-payload',
				'There is available "payload" property in response but it\'s either empty, non object or does not have one of these properties: "options", "dictionary"'
			);
		}

		return $response->payload->{$type};

	}
}
