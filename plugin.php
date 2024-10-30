<?php
/**
 * Plugin Name:     Livehelpnow Help Desk
 * Version:         0.2.0
 * Plugin URI:      https://wordpress.org/plugins/livehelpnow-helpdesk/
 * Description:     Include LiveHelpNow chat system on your WordPress website.
 * Author:          In`saneLab
 * Author URI:      https://insanelab.com
 * Text Domain:     lhnchat
 * Domain Path:     /languages
 * License:         GPL-2.0+
 * License URI:     http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * @package         LHNChat
 */

namespace LHNChat;

/**
 * Change to unique names
 */
define( 'LHN_CHAT_PLUGIN_FILE', __FILE__ );
define( 'LHN_CHAT_PLUGIN_DIR_PATH', plugin_dir_path( __FILE__ ) );
define( 'LHN_CHAT_PLUGIN_DIR_URL',  plugin_dir_url( __FILE__ ) );

/**
 * Require composer autoload
 */
require_once LHN_CHAT_PLUGIN_DIR_PATH . 'vendor/autoload.php';

/**
 * Require plugin init file
 */
require_once LHN_CHAT_PLUGIN_DIR_PATH . 'src/inc/init.php';
