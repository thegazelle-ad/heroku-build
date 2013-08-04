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
              <?php echo the_content(); ?>
              
            </div>
          </div>
        <?php endwhile; ?> 
<?php get_footer(); ?>
