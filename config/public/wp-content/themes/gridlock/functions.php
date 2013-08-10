<?php
/**
 * Gridlock functions and definitions.
 *
 * Sets up the theme and provides some helper functions, which are used
 * in the theme as custom template tags. Others are attached to action and
 * filter hooks in WordPress to change core functionality.
 *
 * @package WordPress_Themes
 * @subpackage Gridlock
 */

/**
 * Sets up the content width value based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) )
  $content_width = 1200;

/**
 * Sets up theme defaults and registers the various WordPress features that
 * Twenty Twelve supports.
 *
 * @uses load_theme_textdomain() For translation/localization support.
 * @uses add_editor_style() To add a Visual Editor stylesheet.
 * @uses add_theme_support() To add support for post thumbnails, automatic feed links,
 * 	custom background, and post formats.
 * @uses set_post_thumbnail_size() To set a custom post thumbnail size.
 *
 */
function gridlock_setup() {
  // This theme styles the visual editor with editor-style.css to match the theme style.
  add_editor_style("css/editor-style.css");

  // Adds RSS feed links to <head> for posts and comments.
  add_theme_support( 'automatic-feed-links' );

  // This theme supports a variety of post formats.
  add_theme_support( 'post-formats', array( 'gallery', 'video', 'image', 'link') );

  /*
   * This theme supports custom background color and image, and here
   * we also set up the default background color.
   */
  add_theme_support( 'custom-background', array(
    'default-color' => 'e6e6e6',
  ) );

  // This theme uses a custom image size for featured images, displayed on "standard" posts.
  add_theme_support( 'post-thumbnails' );
  set_post_thumbnail_size( 400, 9999 ); // Unlimited height, soft crop

  // Add the option
  add_option( "gridlock_all", true);
  add_option( "gridlock_future", true);
  add_option( "gridlock_query", array("posts_per_page" => 10));
  add_option( "gridlock_grid_query", array("posts_per_page" => 10));
  add_option( "gridlock_rows", 0);
}
add_action( 'after_setup_theme', 'gridlock_setup' );
add_theme_support( 'post-thumbnails' ); 



