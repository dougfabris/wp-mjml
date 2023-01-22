<?php

/**
 * Modelo Bloco Personalizado Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'bloco-personalizado' . $block['id'];
if( !empty($block['anchor']) ) {
  $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'bloco-personalizado';
if( !empty($block['className']) ) {
  $className .= ' ' . $block['className'];
}

if( !empty($block['align']) ) {
  $className .= ' align' . $block['align'];
}

// Default MJML Mail Config
$body_background = get_field('background_color', $post_id);
$container_width = get_field('container_width', $post_id);

// Load values and assing defaults.
$padding_secao = get_field('padding_da_secao');
$background_secao = get_field('background_da_secao');
$border_radius_secao = get_field('border_radius_da_secao');
$alinhamento_vert_secao = get_field('alinhamento_vertical_da_secao');

switch($alinhamento_vert_secao){
  case "bottom":
    $align = "flex-end";
    break;
  case "middle":
    $align = "center";
    break;
  default:
    $align = "flex-start";
}

?>

<style>
  .block-editor-block-list__layout{
    background: <?php echo $body_background;?>;
  }
  .editor-styles-wrapper .block-editor-block-list__block{
    margin:0 auto;
  }
</style>

<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>" style="width: <?php echo $container_width.'px';?>;margin:0 auto;align-items: <?php echo $align;?>;background: <?php echo $background_secao;?>;display:flex;padding-top:<?php echo $padding_secao['padding_top'].'px';?>;padding-bottom: <?php echo $padding_secao['padding_bottom'].'px';?>;padding-left:<?php echo $padding_secao['padding_left'].'px';?>;padding-right:<?php echo $padding_secao['padding_right'].'px';?>;border-radius:<?php echo $border_radius_secao['radius_top_left'].'px '.$border_radius_secao['radius_top_right'].'px '.$border_radius_secao['radius_bottom_right'].'px '.$border_radius_secao['radius_bottom_left'].'px';?>">

    <?php 

      if(get_field('itens_do_bloco')){
        while(has_sub_field('itens_do_bloco')) {

          $largura_secao = get_sub_field('largura_da_coluna');
          $botoes_secao = get_sub_field('botoes_bloco');
          $imagens_secao = get_sub_field('imagens_bloco');
          $textos_secao = get_sub_field('textos_bloco');

          if ($largura_secao) {
            $largura_da_coluna = get_sub_field('largura_da_coluna').'%';
          } else{
            $largura_da_coluna = 'flex: 1 1 0';
          }

    ?>

    <div style="width: <?php echo $largura_da_coluna;?>">

        <?php 
        
          if ($imagens_secao) {
            foreach($imagens_secao as $item) {
              $item = $item['imagem_bloco'];
                
        ?>

        <div style="text-align:<?php echo $item['alinhamento_da_imagem'];?>">

            <img style="width: <?php echo $item['largura_da_imagem'].'px';?>;" src="<?php echo $item['imagem']['url']; ?>" alt="<?php echo $item['imagem']['alt'];?>" />

        </div>

        <?php } } else { ?>

          <img src="https://i.ibb.co/yYSFVNc/placeholder2.png" alt="Placeholder" />

        <?php 

          }
      
          if($textos_secao){
            foreach($textos_secao as $item){ 
                $item = $item['texto_bloco'];
                    
        ?>

            <span style="padding:10px 10px 0px 0px;display:block;font-family: 'Arial';font-size: <?php echo $item['tamanho_da_fonte'].'px';?>;line-height: <?php echo $item['altura_da_fonte'].'px';?>;color: <?php echo $item['cor'];?>;text-align: <?php echo $item['alinhamento'];?>;font-weight: <?php echo $item['peso_da_fonte'];?>;text-transform: <?php echo $item['transformacao'];?>;letter-spacing: <?php echo $item['espacamento_de_letra'].'px';?>;"><?php echo $item['conteudo'];?></span>

        <?php } 
    
        } 
        
          if($botoes_secao){
            foreach($botoes_secao as $item){
              $item = $item['botao_bloco'];
            
        ?>

        <div style="text-align:<?php echo $item['alinhamento_do_botao'];?>">

          <a style="display:inline-block;line-height:1;font-family: 'Arial';text-decoration: none;padding: <?php echo $item['padding_do_botao_top_bottom'].'px '.$item['padding_do_botao_left_right'].'px';?>;margin:<?php echo $item['margin_do_botao_top_bottom'].'px '.$item['margin_do_botao_left_right'].'px';?>;font-size: <?php echo $item['tamanho_da_fonte'].'px';?>;color: <?php echo $item['cor_do_texto'];?>;text-align: <?php echo $item['alinhamento_do_botao'];?>;font-weight: <?php echo $item['peso_da_fonte'];?>;border-radius: <?php echo $item['curvatura_do_botao'].'px';?>;background: <?php echo $item['background_do_botao'];?>;border: 1px solid <?php echo $item['cor_da_borda_do_botao'];?>;" href="<?php echo $item['link_do_botao'];?>" class="botao_bloco"><?php echo $item['texto_do_botao'];?></a>

        </div>

        <?php } } ?>

    </div>

    <?php }} else{?>

    <div style="background-color: #fff;width: 100%;text-align: center;">

        <p style="font-family: 'Arial';">Insira pelo menos uma coluna</p>

    </div>

    <?php } ?>
</div>
