<?php
/**
 * virtue initial setup and constants
 */
function kadence_setup() {

  // Register wp_nav_menu() menus (http://codex.wordpress.org/Function_Reference/register_nav_menus)
  register_nav_menus(array(
    'topbar_navigation' => __('Topbar Navigation', 'virtue'),
    'primary_navigation' => __('Primary Navigation (Near Logo Side)', 'virtue'),
    'secondary_navigation' => __('Secondary Navigation (Above Header)', 'virtue'),
    'third_navigation' => __('Third Navigation (Below Header)', 'virtue'),
    'footer_navigation' => __('Footer Navigation', 'virtue'),
    'mobile_navigation' => __('Mobile Navigation', 'virtue'),
  ));
  
  // Add post thumbnails (http://codex.wordpress.org/Post_Thumbnails)
  add_theme_support('post-thumbnails');
  add_image_size( 'widget-thumb', 80, 50, true );
  //add_image_size( 'product-cat-thumb', 270, 270, true );
  //add_image_size( 'portfolio-cat-thumb', 370, 370, true );
  add_post_type_support( 'attachment', 'page-attributes' );
  // set_post_thumbnail_size(150, 150, false);
  // add_image_size('category-thumb', 300, 9999); // 300px wide (and unlimited height)

  // Add post formats (http://codex.wordpress.org/Post_Formats)
  //add_theme_support('post-formats', array('gallery', 'image', 'video'));
  add_theme_support( 'automatic-feed-links' );
  // Tell the TinyMCE editor to use a custom stylesheet
  add_editor_style('/assets/css/editor-style-virtue.css');
}
add_action('after_setup_theme', 'kadence_setup');

// Backwards compatibility for older than PHP 5.3.0
if (!defined('__DIR__')) { define('__DIR__', dirname(__FILE__)); }

/**
 * Define helper constants
 */
$get_theme_name = explode('/themes/', get_template_directory());

define('RELATIVE_PLUGIN_PATH',  str_replace(home_url() . '/', '', plugins_url()));
define('RELATIVE_CONTENT_PATH', str_replace(home_url() . '/', '', content_url()));
define('THEME_NAME',            next($get_theme_name));
define('THEME_PATH',            RELATIVE_CONTENT_PATH . '/themes/' . THEME_NAME);