// the gridster drag and drop grid
if ( is_admin() ) {
  function gridlock_settings_text() {
    echo "";
  }
  function sanitize_query($query) {
    $res = array();
    $query = str_replace('"', "", str_replace("'", "", str_replace(" ", "", $query)));
    $query = explode(",", $query);
    foreach ($query as $val) {
      $vals = explode("=>", $val);
      $res[$vals[0]] = $vals[1];
    }
    return $res;
  }
  function gridlock_all_input() {
    $options = get_option('gridlock_all');
    echo ("<input id='gridlock_all' name='gridlock_all' ".checked(1, get_option('gridlock_all'), false)."' type='checkbox' value='1' />");
    echo ("<br/><small class='text-muted'> Whether to show posts that have yet to be placed in the grid. <br />Note: defaulted articles always appear above gridded articles</small>");
  }
  function gridlock_query_input() {
    $options = get_option('gridlock_query');
    $res = '';
    foreach ($options as $key => $val) {
      $res .= $key . " => " . $val . ", ";
    }
    $res = substr($res, 0, -2);
    echo "<input id='gridlock_query' name='gridlock_query' size='40' type='text' value='{$res}' />";
    echo "<br /><small class='text-muted'>If capping by rows, the number of posts queried should exceed the number of rows * 3 </small>";
  }
  function gridlock_rows_input() {
    $options = get_option('gridlock_rows');
    echo "<input id='gridlock_rows' name='gridlock_rows' size='40' type='text' value='{$options}' />";
    echo "<br /><small class='text-muted'>(0 for unlimited - return all posts queried) </small>";
  }
  function gridlock_future_input() {
    $options = get_option('gridlock_all');
    echo ("<input id='gridlock_all' name='gridlock_future' ".checked(1, get_option('gridlock_future'), false)."' type='checkbox' value='1' />");
    echo ("<br/><small class='text-muted'>Make future and draft posts available for gridding</small>");
  }
  function gridlock_grid_query_input() {
    $options = get_option('gridlock_grid_query');
    $res = '';
    foreach ($options as $key => $val) {
      $res .= $key . " => " . $val . ", ";
    }
    $res = substr($res, 0, -2);
    echo "<input id='gridlock_query' name='gridlock_grid_query' size='40' type='text' value='{$res}' />";
    echo ("<br/><small class='text-muted'>The query for the grid on the right</small>");
  }
  function register_gridlock() {
    register_setting("gridlock_general", "gridlock_all");
    register_setting("gridlock_general", "gridlock_query", "sanitize_query");
    register_setting("gridlock_general", "gridlock_rows");
    register_setting("gridlock_general", "gridlock_future");
    register_setting("gridlock_general", "gridlock_grid_query", "sanitize_query");
    add_settings_section('gridlock_main', 'General Gridlock Settings', 'gridlock_settings_text', 'gridlock_general');
    add_settings_field("gridlock_all", "Show ungridded on home screen", "gridlock_all_input", "gridlock_general", "gridlock_main" );
    add_settings_field("gridlock_query", "Home screen query", "gridlock_query_input", "gridlock_general", "gridlock_main" );
    add_settings_field("gridlock_rows", "Max rows on home screen", "gridlock_rows_input", "gridlock_general", "gridlock_main" );
    add_settings_field("gridlock_future", "Grid unpublishhed posts", "gridlock_future_input", "gridlock_general", "gridlock_main" );
    add_settings_field("gridlock_grid_query", "Grid screen query", "gridlock_grid_query_input", "gridlock_general", "gridlock_main" );
  }
  // deletes the default posts if the box is unchecked
  // adds them if checked
  function remove_all_query() {
    if (!get_option("gridlock_all")) {
      $remove = new WP_Query(array( "posts_per_page" => 500));
      while ( $remove->have_posts() ) : $remove->the_post(); 
        if (get_post_meta(get_the_ID(), "gridlock", true) < 1) {
          delete_post_meta(get_the_ID(), "gridlock");
        }
      endwhile;
    } else {
      // if gridlock all is set, go through all recent posts and make sure 
      $backorder = new WP_Query(get_option("gridlock_query"));
      while ($backorder->have_posts() ) : $backorder->the_post();
        if (!get_post_meta(get_the_ID(), "gridlock", true)) {
          add_post_meta(get_the_ID(), "gridlock", "0.13", true);
        }
      endwhile;
    }
  }
  add_action( 'admin_init', 'register_gridlock' );
  add_action( 'admin_init', 'remove_all_query' );

  function gridster_head() { ?>
    <script src="<?php echo get_template_directory_uri(); ?>/javascripts/jquery-2.0.3.min.js" type="text/javascript"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo get_stylesheet_directory_uri(); ?>/bootstrap.min.css" />
    <script src="<?php echo get_template_directory_uri(); ?>/javascripts/bootstrap.min.js" type="text/javascript"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo get_stylesheet_directory_uri(); ?>/jquery.gridster.min.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo get_stylesheet_directory_uri(); ?>/gridster.css" />
    <script src="<?php echo get_template_directory_uri(); ?>/javascripts/jquery.gridster.min.js" type="text/javascript"></script>
    <script src="<?php echo get_template_directory_uri(); ?>/javascripts/gridster.js" type="text/javascript"></script>
  <? }
  function gridster_query() {
    if (isset($_GET["query"])) { 
      $query = $_GET["query"];
    } else {
      $query = get_option("gridlock_query");
    }
    return $query;
  }
  function gridlock_future( $array ) {
    if (get_option("gridlock_future")) {
      return array_merge($array, array( "post_status" => "draft,future,publish"));
    } else {
      return $array;
    }
  }
  function gridster() { 
    $query = gridster_query(); 
    $max_row = 0; ?>
    <div data-root='<?php echo site_url(); ?>' class="gridster">
      <div id="grid-buttons" class="row">
        <div class="col-6">
          <button id="btn-save" type="button" class="btn btn-primary btn-block">Save Grid</button>
        </div>
        <div class="col-6">
          <button id="btn-preview" type="button" class="btn btn-success btn-block">Go To Preview</button>
        </div>
      </div>
      <ul>
    <?php
      $params = gridlock_future(array_merge(get_option("gridlock_query"), array('orderby' => 'date', 'order' => 'DESC', "post_status" => "publish" )));
      $gridster_query = new WP_Query($params);
      while ( $gridster_query->have_posts() ) : $gridster_query->the_post(); 
        if (get_post_meta( get_the_ID(), "gridlock", true) > 1) { 
            $gridlock =  explode(".", get_post_meta( get_the_ID(), "gridlock", true)); 
            $index = $gridlock[1][0]; 
            $span = $gridlock[1][1]; 
            $row = $gridlock[0]; ?>
            <li data-row="<?php echo $row ?>" data-col="<?php echo $index ?>" data-sizex="<?php echo $span ?>" data-sizey="1" data-post_id=<?php the_ID(); ?>>
              <div class="gridster-box">
                <div class="row gridster-title">
                  <?php the_title(); ?> 
                </div>
                <div class="row">
                  <button type="button" class="btn btn-info btn-block toggle-btn">Toggle Size</button>
                </div>
                <div class="row">
                  <button type="button" class="btn btn-danger btn-block remove-btn">Remove</button>
                </div>
              </div>
            </li>
        <?php } 
      endwhile;
      ?>
      </ul>
    </div>

  <?php } 

  function gridlock_menu() {
    add_theme_page("Gridlock", "Gridlock", "edit_others_posts", "gridlock", "gridlock_page");
  }

  add_action('admin_menu', 'gridlock_menu');

  function ungridded_posts() {
    $params = gridlock_future(array_merge(get_option("gridlock_grid_query"), array('orderby' => 'date', 'order' => 'DESC', "post_status" => "publish"  )));
    $unassigned = new WP_Query($params);
    echo "<ul id='ungridded' class='list-unstyled'>";
    while ( $unassigned->have_posts() ) : $unassigned->the_post(); 
      if (get_post_meta(get_the_ID(), "gridlock", true) < 1) {
        echo "<li><a href='#' data-post_id=" . get_the_ID() . " class='text-primary'>+" . get_the_title() . "</a></li>";
      }
    endwhile;
    echo "</ul>";
  }

  ?>
  <?php function gridlock_page() { ?>
    <?php gridster_head(); ?>
    <div class="gridlock-container">
      <div class="row">
        <div class="col-5">
          <div class='wrap'>
          <?php screen_icon(); ?>
          <h2>Gridlock Options</h2>
            <div class="sidebar">
              <form action="options.php" method="post">
                <?php settings_fields( "gridlock_general"); ?>
                <?php do_settings_sections("gridlock_general"); ?>
                <?php submit_button(); ?>
              </form>
            <h3>Ungridded Posts</h3>
              <p><small class="text-muted">Click to add to grid</small></p>
              <?php ungridded_posts(); ?> 
            </div>
          </div>
        </div>
        <div class="col-7">
          <div class="gridlocker">
            <?php gridster(); ?>
          </div>
        </div>
      </div>
    </div>
  <?php }
}

