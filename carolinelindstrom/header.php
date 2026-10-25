<!doctype html>
<!-- START header.php -->
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="color-scheme" content="dark light">
	<?php
	/*
	The page background before the stylesheets have loaded, so the browser never
	flashes white between pages. Same colors as --color-bg in tokens.css.
	*/
	?>
	<style>html{color-scheme:dark;background:#15131F}html[data-theme="light"]{color-scheme:light;background:#FBFAF6}</style>
	<?php wp_head(); ?>
</head>
<?php /* id="top": the footer's "Till toppen" link scrolls here */ ?>
<body id="top" <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link" href="#main"><?php esc_html_e( 'Hoppa till innehållet', 'carolinelindstrom' ); ?></a>
<div class="scroll-progress" aria-hidden="true"></div>

<header class="site-header">
	<div class="site-header__inner container">
		<?php clind_logo(); ?>
		<?php get_template_part( 'template-parts/header/site-nav' ); ?>
		<?php get_template_part( 'template-parts/header/toggles' ); ?>
	</div>
</header>

<main id="main" class="site-main" tabindex="-1">
<!-- END header.php -->
