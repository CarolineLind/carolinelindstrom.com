<?php
/* --------------------------------------------------
 * ------------------- FRONT PAGE -------------------
 * -------------------------------------------------- */

/*
The start page's sections come from the theme (template-parts/front/): the
hero, the keyword banner, "Läs vidare" and the code editor. Then the start
page's own block editor content, if it has any.
*/

get_header();

while ( have_posts() ) {
	the_post();
	get_template_part( 'template-parts/front/hero' );
	get_template_part( 'template-parts/front/banner' );
	get_template_part( 'template-parts/front/read-more' );
	get_template_part( 'template-parts/front/code-editor' );

	/* Only when there is something in it: an empty article would still add its section spacing */
	if ( '' !== trim( preg_replace( '/<!--.*?-->/s', '', get_the_content() ) ) ) {
		get_template_part( 'template-parts/content/content-page' );
	}
}

get_footer();