function gridlock_widgets_init() {
	register_sidebar( array(
		'name' => __( 'Main Sidebar', 'gridlock' ),
		'id' => 'sidebar-1',
		'description' => __( 'Appears on posts and pages except the optional Front Page template, which has its own widgets', 'gridlock' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
) );
}
add_action( 'widgets_init', 'gridlock_widgets_init' );

function gridlock_stylesheet_directory_uri( $args ) {
  return $args."/css";
}
add_filter( 'stylesheet_directory_uri', 'gridlock_stylesheet_directory_uri', 10, 2 );

function small_author($args) {
  $pattern = "/>([^<]*)</";
  $replacement = "><em class='text-muted'>$1</em><";
  echo preg_replace($pattern, $replacement, $args);
}
add_filter( 'the_author_posts_link', 'small_author', 10, 2 );
// register wp_nav_menu
add_action( 'init', 'register_my_menus' );
function register_my_menus() {
  register_nav_menus( array(
  'primary-menu' => __( 'Primary Menu', 'mytheme' )
  ) );
}

function wp_nav_menu_no_ul()
{
    $options = array(
        'echo' => false,
        'container' => false,
        'theme_location' => 'primary',
        'fallback_cb'=> 'default_page_menu'
    );

    $menu = wp_nav_menu($options);
    echo preg_replace(array(
        '#^<ul[^>]*>#',
        '#</ul>$#'
    ), '', $menu);

}

function default_page_menu() {
   wp_list_pages('title_li=');
} 
function catch_image() {
  global $post, $posts;
  $first_img = '';
  ob_start();
  ob_end_clean();
  $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
  if (isset($matches[1][0])) {
    $first_img = $matches[1][0];
  }

  if(empty($first_img)) {
    $first_img = false;
  }
  return $first_img;
}

function make_endpoint() {
  // register a JSON endpoint for the root
  add_rewrite_endpoint("gridster", EP_ROOT);
}
add_action("init", "make_endpoint");
function add_queryvars( $query_vars ) {  
    $query_vars[] = 'gridster';  
    return $query_vars;  
}  
add_filter( 'query_vars', 'add_queryvars' );

function json_endpoint() {
  global $wp_query;
  if (!isset($wp_query->query_vars['gridster'])) {
    return;
  }

  $posts = $_POST['gridlock'];

  for ($i = 0 ; $i < count($posts); $i++) {
    $post = $posts[$i];
    $id = $post["id"];
    $row = $post["row"];
    $index = $post["index"];
    $span = $post["span"];
    $val = $row . "." . $index . $span;
    update_post_meta($id, "gridlock", $val);
  }

  header("Content-Type: application/json");

  $response = Array( "response" => "success");
  echo json_encode($response);
  exit();
}
add_action( 'template_redirect', 'json_endpoint' );

function endpoints_activate() {
  make_endpoint();
  flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'endpoints_activate' );

function endpoints_deactivate() {
  // flush rules on deactivate as well so they're not left hanging around uselessly
  flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'endpoints_deactivate' );
