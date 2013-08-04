<?php
/**
 * The main template file.
 *
 * @package WordPress_Themes
 * @subpackage Gridlock
 */

get_header(); ?>

    <?php
      $meta_query = new WP_Query(array('showposts' => 10, 'orderby' => 'meta_value', 'meta_key' => 'gridlock', 'order' => 'ASC' ));
      $old_row = 0;
      while ( $meta_query->have_posts() ) : $meta_query->the_post(); ?>
          <div class='row gridlock-row'>
            <div class="article-container col-12">
              <?php echo the_ID(); ?>
              <?php echo the_title(); ?>
              <?php get_template_part( 'content' ); ?>
              <?php echo get_post_meta( get_the_ID(), "gridlock", true); ?>
              <?php get_template_part( 'content', 'grid' ); ?>
              
            </div>
          </div>
        <?php endwhile; ?> 
<?php get_footer(); ?>
