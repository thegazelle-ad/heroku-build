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
    <?php 
      $image_url = false;
      if (has_post_thumbnail()) {
        $image_url =  wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), array(300, 300), false, ''); 
        $image_url = $image_url[0];
      } else {
        $image_url = catch_image();
      }
      ?>
    <? if ($image_url) { ?>
      <div class="article-image col-6">
        <a href="<?php the_permalink(); ?>">
          <div class="image" style="background-image: url(<?php echo $image_url ?>)"></div>
        </a>
      </div>
      <div class="article-description col-6">
    <?php } else { ?>
      <div class="article-description col-12">
    <?php } ?>
        <a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'gridlock' ),  the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"<?php the_title(); ?> >
          <h4 class"article-title"> <?php the_title(); ?> </h4>
        </a>
        <?php echo the_author_posts_link(); ?>
        <p> <?php the_excerpt(); ?></p>
      </div>

  </div>
</div>
