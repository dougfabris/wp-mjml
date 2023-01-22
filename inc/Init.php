<?php 

/**
 * @package MJML Mail
 */

namespace Inc;

final class Init {
	/**
	 * Store all the classes inside an array
	 * @return array Full list of classes
	*/ 

	public static function get_services() {
		return [
			Base\Enqueue::class,
			Base\CustomPostType::class,
			Base\AdvancedCustomFields::class,
			Base\MjmlSend::class
		];
	}

	/**
	 * Loop through the classes, initialize them, and call the register() method if it exists
	 * @return 
	 */

	public static function register_services() {
		foreach ( self::get_services() as $class) {
			$service = self::instantiate( $class );
			if ( method_exists( $service, 'register' ) ) {
				$service->register();
			}
		}
	}

	/**
	 * Initialize the class
	 * @param class $class class from the services array
	 * @return class instance new instance of the class
	 */

	private static function instantiate ( $class ) {
		$service = new $class();
		return $service;
	}
}

/*use Inc\Activate;
use Inc\Deactivate;
use Inc\Admin\AdminPages;

if ( !class_exists( 'MjmlMail' ) ){
	class MjmlMail {	
		public $plugin;

		function __construct() {
			$this->plugin = plugin_basename( __FILE__ );
		}

		function register() {
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );
		}

		protected function create_post_type() {
			add_action( 'init', array( $this, 'custom_post_type' ) );
		}

		function custom_post_type() {
			register_post_type( 'book', ['public' => true, 'label' => 'Books'] );
		}

		function activate() {
			//require_once plugin_dir_path(__FILE__) . 'inc/mjmlmail-activate.php';
			Activate::activate();
		}
	}

	$mjmlmail = new MjmlMail();
	$mjmlmail->register();
	// $mjmlmail->create_post_type();

	// activation
	//require_once plugin_dir_path(__FILE__) . 'inc/mjmlmail-activate.php';
	register_activation_hook( __FILE__, array( $mjmlmail, 'activate' ) );

	// deactivation
	//require_once plugin_dir_path(__FILE__) . 'inc/mjmlmail-deactivate.php';
	register_deactivation_hook( __FILE__, array( 'Deactivate', 'deactivate' ) );

}*/
