<!DOCTYPE html>
<html lang="nl">
<head>
<meta charset="utf-8">
<title><?php bloginfo('name');?><?php wp_title();?></title>
<meta name="description" content="<?php bloginfo('description');?>" />
<meta http-equiv="language" content="<?php echo $lang = get_bloginfo("language"); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, user-scalable=no"/>
<link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri(); ?>/favicon.png" />
<link href="<?php bloginfo('stylesheet_url');?>" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
<link href="https://fonts.googleapis.com/css?family=Playfair+Display:400,400i,700,700i,900,900i|Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i&display=swap" rel="stylesheet">


<?php wp_head();?>
</head>

<?php get_template_part('components/navbar'); ?>
