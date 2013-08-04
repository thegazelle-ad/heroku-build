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
function twentytwelve_setup() {
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
}
add_action( 'after_setup_theme', 'gridlock_setup' );
add_theme_support( 'post-thumbnails' ); 

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
  )
  );
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
  $first_img = $matches[1][0];

  if(empty($first_img)) {
    $first_img = false;
  }
  return $first_img;
}
