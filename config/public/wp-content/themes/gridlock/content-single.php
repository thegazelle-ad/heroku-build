<?php
/**
 * The default template for displaying content. Used for both single and index/archive/search.
 *
 * @package WordPress_Themes
 * @subpackage Gridlock
 */

 ?>


<div id="id-<?php the_ID(); ?>" <?php post_class(); ?> >
  <div class="row">
    <div class="col-12 divider">
      <h1><?php the_title(); ?></h1>
    </div>
  </div>
  <div class="row divider">
    <div class="col-6 col-sm-4 avatar">
      <?php echo get_avatar( get_the_author_meta( 'user_email' ), 80); ?>
    </div>
    <div class="col-6 col-sm-8">
      <h3 class="byline"><?php echo the_author_posts_link(); ?></h3>
    </div>
  </div>
  <div class="row divider">
    <?php the_content(); ?>
  </div>
</div>

