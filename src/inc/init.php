<?php
/**
 * Plugin init files
 *
 * @package LHNChat
 * @since 0.1.0
 */

namespace LHNChat;

require_once LHN_CHAT_PLUGIN_DIR_PATH . 'src/inc/functions.php';

$plugin = new Plugin();

register_activation_hook( LHN_CHAT_PLUGIN_FILE, [
	$plugin,
	'on_plugin_activation'
] );

register_deactivation_hook( LHN_CHAT_PLUGIN_FILE, [
	$plugin,
	'on_plugin_deactivation'
] );

$plugin->register_hooks();
