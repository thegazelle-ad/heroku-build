<?php
/**
 * The main template file.
 *
 * @package WordPress_Themes
 * @subpackage Gridlock
 */

get_header(); ?>

    <?php
      $meta_query = new WP_Query(array('posts_per_page' => 10 ));
      $old_row = 0;
      while ( $meta_query->have_posts() ) : $meta_query->the_post(); ?>
          <div class='row gridlock-row'>
          <div class="article-container col-12" >
          <?php get_template_part( 'content' ); 
          // closing the column tag
          ?>
          </div>
          </div>
    <?php endwhile; 
        ?>
<?php get_footer(); ?>
