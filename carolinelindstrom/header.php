<!doctype html>
<!-- START header.php -->
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link" href="#main"><?php esc_html_e( 'Hoppa till innehållet', 'carolinelindstrom' ); ?></a>

<header class="site-header">
	<?php get_template_part( 'template-parts/header/site-nav' ); ?>
</header>

<main id="main" class="site-main" tabindex="-1">
<!-- END header.php -->
