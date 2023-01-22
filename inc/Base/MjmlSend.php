<?php 

/**
 * @package MJML Mail
 */

namespace Inc\Base;
//use \Inc\Base\BaseController;

class MjmlSend extends BaseController{
	public function register() {
		/*add_action( 'init', array( $this, 'post_published_notification' ) );*/
		add_action( 'publish_mjml-mail', array( $this, 'post_published_notification' ), 10, 2 );
		add_filter( 'single_template', array( $this, 'my_custom_template' ) );
		add_action( 'admin_action_rd_duplicate_post_as_draft', array( $this, 'rd_duplicate_post_as_draft' ) );
		add_filter( 'post_row_actions', array( $this, 'rd_duplicate_post_link' ), 10, 2 );
	}

	/* Filter the single_template with our custom function*/
	public function my_custom_template( $single ) {
	    global $post;
	    /* Checks for single template by post type */
	    if ( $post->post_type == 'mjml-mail' ) {
	        if ( file_exists( $this->plugin_path . 'includes/single-mjml-mail.php' ) ) {
	            return $this->plugin_path . 'includes/single-mjml-mail.php';
	        }
	    }
	    return $single;
	}


	/*public function publish_mjml() {
		add_action( 'publish_mjml-mail', 'post_published_notification', 10, 2 );
	}*/	
	
	public function post_published_notification( $post_id ){

		// receber e-mail em MJML
		$mjml = $this->build_mjml($post_id);
		$data = array( 'mjml' => $mjml );
		$json_body = json_encode($data);

		$username = '57acc65d-9f5b-4597-814d-ff40db814226';
		$password = '222ea3fa-76f1-4715-8538-fbb4faa9a66b';  
	  $basicauth = 'Basic ' . base64_encode(  $username . ':' . $password );

		$headers = array( 
			'Authorization' => $basicauth,
			'Content-type' => 'application/json',
		);

		$upload = array(
			'method' => 'POST',
			'timeout' => 30,
			'redirection' => 5,
			'httpversion' => '1.0',
			'blocking' => true,
			'headers' => $headers,
			'body' => $json_body,
			'cookies' => array()
		);

		$response = wp_remote_post('https://api.mjml.io/v1/render', $upload);
		$body = wp_remote_retrieve_body($response);
		$json_decode = json_decode($body);

		// guardar retorno HTML em um custom field
		if ( ! add_post_meta( $post_id, 'source', $json_decode->html, true ) ) {
			update_post_meta ( $post_id, 'source', $json_decode->html );
		}
	}

