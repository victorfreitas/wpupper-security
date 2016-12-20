<?php
/*
 * Plugin Name: WPUpper Security
 * Plugin URI:  https://github.com/victorfreitas
 * Version:     2.1
 * Author:      Victor Freitas
 * Author URI:  https://github.com/victorfreitas
 * License:     GPL2
 * Text Domain: jogar-mais-wp-security
 * Description: Remove automatically the version of WordPress HTML content anywhere on the site.
 #═════════════════════════════════════════════════════════════════════════════════════#
 ║ This program is free software; you can redistribute it and/or                       ║
 ║ modify it under the terms of the GNU General Public License                         ║
 ║ as published by the Free Software Foundation; either version 2                      ║
 ║ of the License, or (at your option) any later version.                              ║
 ║                                                                                     ║
 ║ This program is distributed in the hope that it will be useful,                     ║
 ║ but WITHOUT ANY WARRANTY; without even the implied warranty of                      ║
 ║ MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the                       ║
 ║ GNU General Public License for more details.                                        ║
 ║                                                                                     ║
 ║ You should have received a copy of the GNU General Public License                   ║
 ║ along with this program; if not, write to the Free Software                         ║
 ║ Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.     ║
 ║                                                                                     ║
 ║    By Victor Freitas (victorfreitasdev@gmail.com)                                   ║
 ║                                                                                     ║
 ║ Copyright 2015-2016 WPUpper Security                                                ║
 #═════════════════════════════════════════════════════════════════════════════════════#
 */
if ( ! function_exists( 'add_action' ) ) {
	exit(0);
}

class WPUpper_Security_App {

	const SLUG = 'wpupper-security';

	/**
	 * Initialize
	 *
	 * @since 2.0
	 * @return Void
	 */
	public static function uses( $class_name, $location ) {
		$locations = array( 'Controller', 'View', );
		$extension = 'php';

		if ( in_array( $location, $locations ) ) {
			$extension = strtolower( $location ) . ".{$extension}";
		}

		require_once( "{$location}/{$class_name}.{$extension}" );
	}
}

WPUpper_Security_App::uses( 'wpupper-security-core', 'Config' );

new WPUpper_Security_Core();