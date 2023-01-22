<?php 

  if( have_posts() ){
    while( have_posts() ){
      the_post();  
      $content = get_post_meta(get_the_ID(), 'source');
      echo $content[0];
    }
  }

?>