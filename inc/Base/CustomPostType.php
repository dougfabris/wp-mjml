<?php 

/**
 * @package MJML Mail
 */

namespace Inc\Base;
//use \Inc\Base\BaseController;

class CustomPostType{

	public function register() {
		add_action( 'init', array( $this, 'create_post_type' ) );
	}

	private function flush_rewrite_rules() {
	    create_post_type();
	    flush_rewrite_rules();
	}

	public function create_post_type() {

		$labels = array(
	        'name' => _x('MJML Mail', 'post type general name'),
	        'singular_name' => _x('MJML Mail', 'post type singular name'),
	        'add_new' => _x('Adicionar MJML Mail', 'mjml-mail'),
	        'add_new_item' => __('Adicionar MJML Mail'),
	        'edit_item' => __('Editar MJML Mail'),
	        'new_item' => __('Novo MJML Mail'),
	        'view_item' => __('Ver MJML Mail'),
	        'search_items' => __('Procurar MJML Mail'),
	        'not_found' =>  __('Nada encontrado'),
	        'not_found_in_trash' => __('Nada encontrado no lixo'),
	        'parent_item_colon' => ''

	    );

	    $args = array(
	        'labels' => $labels,
	        'public' => true,
	        'publicly_queryable' => true,
	        'show_ui' => true,
	        'query_var' => true,
	        'show_in_rest' => true,
	        'rewrite' => array(
	            'slug' => 'mjml-mail',
	            'with_front' => false
	        ),
	        'has_archive' => true,
	        'menu_icon' => 'dashicons-email-alt2',
	        'capability_type' => 'post',
	        'hierarchical' => false,
	        'menu_position' => 6,
	        'taxonomies'    => array( 'category' ),
	        'supports' => array('title', 'editor')

	    );

	    register_post_type('mjml-mail',$args);
	    flush_rewrite_rules();
	}

}