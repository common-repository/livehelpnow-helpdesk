<?php
/**
 * Plugin functions
 *
 * @package LHNChat
 * @since 0.1.0
 */

namespace LHNChat;

function is_lhn_user_authenticated() {
  $auth = new API\Authentication();
  return $auth->is_user_authenticated();
}
