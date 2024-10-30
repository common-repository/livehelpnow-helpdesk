<?php
/**
 * Abstract class to keep WordPress integration methods
 *
 * @package LHNChat
 */

namespace LHNChat\Abstracts;

use \LHNChat\HooksActivator;

/**
 * WPIntegrator class
 */
abstract class WPIntegrator implements \LHNChat\Interfaces\Hookable {

	/**
	 * Register all hooks applied in given class
	 *
	 * @return void
	 */
	public function register_hooks() {
		$hooks_activator = new HooksActivator();
		$hooks_activator->add_doc_hooks( $this );
	}

}