	private function build_mjml($post_id){  

	  $mjml = '';
		$mjml .= '
		<mjml>
			<mj-head>
				<mj-attributes>
					<mj-text color="#555" />
					<mj-section background-color="#fff" />
				</mj-attributes>

				<mj-style>
					@media(max-width:468px){
						.foot-space{
							padding:10px 5px !important;
						}
					}
				</mj-style>
			</mj-head>

			<mj-body width="'.get_field('container_width').'px" background-color="'.get_field('background_color', $post_id).'">';

	  		$conteudo_entire = parse_blocks(get_post_field('post_content', $post_id));

	  		foreach ($conteudo_entire as $key) {

	    		if($key['blockName'] != ''){

	    			if($key['blockName'] == 'acf/bloco-personalizado'){

	      				$block_data = $key['attrs']['data'];  

						$mjml .= '<mj-section padding-top="'.$block_data['padding_da_secao_padding_top'].'px'.'" padding-bottom="'.$block_data['padding_da_secao_padding_bottom'].'px'.'" padding-left="'.$block_data['padding_da_secao_padding_left'].'px'.'" padding-right="'.$block_data['padding_da_secao_padding_right'].'px'.'" background-color="'.$block_data['background_da_secao'].'" border-radius="'.$block_data['border_radius_da_secao_radius_top_left'].'px '.$block_data['border_radius_da_secao_radius_top_right'].'px '.$block_data['border_radius_da_secao_radius_bottom_right'].'px '.$block_data['border_radius_da_secao_radius_bottom_left'].'px">';

							$block_count = $block_data['itens_do_bloco'];

							for($i = 0; $i < $block_count; $i++){

								if($block_data['itens_do_bloco_'.$i.'_largura_da_coluna']){
									$largura_da_coluna = $block_data['itens_do_bloco_'.$i.'_largura_da_coluna'];
								}else{
									$largura_da_coluna = '';
								}

								$mjml .= '<mj-column vertical-align="'.$block_data['alinhamento_vertical_da_secao'].'" width="'.$largura_da_coluna.'%'.'">';

									// Images Items
									if($block_data['itens_do_bloco_'.$i.'_imagens_bloco']){ 

										for($j = 0; $j < $block_data['itens_do_bloco_'.$i.'_imagens_bloco'];$j++){
										
											$link = $block_data['itens_do_bloco_'.$i.'_imagens_bloco_'.$j.'_imagem_bloco_link_da_imagem'];

											$imagem = $block_data['itens_do_bloco_'.$i.'_imagens_bloco_'.$j.'_imagem_bloco_imagem'];

											$largura = $block_data['itens_do_bloco_'.$i.'_imagens_bloco_'.$j.'_imagem_bloco_largura_da_imagem'];

											$alinhamento = $block_data['itens_do_bloco_'.$i.'_imagens_bloco_'.$j.'_imagem_bloco_alinhamento_da_imagem'];
										

											$mjml .= '<mj-image href="'.$link.'" src="'.wp_get_attachment_url($imagem).'" alt="'.get_post_meta( $imagem, '_wp_attachment_image_alt', true ).'" border="none" width="'.$largura.'px'.'" padding="0" align="'.$alinhamento.'"></mj-image>';

										}

									}
									
									// Text Items
									if( $block_data['itens_do_bloco_'.$i.'_textos_bloco'] ){

										for($j = 0; $j < $block_data['itens_do_bloco_'.$i.'_textos_bloco']; $j++){

											$align = $block_data['itens_do_bloco_'.$i.'_textos_bloco_'.$j.'_texto_bloco_alinhamento'];

											$font_weight = $block_data['itens_do_bloco_'.$i.'_textos_bloco_'.$j.'_texto_bloco_peso_da_fonte'];

											$font_size = $block_data['itens_do_bloco_'.$i.'_textos_bloco_'.$j.'_texto_bloco_tamanho_da_fonte'];

											$line_height = $block_data['itens_do_bloco_'.$i.'_textos_bloco_'.$j.'_texto_bloco_altura_da_fonte'];

											$color = $block_data['itens_do_bloco_'.$i.'_textos_bloco_'.$j.'_texto_bloco_cor'];

											$transform = $block_data['itens_do_bloco_'.$i.'_textos_bloco_'.$j.'_texto_bloco_transformacao']; 

											$letter_spacing = $block_data['itens_do_bloco_'.$i.'_textos_bloco_'.$j.'_texto_bloco_espacamento_de_letra'];

											$conteudo = $block_data['itens_do_bloco_'.$i.'_textos_bloco_'.$j.'_texto_bloco_conteudo'];
									
											$mjml .= '<mj-text text-transform="'.$transform.'" letter-spacing="'.$letter_spacing.'px" align="'.$align.'" font-weight="'.$font_weight.'" padding="10px 10px 0px 0px" font-family="Arial, sans-serif" font-size="'.$font_size.'px'.'" line-height="'.$line_height.'px'.'" color="'.$color.'">'.$conteudo.'</mj-text>';
									
										}

									} 
									
									// Buttons Items
									if( $block_data['itens_do_bloco_'.$i.'_botoes_bloco'] ) {

										for($j = 0; $j < $block_data['itens_do_bloco_'.$i.'_botoes_bloco']; $j++){

											$background = $block_data['itens_do_bloco_'.$i.'_botoes_bloco_'.$j.'_botao_bloco_background_do_botao'];

											$color = $block_data['itens_do_bloco_'.$i.'_botoes_bloco_'.$j.'_botao_bloco_cor_do_texto'];

											$text = $block_data['itens_do_bloco_'.$i.'_botoes_bloco_'.$j.'_botao_bloco_texto_do_botao']; 

											$link = $block_data['itens_do_bloco_'.$i.'_botoes_bloco_'.$j.'_botao_bloco_link_do_botao'];

											$font_size = $block_data['itens_do_bloco_'.$i.'_botoes_bloco_'.$j.'_botao_bloco_tamanho_da_fonte']; 

											$align = $block_data['itens_do_bloco_'.$i.'_botoes_bloco_'.$j.'_botao_bloco_alinhamento_do_botao']; 

											$font_weight = $block_data['itens_do_bloco_'.$i.'_botoes_bloco_'.$j.'_botao_bloco_peso_da_fonte']; 
											 
											$radius = $block_data['itens_do_bloco_'.$i.'_botoes_bloco_'.$j.'_botao_bloco_curvatura_do_botao']; 

											$border_color = $block_data['itens_do_bloco_'.$i.'_botoes_bloco_'.$j.'_botao_bloco_cor_da_borda_do_botao'];  

											$padding = $block_data['itens_do_bloco_'.$i.'_botoes_bloco_'.$j.'_botao_bloco_padding_do_botao_top_bottom'].'px '.$block_data['itens_do_bloco_'.$i.'_botoes_bloco_'.$j.'_botao_bloco_padding_do_botao_left_right'].'px';
											
											$margin = $block_data['itens_do_bloco_'.$i.'_botoes_bloco_'.$j.'_botao_bloco_margin_do_botao_top_bottom'].'px '.$block_data['itens_do_bloco_'.$i.'_botoes_bloco_'.$j.'_botao_bloco_margin_do_botao_left_right'].'px'; 

											$mjml .= '<mj-button line-height="1" background-color="'.$background.'" color="'.$color.'" font-size="'.$font_size.'px'.'" align="'.$align.'" vertical-align="middle" border="1px solid '.$border_color.'" font-weight="'.$font_weight.'" href="'.$link.'" font-family="Arial, sans-serif" inner-padding="'.$padding.'" padding="'.$margin.'" border-radius="'.$radius.'px'.'">'.$text.'</mj-button>';

										}

									} 

								$mjml .= '</mj-column>';

							}
	
						$mjml .= '</mj-section>';

	  				} // Model Closing

	  			} // If block is not empty 

			} // Foreach End

			$mjml .= '</mj-body></mjml>';
		
		return $mjml;
		  
	} // Function Close



