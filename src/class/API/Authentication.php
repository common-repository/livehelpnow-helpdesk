<?php
/**
 * Class for authentication with LiveHelpNow API
 *
 * @package LHNChat
 */

namespace LHNChat\API;

/**
 * Authentication plugin class
 */
class Authentication extends \LHNChat\Abstracts\WPIntegrator implements \LHNChat\Interfaces\Hookable {

	/**
	 * Check is user authenticated in livehelpnow service.
	 * Made by pulling widgets list and checking is everything fine.
	 *
	 * @return boolean
	 */
	public function is_user_authenticated() {

		if ( get_option( 'lhn_session_destroyed' ) ) {
			return false;
		}

		$api = new API();
		$response = $api->get_response( 'https://developer.livehelpnow.net/api/ui/embedded/list' );

		if ( ! is_object( $response ) || ! property_exists( $response, 'status' ) ) {
			return false;
		}

		return 'ok' === $response->status ? true : false;

	}

	/**
	 * On admin post. Send request to API and tr to authenticate.
	 *
	 * @action admin_post_lhn_authenticate
	 *
	 * @return void
	 */
	public function authenticate_user() {

		if( empty( $_POST['username'] ) || empty( $_POST['password'] ) ) {
			return;
		}

		$username = $_POST['username'];
		$password = $_POST['password'];

		$auth_url = sprintf(
			'https://developer.livehelpnow.net/oauth/token?%s',
			sprintf(
				'password=%s&username=%s&grant_type=%s&client_id&client_secret&include_oauth_credentials=%s',
				urlencode( $password ),
				urlencode( $username ),
				'password',
				'js_sdk'
			)
		);

		$curl_handler = curl_init( $auth_url );
		curl_setopt( $curl_handler, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $curl_handler, CURLOPT_HTTPHEADER, [
			'Content-Type: application/json',
		] );

		$data = curl_exec( $curl_handler );
		$data = json_decode( $data );

		if ( JSON_ERROR_NONE != json_last_error() ) {

			delete_option( 'lhn_authentication_info' );

			wp_redirect( add_query_arg( [
				'response' => urlencode( $data ),
			], admin_url( $_POST['referer'] ) ) );

			die();

		}

		update_option( 'lhn_authentication_info', $data );
		update_option( 'lhn_oauth_id', $data->oauth_credentials->id );
		update_option( 'lhn_oauth_secret', $data->oauth_credentials->secret );
		update_option( 'lhn_session_destroyed', false );

		$list = new WidgetsList();
		$embedded_widgets = $list->get_widgets();

		if ( ! get_option( 'lhn_first_authentication_passed' )
			&& is_array( $embedded_widgets )
			&& isset( $embedded_widgets[0] )
			&& property_exists( $embedded_widgets[0], 'id' )
		) {
			update_post_meta( get_option( 'lhn_default_chat' ), 'chat_widget_id', $embedded_widgets[0]->id );
		}

		update_option( 'lhn_first_authentication_passed', true );

		wp_redirect( add_query_arg( [
			'authenticated' => 'true',
		], admin_url( $_POST['referer'] ) ) );

		die();

	}

	/**
	 * Destroy user session.
	 *
	 * @action admin_post_lhn_destroy_session
	 *
	 * @return void
	 */
	public function destroy_session() {

		update_option( 'lhn_session_destroyed', true );

		wp_redirect( add_query_arg( [
			'session-destroyed' => 'true',
		], admin_url( urldecode( $_GET['referer'] ) ) ) );

		die();

	}

	/**
	 * Destroy user session.
	 *
	 * @action lhn_destroy_user_session_schedule
	 *
	 * @return void
	 */
	public function destroy_session_schedule() {

		update_option( 'lhn_session_destroyed', true );

	}

	/**
	 * Display notice that no LHN user was authenticated yet.
	 *
	 * @action admin_notices
	 *
	 * @return void
	 */
	public function display_authentication_notice() {

		if ( get_option( 'lhn_authentication_info' ) ) {
			return;
		}

		if ( ! empty ( $_GET['page'] ) && 'livehelpnow-chat' === $_GET['page'] ) {
			return;
		}

		?>
		<div class="notice notice-error is-dismissible">
			<h3><?php esc_html_e( 'LiveHelpNow! Chat is not working yet!', 'lhnchat' ); ?></h3>
			<p>
				<?php esc_html_e( 'LiveHelpNow! Plugin is installed and active, but I\'s not working yet! You need to authenticate first.' ,'lhnchat' ); ?>
				<p>
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=livehelpnow-chat' ) ); ?>" class="button button-primary">
						<?php esc_html_e( 'Authenticate now', 'lhnchat' ); ?>
					</a>
				</p>
			</p>
		</div>
		<?php

	}
}
