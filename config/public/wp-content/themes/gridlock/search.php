<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

get_header(); ?>

		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<h1 class="page-title"><?php printf( 'Search Results for: %s', '<span>' . get_search_query() . '</span>' ); ?></h1>
			</header>


			<?php /* Start the Loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>
        <div class="row gridlock-row">
          <div class="article-container row">
            <?php get_template_part( 'content', get_post_format() ); ?>
          </div>
        </div>
			<?php endwhile; ?>


		<?php else : ?>

			<div class="post no-results not-found row gridlock-row">
        <div class="article-container row">
          <div class="article-description col-12">
            <h4 class="article-title">Nothing Found</h4>
          </div>
          <p>
            Sorry, but nothing matched your search criteria. Please try again with some different keywords.
          </p>
					<?php get_search_form(); ?>
        </div>
      </div>

		<?php endif; ?>

<?php get_footer(); ?>
