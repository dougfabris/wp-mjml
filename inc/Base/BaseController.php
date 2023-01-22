<?php 

/**
 * @package MJML Mail
 */

namespace Inc\Base;

class BaseController{

	public $plugin_path;
	public $plugin_url;
	public $plugin;

	// This will works in php < 5.3
	public function __construct() {
		$this->plugin_path = plugin_dir_path( realpath(dirname( __FILE__ ).'/..' ) );
		$this->plugin_url = plugin_dir_url( realpath(dirname( __FILE__ ).'/..' ) );
		$this->plugin = plugin_basename( realpath(dirname( __FILE__ ).'/../..' ) ). '/mjmlmail.php';
	}


	// This will works in php >= 7.0

	/*public function __construct() {
		$this->plugin_path = plugin_dir_path( dirname( __FILE__, 2 ) );
		$this->plugin_url = plugin_dir_url( dirname( __FILE__, 2 ) );
		$this->plugin = plugin_basename( dirname( __FILE__, 3 ) ) . '/mjmlmail.php';
	}*/


	public function activated( string $key ){
		$option = get_option( 'mjml_mail' );
		return isset( $option[ $key ] ) ? $option[ $key ] : false;
	}

}