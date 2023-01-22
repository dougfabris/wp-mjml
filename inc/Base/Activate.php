<?php 

/**
 * @package MJML Mail
 */

namespace Inc\Base;

class Activate {
	public static function activate() {
		flush_rewrite_rules();

		if( get_option( 'mjml_mail' ) ) {
			return;
		}

		$default = array();
		update_option( 'mjml_mail', $default );
	}
}
