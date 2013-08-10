<?php
/**
 * The template for displaying Author Archive pages.
 *
 * Used to display archive-type pages for posts by an author.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

get_header(); ?>


		<?php if ( have_posts() ) : ?>

			<?php
				/* Queue the first post, that way we know
				 * what author we're dealing with (if that is the case).
				 *
				 * We reset this later so we can run the loop
				 * properly with a call to rewind_posts().
				 */
				the_post();
			?>

      <div class="jumbotron">
        <div class="row">
          <div class="col-6 col-sm-4 avatar">
            <?php echo get_avatar( get_the_author_meta( 'user_email' ), 300); ?>
          </div><!-- .author-avatar -->
          <div class="col-6 col-sm-8">
            <h1><?php the_author(); ?></h1>
          <?php if ( get_the_author_meta( 'description' ) ) : ?>
            <p><?php the_author_meta( 'description' ); ?></p>
          <?php endif; ?>
          </div><!-- .author-description	-->
        </div><!-- .author-info -->
      </div>

			<?php
				/* Since we called the_post() above, we need to
				 * rewind the loop back to the beginning that way
				 * we can run the loop properly, in full.
				 */
				rewind_posts();
			?>


			<?php /* Start the Loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>
        <div class="row gridlock-row">
          <div class="article-container row">
            <?php get_template_part( 'content' ); ?>
          </div>
        </div>
			<?php endwhile; ?>

		<?php else : ?>
			<?php get_template_part( 'content', 'none' ); ?>
		<?php endif; ?>

<?php get_footer(); ?>
