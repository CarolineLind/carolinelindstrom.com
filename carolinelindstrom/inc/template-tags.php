<?php
/*--------------------------------------------------*/
/*----------------- TEMPLATE TAGS ------------------*/
/*--------------------------------------------------*/

/* Give menu links the .navlink class. WordPress already adds aria-current="page" to the current item. */
function cl_nav_link_attributes( $atts, $item, $args ) {
	if ( isset( $args->theme_location ) && 'primary' === $args->theme_location ) {
		$atts['class'] = trim( ( $atts['class'] ?? '' ) . ' navlink' );
	}
	return $atts;
}
add_filter( 'nav_menu_link_attributes', 'cl_nav_link_attributes', 10, 3 );

/* The <cl> logo, linking to the start page */
function cl_logo() {
	printf(
		'<a class="logo mono" href="%1$s" aria-label="%2$s"><span class="logo__bracket logo__bracket--l" aria-hidden="true">&lt;</span><span aria-hidden="true">cl</span><span class="logo__bracket logo__bracket--r" aria-hidden="true">&gt;</span></a>',
		esc_url( home_url( '/' ) ),
		esc_attr( sprintf( __( '%s, startsida', 'carolinelindstrom' ), get_bloginfo( 'name' ) ) )
	);
}
