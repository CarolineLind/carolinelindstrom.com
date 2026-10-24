<?php
/* --------------------------------------------------
 * ---------------------- ICONS ---------------------
 * -------------------------------------------------- */

/*
Favicon, home screen icon and browser color, owned by the theme instead of
WordPress's Site Icon setting (docs/decisions/0005-logo-and-favicon.md).
favicon.svg switches between light and dark with the system's setting.
The PNGs are its dark version, rendered by tools/icons/render-icons.mjs.
*/

/* WordPress would print the Site Icon from Customizer as well */
remove_action( 'wp_head', 'wp_site_icon', 99 );

function clind_icons() {
	$icons = get_template_directory_uri() . '/assets/icons/';
	?>
	<link rel="icon" href="<?php echo esc_url( $icons . 'favicon-32.png' ); ?>" sizes="32x32">
	<link rel="icon" href="<?php echo esc_url( $icons . 'favicon.svg?ver=' . clind_asset_version( 'assets/icons/favicon.svg' ) ); ?>" type="image/svg+xml">
	<link rel="apple-touch-icon" href="<?php echo esc_url( $icons . 'apple-touch-icon.png' ); ?>">
	<?php /* Browser bars on phones: the page background, --color-bg in tokens.css */ ?>
	<meta name="theme-color" content="#FBFAF6" media="(prefers-color-scheme: light)">
	<meta name="theme-color" content="#15131F" media="(prefers-color-scheme: dark)">
	<?php
}
add_action( 'wp_head', 'clind_icons', 2 );

/* /favicon.ico: WordPress redirects it to the Site Icon or its own logo, so point it at ours */
function clind_favicon_ico() {
	wp_safe_redirect( get_template_directory_uri() . '/assets/icons/favicon-32.png' );
	exit;
}
add_action( 'do_faviconico', 'clind_favicon_ico' );
