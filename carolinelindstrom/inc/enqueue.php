<?php
/*--------------------------------------------------*/
/*-------------------- ENQUEUE ---------------------*/
/*--------------------------------------------------*/

/* Version from the file's modified time so the browser never shows stale CSS while we build */
function clind_asset_version( $path ) {
	$file = get_template_directory() . '/' . $path;
	return file_exists( $file ) ? (string) filemtime( $file ) : wp_get_theme()->get( 'Version' );
}

function clind_enqueue_styles() {
	/* Load order matters: fonts and tokens first, then base, layout and components */
	$styles = array(
		'cl-fonts'      => 'assets/css/base/fonts.css',
		'cl-tokens'     => 'assets/css/base/tokens.css',
		'cl-reset'      => 'assets/css/base/reset.css',
		'cl-typography' => 'assets/css/base/typography.css',
		'cl-utilities'  => 'assets/css/base/utilities.css',
		'cl-header'     => 'assets/css/layout/header.css',
		'cl-footer'     => 'assets/css/layout/footer.css',
		'cl-content'    => 'assets/css/layout/content.css',
	);

	$previous = array();
	foreach ( $styles as $handle => $path ) {
		wp_enqueue_style( $handle, get_template_directory_uri() . '/' . $path, $previous, clind_asset_version( $path ) );
		$previous = array( $handle );
	}
}
add_action( 'wp_enqueue_scripts', 'clind_enqueue_styles' );

/*
Start downloading the fonts used on the first screen right away, before the CSS
is parsed. JetBrains Mono is left out: it's only used for small labels.
crossorigin is required for fonts, even from the same domain.
*/
function clind_preload_fonts() {
	foreach ( array( 'bricolage-grotesque', 'dm-sans' ) as $font ) {
		printf(
			'<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n",
			esc_url( get_template_directory_uri() . '/assets/fonts/' . $font . '.woff2' )
		);
	}
}
add_action( 'wp_head', 'clind_preload_fonts', 1 );

/*
Set light/dark and paused motion on <html> before anything is painted, so the page never flashes the wrong colors.
Saved choice first, else the visitor's system setting. The toggle buttons that write cl-theme / cl-motion come later.
*/
function clind_theme_mode_script() {
	?>
	<script>
	(function () {
		var root = document.documentElement;
		var theme = null;
		var motion = null;
		try {
			theme = localStorage.getItem('cl-theme');
			motion = localStorage.getItem('cl-motion');
		} catch (e) {}
		if (theme !== 'light' && theme !== 'dark') {
			theme = window.matchMedia && window.matchMedia('(prefers-color-scheme: light)').matches ? 'light' : 'dark';
		}
		root.setAttribute('data-theme', theme);
		if (motion === 'paused') {
			root.setAttribute('data-motion', 'paused');
		}
	})();
	</script>
	<?php
}
add_action( 'wp_head', 'clind_theme_mode_script', 0 );
