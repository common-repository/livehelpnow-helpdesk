<?php
/**
 * Interface for objects that can be used with hooks activator

 * @package LHNChat
 */

namespace LHNChat\Interfaces;

/**
 * Hookable interface
 */
interface Hookable {

	/**
	 * Register all hooks applied in given class
	 *
	 * @return void
	 */
	public function register_hooks();

}
