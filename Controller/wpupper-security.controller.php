<?php
/**
 * WPUpper Security
 *
 * @package WPUpper Security
 * @subpackage Controller
 * @author Victor Freitas
 * @since 1.1.0
 */

if ( ! function_exists( 'add_action' ) ) {
	exit(0);
}

class WPUpper_Security_Controller {

	/**
	 * Constructor initialize actions and filters
	 *
	 * @since 1.0
	 * @param Null
	 * @return void
	 */
	public function __construct() {
		add_filter( 'script_loader_src', array( &$this, 'check_version' ) );
		add_filter( 'style_loader_src', array( &$this, 'check_version' ) );
		add_action( 'after_setup_theme', array( &$this, 'remove_headers' ) );
	}

	public function remove_headers() {
		$this->remove_header_info();
		$this->remove_generator();
	}

	/**
	 * Generate filemtime by last modification file
	 *
	 * @since 1.0
	 * @param String $uri
	 * @return Integer
	 */
	private function _filemtime_generator( $uri ) {
		$headers = @get_headers( $uri, 1 );

		if ( isset( $headers['Last-Modified'] ) ) {
			return strtotime( $headers['Last-Modified'] );
		}

		if ( isset( $headers['Expire'] ) ) {
			return strtotime( $headers['Expire'] );
		}

		return date( 'dnY' ) * 2048;
    }

	/**
	 * Remove generator tags
	 *
	 * @since 1.0
	 * @param Null
	 * @return void
	 */
    public function remove_generator() {
		$types = array(
			'html',
			'xhtml',
			'atom',
			'rss2',
			'rdf',
			'comment',
			'export',
		);

		$actions = array(
			'rss2_head',
			'commentsrss2_head',
			'rss_head',
			'rdf_header',
			'atom_head',
			'comments_atom_head',
			'opml_head',
			'app_head',
		);

		$types   = apply_filters( WPUpper_Security_App::SLUG . '-generator-types', $types );
		$actions = apply_filters( WPUpper_Security_App::SLUG . '-generator-actions', $actions );

		foreach ( $actions as $action ) {
			remove_action( $action, 'the_generator' );
		}

		foreach ( $types as $key => $val ) {
			add_filter( "get_the_generator_{$val}", '__return_false' );
		}
    }

	/**
	 * Remover header informations
	 *
	 * @since 1.0
	 * @param Null
	 * @return void
	 */
	public function remove_header_info() {
		$headers = array(
			'adjacent_posts_rel_link_wp_head' => 10,
			'rest_output_link_wp_head'        => 10,
			'parent_post_rel_link'            => 10,
			'wp_shortlink_wp_head'            => 10,
			'start_post_rel_link'             => 10,
			'wlwmanifest_link'                => 10,
			'index_rel_link'                  => 10,
			'wp_generator'                    => 10,
			'rsd_link'                        => 10,
			'print_emoji_detection_script'    => 7,
			'feed_links_extra'                => 3,
			'feed_links'                      => 2,
		);

		$headers = apply_filters( WPUpper_Security_App::SLUG . '-headers', $headers );

		remove_action( 'wp_print_styles', 'print_emoji_styles' );

		foreach ( $headers as $function_name => $priority ) {
	    	remove_action( 'wp_head', $function_name, $priority );
		}
	}

	/**
	 * Check the version files css and js
	 *
	 * @since 1.0
	 * @param String $src
	 * @return String
	 */
	public function check_version( $src ) {
		global $wp_version;

		$src = esc_url( $src );

		if ( ! $src ) {
			return $src;
		}

		$file = explode( 'ver=', $src );

		if ( ! isset( $file[1] ) ) {
			return $src;
		}

		if ( $wp_version == $file[1] ) {
			return $file[0] . 'ver=' . $this->_change_version( $src, $file[0] );
		}

		return $src;
	}

	/**
	 * Change version in files css and js to filemtime by last modification
	 *
	 * @since 1.0
	 * @param String $src
	 * @param String $file
	 * @return String
	 */
	private function _change_version( $src, $file ) {
		$real_path = realpath( ABSPATH );
		$path_fix  = str_replace( '\\', '/', $real_path );
		$parse_url = ( object ) parse_url( $src );
		$file_path = "{$path_fix}{$parse_url->path}";

		if ( file_exists( $file_path ) ) {
			$date = date( 'dmyHis', filemtime( $file_path ) );
			return apply_filters( WPUpper_Security_App::SLUG . '-filemtime', $date );
		}

		return $this->_filemtime_generator( $file );
	}
}