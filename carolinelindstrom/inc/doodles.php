<?php
/* --------------------------------------------------
 * -------------------- DOODLES ---------------------
 * -------------------------------------------------- */

/*
Hand-drawn doodles from the design (Main.dc.html): pen strokes in SVG, always
decorative (aria-hidden). pathLength="1" lets the CSS draw each stroke in with
the same numbers, whatever its real length. Colour comes from the CSS (stroke).
	Banner, between the keywords (components/keyword-banner.css): wave, loop,
	sparkle, spiral.
	"Läs vidare" cards, with a hover move each (components/read-more.css): face
	(winks), figure (jumps, arms up), arrow (spins), star (pops, sparkles).
	Start page hero, rising with the scroll (components/hero-shapes.css): the
	banner's four plus a lone star, sun, pen ring, cloud, blob and squiggle.
Groups with a class (.doodle__wink and so on) are the parts that move.
*/

/* The markup of each doodle: [ width, height, viewBox, inner SVG ] */
function clind_doodles() {
	return array(
		'wave'      => array( 52, 24, '0 0 52 24', '<path pathLength="1" d="M3 16 C 12 4, 24 4, 31 12 S 44 20, 49 6"/>' ),
		'loop'      => array( 54, 30, '0 0 54 30', '<path pathLength="1" d="M3 22 C 10 22, 16 18, 19 12 C 22 5, 14 2, 12 8 C 10 15, 22 22, 30 18 C 38 14, 40 6, 36 5 C 32 4, 32 14, 40 19 C 45 22, 49 20, 51 18"/>' ),
		'sparkle'   => array( 30, 30, '0 0 30 30', '<path pathLength="1" d="M15 2 C 16 11, 19 14, 28 15 C 19 16, 16 19, 15 28 C 14 19, 11 16, 2 15 C 11 14, 14 11, 15 2 Z"/>' ),
		'spiral'    => array( 32, 32, '0 0 32 32', '<path pathLength="1" d="M16 16 C 16 13, 20 13, 20 16 C 20 20, 13 21, 12 16 C 11 10, 21 8, 24 15 C 27 22, 18 28, 11 25 C 4 22, 4 10, 11 6 C 18 2, 28 5, 30 12"/>' ),
		'face'      => array( 84, 84, '0 0 64 64', '<g class="doodle__tilt"><path pathLength="1" d="M32 6 C 48 5, 59 17, 58 32 C 57 48, 46 58, 31 58 C 16 58, 6 47, 6 32 C 6 18, 17 7, 34 6"/><path pathLength="1" d="M23 24 C 23 26, 23 28, 23 30"/><g class="doodle__wink"><path pathLength="1" d="M41 24 C 41 26, 41 28, 41 30"/></g><path pathLength="1" d="M20 39 C 26 47, 38 47, 44 38"/></g>' ),
		'figure'    => array( 84, 84, '0 0 64 64', '<g class="doodle__jump"><path pathLength="1" d="M32 5 C 36 5, 38 8, 38 11 C 38 15, 35 17, 32 17 C 28 17, 26 14, 26 11 C 26 7, 29 5, 33 5"/><g class="doodle__arms"><path pathLength="1" d="M9 25 C 19 29, 45 29, 55 24"/></g><path pathLength="1" d="M32 21 C 32 29, 32 35, 32 42"/><path pathLength="1" d="M32 42 C 28 48, 25 54, 21 60"/><path pathLength="1" d="M32 42 C 36 48, 39 54, 43 60"/></g>' ),
		'arrow'     => array( 84, 84, '0 0 64 64', '<g class="doodle__spin"><path pathLength="1" d="M32 8 C 46 8, 56 18, 56 32 C 56 46, 46 56, 32 56 C 18 56, 8 46, 8 32 C 8 24, 11 18, 17 13"/><path pathLength="1" d="M10 15 L 17 13 L 16.5 20"/></g>' ),
		/* Start page hero shapes (template-parts/front/hero-shapes.php) */
		'star-solo' => array( 64, 64, '0 0 64 64', '<path pathLength="1" d="M31 5 Q 35.5 13, 38.5 22.5 Q 48 22, 57 24.5 Q 50.5 30.5, 43.5 36 Q 46 45.5, 48 55.5 Q 39.5 50.5, 32.5 44.5 Q 24.5 50, 15.5 54 Q 18 45, 21 36.5 Q 13.5 30, 6.5 23 Q 16 23.5, 25.5 22.5 Q 28.5 12.5, 33 3.5"/>' ),
		'sun'       => array( 64, 64, '0 0 64 64', '<path pathLength="1" d="M33 22 C 40 22, 43 28, 42 33 C 41 40, 34 43, 29 41 C 23 39, 21 32, 24 27 C 26 23, 30 22, 35 23 M47.9 38 L 55.4 40.8 M38 49 L 39.7 53.7 M26 47.9 L 23.2 55.4 M15 38 L 10.3 39.7 M16.1 26 L 8.6 23.2 M26 15 L 24.3 10.3 M38 16.1 L 40.8 8.6 M49 26 L 53.7 24.3"/>' ),
		'pen-ring'  => array( 64, 64, '0 0 64 64', '<path pathLength="1" d="M40 10 C 26 6, 9 16, 10 33 C 11 50, 28 57, 41 52 C 54 47, 58 30, 50 18 C 46 12, 38 9, 30 11"/>' ),
		'cloud'     => array( 64, 64, '0 0 64 64', '<path pathLength="1" d="M17 44 C 8 44, 6 34, 14 32 C 12 22, 24 18, 28 24 C 32 14, 48 16, 46 28 C 56 28, 58 44, 46 44 C 36 44, 26 45, 15 44"/>' ),
		'blob'      => array( 64, 64, '0 0 64 64', '<path d="M33 8 C 47 7, 57 18, 56 32 C 55 47, 44 57, 30 56 C 17 55, 8 45, 9 31 C 10 17, 20 9, 33 8 Z"/>' ),
		'squiggle'  => array( 64, 64, '0 0 64 64', '<path pathLength="1" d="M4 32 C 8 22, 12 22, 16 32 S 24 42, 28 32 S 36 22, 40 32 S 48 42, 52 32 S 58 24, 60 30"/>' ),
		'star'      => array( 84, 84, '0 0 64 64', '<g class="doodle__pop"><path pathLength="1" d="M31 5 Q 35.5 13, 38.5 22.5 Q 48 22, 57 24.5 Q 50.5 30.5, 43.5 36 Q 46 45.5, 48 55.5 Q 39.5 50.5, 32.5 44.5 Q 24.5 50, 15.5 54 Q 18 45, 21 36.5 Q 13.5 30, 6.5 23 Q 16 23.5, 25.5 22.5 Q 28.5 12.5, 33 3.5"/></g><g class="doodle__sparkle"><path pathLength="1" d="M52 4 C 52.5 7, 52 9.5, 52.5 13 M 48.5 9 C 51 8.5, 53.5 9, 56.5 8.5"/></g>' ),
	);
}

/* Print one doodle. $extra_class is added to the <svg> (e.g. the section's own class) */
function clind_doodle( $name, $extra_class = '' ) {
	$doodles = clind_doodles();
	if ( ! isset( $doodles[ $name ] ) ) {
		return;
	}
	list( $width, $height, $view_box, $inner ) = $doodles[ $name ];
	printf(
		'<svg class="%1$s" width="%2$d" height="%3$d" viewBox="%4$s" fill="none" aria-hidden="true" focusable="false">%5$s</svg>',
		esc_attr( trim( 'doodle doodle--' . $name . ' ' . $extra_class ) ),
		absint( $width ),
		absint( $height ),
		esc_attr( $view_box ),
		/* Literal markup from clind_doodles() above, never from input */
		$inner // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	);
}
