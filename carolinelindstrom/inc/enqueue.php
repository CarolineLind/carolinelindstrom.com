<?php
/*--------------------------------------------------*/
/*-------------------- ENQUEUE ---------------------*/
/*--------------------------------------------------*/

/* Google Fonts for now – swap for self-hosted files in assets/fonts/ later */
function cl_fonts_url() {
	return 'https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,400;12..96,600;12..96,800&family=DM+Sans:opsz,wght@9..40,400;9..40,500;9..40,700&family=JetBrains+Mono:wght@400;600&display=swap';
}

/* Version from the file's modified time so the browser never shows stale CSS while we build */
function cl_asset_version( $path ) {
	$file = get_template_directory() . '/' . $path;
	return file_exists( $file ) ? (string) filemtime( $file ) : wp_get_theme()->get( 'Version' );
}

function cl_enqueue_styles() {
	wp_enqueue_style( 'cl-fonts', cl_fonts_url(), array(), null );

	/* Load order matters: tokens first, then base, layout and components */
	$styles = array(
		'cl-tokens'     => 'assets/css/base/tokens.css',
		'cl-reset'      => 'assets/css/base/reset.css',
		'cl-typography' => 'assets/css/base/typography.css',
		'cl-utilities'  => 'assets/css/base/utilities.css',
		'cl-header'     => 'assets/css/layout/header.css',
		'cl-footer'     => 'assets/css/layout/footer.css',
		'cl-content'    => 'assets/css/layout/content.css',
	);

	$previous = 'cl-fonts';
	foreach ( $styles as $handle => $path ) {
		wp_enqueue_style( $handle, get_template_directory_uri() . '/' . $path, array( $previous ), cl_asset_version( $path ) );
		$previous = $handle;
	}
}
add_action( 'wp_enqueue_scripts', 'cl_enqueue_styles' );

function cl_resource_hints( $urls, $relation_type ) {
	if ( 'preconnect' === $relation_type ) {
		$urls[] = 'https://fonts.googleapis.com';
		$urls[] = array(
			'href'        => 'https://fonts.gstatic.com',
			'crossorigin' => 'anonymous',
		);
	}
	return $urls;
}
add_filter( 'wp_resource_hints', 'cl_resource_hints', 10, 2 );

/*
Set light/dark and paused motion on <html> before anything is painted, so the page never flashes the wrong colors.
Saved choice first, else the visitor's system setting. The toggle buttons that write cl-theme / cl-motion come later.
*/
function cl_theme_mode_script() {
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
add_action( 'wp_head', 'cl_theme_mode_script', 0 );
