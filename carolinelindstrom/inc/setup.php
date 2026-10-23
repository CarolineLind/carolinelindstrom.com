<?php
/*--------------------------------------------------*/
/*--------------------- SETUP ----------------------*/
/*--------------------------------------------------*/

/* Max width (px) for embeds and images in the content area, matches --measure-wide */
if ( ! isset( $content_width ) ) {
	$content_width = 760;
}

function cl_setup() {
	load_theme_textdomain( 'carolinelindstrom', get_template_directory() . '/languages' );

	/* WordPress prints <title> */
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'html5', array( 'gallery', 'caption', 'style', 'script' ) );

	/* Make the block editor look like the front end */
	add_theme_support( 'editor-styles' );
	add_editor_style(
		array(
			'assets/css/base/fonts.css',
			'assets/css/base/tokens.css',
			'assets/css/base/typography.css',
		)
	);

	register_nav_menus(
		array(
			'primary' => __( 'Huvudmeny', 'carolinelindstrom' ),
			'footer'  => __( 'Sidfotsmeny', 'carolinelindstrom' ),
		)
	);
}
add_action( 'after_setup_theme', 'cl_setup' );
