<?php
/**
 * WPUpper Security
 *
 * @package WP Security
 * @author  Victor Freitas
 * @version 1.0.1
 */
if ( ! function_exists( 'add_action' ) ) {
	exit(0);
}

WPUpper_Security_App::uses( 'wpupper-security', 'Controller' );

class WPUpper_Security_Core {

	/**
	 * Initialize
	 *
	 * @since 1.0
	 */
	public function __construct() {
		$this->_init_controllers();
	}

	private function _init_controllers() {
		if ( is_admin() ) {
			return;
		}

		new WPUpper_Security_Controller();
	}
}