<?php 

/**
 * @package WP MJML
 **/

/*
Plugin Name: WP MJML
Plugin URI: #
Description: This is the unofficial MJML wordpress plugin to create full responsive and incredible e-mails from your wordpress installation.
Version: 1.1.1
Author: Douglas Fabris
Author URI: https://github.com/dougfabris
License: GPLv2 or later
Text Domain: wpmjml
*/


/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
Copyright 2005-2015 Automattic, Inc.
*/

defined( 'ABSPATH' ) or die( 'Hey, you can\t access this file, you silly human!' );

if ( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' ) ) {
	require_once dirname( __FILE__ ) . '/vendor/autoload.php';
}

/**
 * The code that runs during plugin activatio
 */

function activate_mjmlmail() {
	Inc\Base\Activate::activate();
}

register_activation_hook( __FILE__, 'activate_mjmlmail' );

/**
 * The code that runs during plugin deactivation
 */

function deactivate_mjmlmail() {
	Inc\Base\Deactivate::deactivate();
}

register_deactivation_hook( __FILE__, 'deactivate_mjmlmail' );

if ( class_exists( 'Inc\\Init' ) ) {
	Inc\Init::register_services();
}

// Update Checker
require dirname( __FILE__ ) .'/plugin-update-checker/plugin-update-checker.php';

//require ( $this->plugin_path .'plugin-update-checker/plugin-update-checker.php' );
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://parnity.co/marketing/mjmlmail/info.json',
	__FILE__, //Full path to the main plugin file or functions.php.
	'mjmlmail'
);
