<?php
/* --------------------------------------------------
 * ---------------------- PAGE ----------------------
 * -------------------------------------------------- */

/* Reading time above the content, the dates below it (template-parts/content/page-meta.php) */

get_header();

while ( have_posts() ) {
	the_post();
	get_template_part( 'template-parts/content/page-meta', null, array( 'part' => 'reading' ) );
	get_template_part( 'template-parts/content/content-page' );
	get_template_part( 'template-parts/content/page-meta', null, array( 'part' => 'dates' ) );
}

get_footer();
