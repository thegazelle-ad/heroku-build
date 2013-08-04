<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress_Themes
 * @subpackage Gridlock
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width" />
<title><?php wp_title( '|', true, 'right' ); ?></title>
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<link rel="stylesheet" type="text/css" href="<?php echo get_stylesheet_directory_uri(); ?>/bootstrap.min.css" />
<link rel="stylesheet" type="text/css" href="<?php echo get_stylesheet_directory_uri(); ?>/style.css" />
<script src="<?php echo get_template_directory_uri(); ?>/javascripts/jquery-2.0.3.min.js" type="text/javascript"></script>
<script src="<?php echo get_template_directory_uri(); ?>/javascripts/bootstrap.min.js" type="text/javascript"></script>
<script src="<?php echo get_template_directory_uri(); ?>/javascripts/script.js" type="text/javascript"></script>
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<div id="main" class="container">
  <div id="header" class="page-header">
    <h1><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr ( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
      <?php bloginfo( 'name' ); ?>
    </a>
      <small><?php bloginfo ( 'description' ); ?></small>
    </h1>
  </div>
  <div class="navbar">
    <!-- .navbar-toggle is used as the toggle for collapsed navbar content -->
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-responsive-collapse">
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </button>
    <div class="nav-collapse collapse navbar-responsive-collapse">
      <ul class="nav navbar-nav">
        <?php wp_nav_menu_no_ul(); ?>
      </ul>
    </div>
    <?php get_search_form(); ?>
  </div>
  <div id="body" class="container">
