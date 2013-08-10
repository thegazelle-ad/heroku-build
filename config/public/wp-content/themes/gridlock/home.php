<?php
/**
 * The main template file.
 *
 * @package WordPress_Themes
 * @subpackage Gridlock
 */

get_header(); ?>

  <div id="home">
    <div class='row gridlock-row'>
    <?php
      $finished = false;
      $max_row = get_option("gridlock_rows");
      if ($max_row == 0) {
        $max_row = 999;
      }
      $row_count = 0;
      $meta_query = new WP_Query(array_merge(get_option("gridlock_query"), array('orderby' => 'meta_value', 'meta_key' => 'gridlock', 'order' => 'ASC', "post_status" => "publish" )));
      while ( $meta_query->have_posts() ) : $meta_query->the_post(); 
            // get the grid positioning
            // Example input is 32.12, meaning row 32, index starting at 1, spanning 2
            $gridlock =  explode(".", get_post_meta( get_the_ID(), "gridlock", true)); 
            $index = $gridlock[1][0]; 
            $span = $gridlock[1][1];
          ?>
          <div class="article-container col-12 
          <?php
            // opening the span tag
            switch ($span) {
            case "1":
              echo "col-sm-4 article-small";
              break;
            case "2":
              echo "col-sm-8 article-medium";
              break;
            case "3":
              echo "col-sm12 article-large";
              break;
            }
          ?>
          ">
          <?php get_template_part( 'content', 'grid' ); 
          // closing the column tag
          ?>
          </div>
          <?php
            //get_template_part( 'content', get_post_format() );
            $finished = false;
            if ($index + $span == 4) {
              // closing the row if the post finishes it
              echo "</div>";
              // open the next post
              echo "<div class='row gridlock-row'>";
              $finished = true;
              if (++$row_count == $max_row) {
                break;
              }
            }
        endwhile; ?> 
      </div>  
      <?php if (!$finished) { // workaround for hiding the last div ?>
        <div class="gridlock-row"></div>
      <?php } ?>
  </div>
      
<?php get_footer(); ?>
