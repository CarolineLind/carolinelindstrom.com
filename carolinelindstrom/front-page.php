<?php
/* --------------------------------------------------
 * ------------------- FRONT PAGE -------------------
 * -------------------------------------------------- */

/*
The start page's sections come from the theme (template-parts/front/): the
hero, the keyword banner and "Läs vidare". Then the start page's own block
editor content. The code editor section is still to come.
*/

get_header();

while ( have_posts() ) {
	the_post();
	get_template_part( 'template-parts/front/hero' );
	get_template_part( 'template-parts/front/banner' );
	get_template_part( 'template-parts/front/read-more' );
	get_template_part( 'template-parts/content/content-page' );
}

get_footer();
