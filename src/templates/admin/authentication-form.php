<?php
/**
 * Authentication form displayed in admin popup
 *
 * @package LHNChat
 */

?>

<div id="lhn-authenticate" style="display:none;">
	<div class="login login-action-login wp-core-ui interim-login">
		<div class="logo-section">
			<img
			src="<?php echo esc_attr( LHN_CHAT_PLUGIN_DIR_URL . 'assets/dist/images/lhn.png' ); ?>"
			alt="LiveHelpNow!"
			class="logo"
			/>
		</div>
		<form action="<?php echo esc_attr( admin_url( 'admin-post.php' ) ); ?>" method="POST">
			<input type="hidden" name="action" value="lhn_authenticate">
			<input type="hidden" name="referer" value="<?php echo esc_attr( basename( $_SERVER['REQUEST_URI'] ) ); ?>">
			<p>
				<label for="username"><?php esc_html_e( 'Username', 'lhnchat' ); ?><br>
					<input type="text" name="username" id="username" aria-describedby="login_error" class="input" value="" size="20" autocomplete="off">
				</label>
			</p>
			<p>
				<label for="password"><?php esc_html_e( 'Password', 'lhnchat' ); ?><br>
					<input type="password" name="password" id="password" aria-describedby="login_error" class="input" value="" size="20" autocomplete="off">
				</label>
			</p>
			<p class="submit">
				<input
				type="submit"
				class="button button-primary button-large"
				value="<?php esc_attr_e( 'Authenticate', 'lhnchat' ); ?>"
				>
			</p>
		</form>
	</div>
</div>
