<?php
/**
 * Authentication info when user is not logged in to LHN
 *
 * @package LHNChat
 */

?>

<p>
	<?php if ( 'lhn-chat' === get_post_type( get_the_ID() ) ) : ?>
		<?php esc_html_e( 'To edit form settings you need to login first.', 'lhnchat' ); ?><br />
	<?php endif;?>
	<?php esc_html_e( 'Please authenticate your LiveHelpNow account.', 'lhnchat' ); ?>
</p>
<a href="/?TB_inline&width=380&height=450&inlineId=lhn-authenticate" class="button button-primary thickbox">
	<?php esc_attr_e( 'Authenticate', 'lhnchat' ); ?>
</a>
