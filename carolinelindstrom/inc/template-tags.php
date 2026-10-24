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

/* The <cl> logo, linking to the start page */
function clind_logo() {
	printf(
		'<a class="logo mono" href="%1$s" aria-label="%2$s"><span class="logo__bracket logo__bracket--l" aria-hidden="true">&lt;</span><span aria-hidden="true">cl</span><span class="logo__bracket logo__bracket--r" aria-hidden="true">&gt;</span></a>',
		esc_url( home_url( '/' ) ),
		/* translators: %s: site name */
		esc_attr( sprintf( __( '%s, startsida', 'carolinelindstrom' ), get_bloginfo( 'name' ) ) )
	);
}