	/*
	 * Function creates post duplicate as a draft and redirects then to the edit post screen
	 */

	function rd_duplicate_post_as_draft(){
	  	global $wpdb;

		if (! ( isset( $_GET['post']) || isset( $_POST['post'])  || ( isset($_REQUEST['action']) && 'rd_duplicate_post_as_draft' == $_REQUEST['action'] ) ) ) {
			wp_die('No post to duplicate has been supplied!');
		}

	
		/*
		* Nonce verification
		*/

	  	if ( !isset( $_GET['duplicate_nonce'] ) || !wp_verify_nonce( $_GET['duplicate_nonce'], basename( __FILE__ ) ) )

		return;
		
	  	/*
	   	* get the original post id
	   	*/

	  	$post_id = (isset($_GET['post']) ? absint( $_GET['post'] ) : absint( $_POST['post'] ) );

	  	/*
	   	* and all the original post data then
	   	*/
		  
		   $post = get_post( $post_id );

	  	/*
	   	* if you don't want current user to be the new post author,
	   	* then change next couple of lines to this: $new_post_author = $post->post_author;
		*/
		   
	  	$current_user = wp_get_current_user();
	  	$new_post_author = $current_user->ID;
	
	  	/*
	   	* if post data exists, create the post duplicate
	   	*/

	  	if (isset( $post ) && $post != null) {

	    /*
	     * new post data array
	     */

	    $args = array(
	      'comment_status' => $post->comment_status,
	      'ping_status'    => $post->ping_status,
	      'post_author'    => $new_post_author,
	      'post_content'   => $post->post_content,
	      'post_excerpt'   => $post->post_excerpt,
	      'post_name'      => $post->post_name,
	      'post_parent'    => $post->post_parent,
	      'post_password'  => $post->post_password,
	      'post_status'    => 'draft',
	      'post_title'     => $post->post_title,
	      'post_type'      => $post->post_type,
	      'to_ping'        => $post->to_ping,
	      'menu_order'     => $post->menu_order
	    );

	
	    /*
	     * insert the post by wp_insert_post() function
	     */

	    $new_post_id = wp_insert_post( $args );

	    /*
	     * get all current post terms ad set them to the new post draft
	     */

	    $taxonomies = get_object_taxonomies($post->post_type); // returns array of taxonomy names for post type, ex array("category", "post_tag");

	    foreach ($taxonomies as $taxonomy) {
	      $post_terms = wp_get_object_terms($post_id, $taxonomy, array('fields' => 'slugs'));
	      wp_set_object_terms($new_post_id, $post_terms, $taxonomy, false);
	    }

	    /*
	     * duplicate all post meta just in two SQL queries
	     */

	    $post_meta_infos = $wpdb->get_results("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=$post_id");

	    if (count($post_meta_infos)!=0) {

			$sql_query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) ";

			foreach ($post_meta_infos as $meta_info) {
				$meta_key = $meta_info->meta_key;
				if( $meta_key == '_wp_old_slug' ) continue;
				$meta_value = addslashes($meta_info->meta_value);
				$sql_query_sel[]= "SELECT $new_post_id, '$meta_key', '$meta_value'";
			}

			$sql_query.= implode(" UNION ALL ", $sql_query_sel);
			$wpdb->query($sql_query);

	    }

	    /*
	     * finally, redirect to the edit post screen for the new draft
	     */

	    wp_redirect( admin_url( 'post.php?action=edit&post=' . $new_post_id ) );

	    //wp_redirect( admin_url( 'edit.php?post_type=email-marketing' ) ); // or edit.php?post_type={here your custom post type name}

	    //exit;

	  } else {

	    wp_die('Post creation failed, could not find original post: ' . $post_id);

	  }

	}

	/*
	 * Add the duplicate link to action list for post_row_actions
	 */

	function rd_duplicate_post_link( $actions, $post ) {

		if (current_user_can('edit_posts') && $post->post_type=='mjml-mail') {

			$actions['duplicate'] = '<a href="' . wp_nonce_url('admin.php?action=rd_duplicate_post_as_draft&post=' . $post->ID, basename(__FILE__), 'duplicate_nonce' ) . '" title="Duplicate this item" rel="permalink">Duplicate</a>';

		}

		return $actions;

	}


	/*
	// Update CSS within in Admin
	function admin_style() {
	  wp_enqueue_style('admin-styles', get_template_directory_uri().'/assets/css/admin-custom.css');
	}

	add_action('admin_enqueue_scripts', 'admin_style');
	*/

}

