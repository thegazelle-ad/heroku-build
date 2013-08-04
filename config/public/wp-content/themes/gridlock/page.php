<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package WordPress_Themes
 * @subpackage Gridlock
 */

get_header(); ?>

  <?php while ( have_posts() ) : the_post(); ?>
    <div class="row gridlock-row">
      <div class="article-container row">
        <?php get_template_part( 'content' ); ?>
        <?php comments_template( '', true ); ?>
      </div>
    </div>
  <?php endwhile; // end of the loop. ?>

<?php get_footer(); ?>
