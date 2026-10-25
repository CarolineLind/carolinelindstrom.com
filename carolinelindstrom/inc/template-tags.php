<?php
/* --------------------------------------------------
 * ----------------- TEMPLATE TAGS ------------------
 * -------------------------------------------------- */

/* Give menu links the .navlink class. WordPress already adds aria-current="page" to the current item. */
function clind_nav_link_attributes( $atts, $item, $args ) {
	if ( isset( $args->theme_location ) && 'primary' === $args->theme_location ) {
		$atts['class'] = trim( ( $atts['class'] ?? '' ) . ' navlink' );
	}
	return $atts;
}
add_filter( 'nav_menu_link_attributes', 'clind_nav_link_attributes', 10, 3 );

/*
The inside of a main menu link. The label goes in .navlink__text with the text
again in data-text, so CSS can reserve the width of its bold version and the menu
doesn't shift when the current page (bold) changes (layout/header.css).
Then sparkles that twinkle around it on hover and focus (components/nav-indicator.css):
empty spans, the star shape, size, place and color all come from the CSS.
*/
function clind_nav_item_title( $title, $item, $args ) {
	if ( isset( $args->theme_location ) && 'primary' === $args->theme_location ) {
		$title = sprintf(
			'<span class="navlink__text" data-text="%1$s">%2$s</span><span class="sparks" aria-hidden="true">%3$s</span>',
			esc_attr( wp_strip_all_tags( $title ) ),
			$title,
			str_repeat( '<span class="spark"></span>', 6 )
		);
	}
	return $title;
}
add_filter( 'nav_menu_item_title', 'clind_nav_item_title', 10, 3 );

/*
The pen strokes of the hand-drawn <cl> logo: the brackets and "cl" in one stroke.
Shared by the header logo and the small mark in the footer.
*/
function clind_logo_paths() {
	return array(
		'bracket-l' => 'M74 66 C 60 80, 44 94, 30 112 C 46 126, 60 138, 76 154',
		'word'      => 'M166 94 C 158 78, 134 74, 120 88 C 104 104, 104 140, 126 152 C 146 162, 168 152, 186 138 C 210 118, 240 86, 244 56 C 247 30, 220 26, 216 54 C 212 86, 214 124, 222 146 C 228 160, 246 160, 260 150',
		'bracket-r' => 'M304 64 C 320 80, 336 94, 356 108 C 340 124, 324 140, 306 156',
	);
}

/*
The hand-drawn <cl> logo, linking to the start page (components/logo.css).
Pen strokes in SVG, no font: the brackets, "cl" in one stroke, and a swash
under it that is drawn on hover. pathLength="1" lets the CSS write each stroke
in with the same numbers, whatever its real length.
*/
function clind_logo() {
	$paths = clind_logo_paths();
	printf(
		'<a class="logo" href="%1$s" aria-label="%2$s"><svg class="logo__svg" viewBox="0 12 390 180" width="69" height="32" aria-hidden="true" focusable="false">'
		. '<path class="logo__bracket logo__bracket--l" pathLength="1" d="%3$s"/>'
		. '<path class="logo__word" pathLength="1" d="%4$s"/>'
		. '<path class="logo__bracket logo__bracket--r" pathLength="1" d="%5$s"/>'
		. '<path class="logo__swash" d="M108 199 C 150 189, 204 193, 236 194 C 252 194.5, 264 192, 276 187"/>'
		. '</svg></a>',
		esc_url( home_url( '/' ) ),
		/* translators: %s: site name */
		esc_attr( sprintf( __( '%s, startsida', 'carolinelindstrom' ), get_bloginfo( 'name' ) ) ),
		esc_attr( $paths['bracket-l'] ),
		esc_attr( $paths['word'] ),
		esc_attr( $paths['bracket-r'] )
	);
}

/*
The logo as a small, muted mark beside the copyright line. Decorative: the header
logo is the link home. Same stroke classes and pathLength as the header logo, so
assets/js/footer.js can write it in with .logo--write (components/logo.css).
*/
function clind_logo_mark() {
	$classes = array(
		'bracket-l' => 'logo__bracket logo__bracket--l',
		'word'      => 'logo__word',
		'bracket-r' => 'logo__bracket logo__bracket--r',
	);
	echo '<svg class="site-footer__logo" viewBox="0 12 390 180" width="58" height="26" aria-hidden="true" focusable="false">';
	foreach ( clind_logo_paths() as $part => $d ) {
		printf( '<path class="%1$s" pathLength="1" d="%2$s"/>', esc_attr( $classes[ $part ] ), esc_attr( $d ) );
	}
	echo '</svg>';
}

/*
The privacy page: the one chosen under Settings → Privacy, otherwise the
published page with the slug "integritetspolicy". Empty if neither exists,
and the footer then leaves the link out.
*/
function clind_privacy_url() {
	$url = get_privacy_policy_url();
	if ( $url ) {
		return $url;
	}
	$page = get_page_by_path( 'integritetspolicy' );
	return ( $page && 'publish' === $page->post_status ) ? get_permalink( $page ) : '';
}
