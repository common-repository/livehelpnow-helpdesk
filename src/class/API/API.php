<?php
/**
 * Class for interacting with LHN API
 *
 * @package LHNChat
 */

namespace LHNChat\API;

/**
 * LHN API class
 */
class API extends \LHNChat\Abstracts\WPIntegrator implements \LHNChat\Interfaces\Hookable {

	/**
	 * Get response from LHN API
	 *
	 * @param string $endpoint url to get reponse from.
	 * @return object response object.
	 */
	public function get_response( $endpoint ) {

		if ( empty( $endpoint ) ) {
			return;
		}

		$auth_info = get_option( 'lhn_authentication_info' );

		if ( empty( $auth_info ) ) {
			return;
		}

		$curl_handler = curl_init( $endpoint );
		curl_setopt( $curl_handler, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $curl_handler, CURLOPT_HTTPHEADER, [
			"Content-Type: application/json",
			"Authorization: {$auth_info->token_type} {$auth_info->access_token}",
		] );

		$data = curl_exec( $curl_handler );
		$data = json_decode( $data );

		if ( JSON_ERROR_NONE != json_last_error() ) {
			return;
		}

		return $data;

	}
}
