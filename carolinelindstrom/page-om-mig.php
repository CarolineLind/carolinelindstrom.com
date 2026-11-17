<?php
/* --------------------------------------------------
 * --------------------- OM MIG ---------------------
 * -------------------------------------------------- */

/*
"Om mig" (slug om-mig; WordPress picks this file by the slug). The theme draws
the hero with the page's h1 (template-parts/about/hero.php, words and images in
Customize → "Om mig"), then comes the page's block editor content.
*/

get_header();

while ( have_posts() ) {
	the_post();
	get_template_part( 'template-parts/content/page-meta', null, array( 'part' => 'reading' ) );
	get_template_part( 'template-parts/about/hero' );
	get_template_part( 'template-parts/content/content-page' );
	get_template_part( 'template-parts/content/page-meta', null, array( 'part' => 'dates' ) );
}

get_footer();
