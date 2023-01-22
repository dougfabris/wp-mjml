<?php 

/**
 * @package MJML Mail
 */

namespace Inc\Base;
//use \Inc\Base\BaseController;

class AdvancedCustomFields extends BaseController {
	public function register() {
		// Define path and URL to the ACF plugin.
		define( 'MY_ACF_PATH', $this->plugin_path . 'includes/acf/' );
		define( 'MY_ACF_URL', $this->plugin_url . 'includes/acf/' );
		define( 'BLOCK_MODELS', $this->plugin_path . 'includes/template-parts/blocks/' );

		// Include the ACF plugin.
		include( MY_ACF_PATH . 'acf.php' );

		// Customize the url setting to fix incorrect asset URLs.
		add_filter('acf/settings/url', function( $url ){
	    	return $url;
		});


		add_action( 'acf/init', array( $this, 'register_acf_block_type' ) );

		//(Optional) Hide the ACF admin menu item.
		// add_filter('acf/settings/show_admin', function( $show_admin ) {
		//     return false;
		// });

		//ALLOW CUSTOM BLOCKS IN SPECIFIC POST TYPE
		add_filter( 'allowed_block_types', function( $allowed_blocks, $post ){
			if( $post->post_type === 'mjml-mail' ) {
		      $allowed_blocks = array(
		        'acf/bloco-personalizado' 
		      );
		  }

		  return $allowed_blocks;
		}, 10, 2 );

		add_filter('acf/settings/save_json', function() {
			return $this->plugin_path . 'includes/acf-json';
		});

		add_filter('acf/settings/load_json', function($paths) {
			$paths = array( $this->plugin_path . 'includes/acf-json' );
			$paths[] = $this->plugin_path . 'includes/acf-json';
			return $paths;
		});

		// add_action( 'admin_init', array( $this, 'jp_sync_acf_fields' ) );
	}	

	// REGISTER ACF BLOCK TYPES
	public function register_acf_block_type() {
		if( function_exists('acf_register_block_type') ) {

			// register de custom block.
			acf_register_block_type(
				array(
					'name'              => 'bloco-personalizado',
					'title'             => __('Bloco Personalizado'),
					'description'       => __('Modelo de bloco com imagem, titulo e conteúdo em quantas colunas desejar'),
					'render_template'   => BLOCK_MODELS.'bloco-personalizado.php',
					'category'          => 'formatting',
					'icon'              => 'feedback',
					'mode'              => 'preview',
					'keywords'          => array( 'bloco-personalizado', 'modelo-bloco-personalizado' ),
				)
			);
		}
	}

	/**
	 * Function that will update ACF fields via JSON file update
	 */

	function jp_sync_acf_fields() {

		// vars
		$groups = acf_get_field_groups();
		$sync 	= array();

		// bail early if no field groups
		if( empty( $groups ) )
			return;
		// find JSON field groups which have not yet been imported

		foreach( $groups as $group ) {
			// vars
			$local 		= acf_maybe_get( $group, 'local', false );
			$modified 	= acf_maybe_get( $group, 'modified', 0 );
			$private 	= acf_maybe_get( $group, 'private', false );

			// ignore DB / PHP / private field groups
			if( $local !== 'json' || $private ) {
				// do nothing
			} elseif( ! $group[ 'ID' ] ) {
				$sync[ $group[ 'key' ] ] = $group;
			} elseif( $modified && $modified > get_post_modified_time( 'U', true, $group[ 'ID' ], true ) ) {
				$sync[ $group[ 'key' ] ]  = $group;
			}
		}

		// bail if no sync needed
		if( empty( $sync ) ) return;

		if( ! empty( $sync ) ) { 
		//if( ! empty( $keys ) ) {
			// vars
			$new_ids = array();
			foreach( $sync as $key => $v ) { //foreach( $keys as $key ) {
				// append fields
				if( acf_have_local_fields( $key ) ) {
					$sync[ $key ][ 'fields' ] = acf_get_local_fields( $key );
				}

				// import
				$field_group = acf_import_field_group( $sync[ $key ] );
			}
		}

	}

	public function my_acf_json_save_point( $path ) {
		// update path
		$path = MY_ACF_PATH . 'includes/acf-json';
		// return
		return $path;
	}

	public function my_acf_json_load_point( $paths ) {
		// remove original path (optional)
		unset($paths[0]);
		// append path
		$paths[] = MY_ACF_PATH . 'includes/acf-json';
		echo $paths;
		// return
		return $paths; 
	}
}
