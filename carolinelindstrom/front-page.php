<?php
/*--------------------------------------------------*/
/*------------------- FRONT PAGE -------------------*/
/*--------------------------------------------------*/

/*
For now the start page shows its own block editor content.
The design's sections (hero, banner, Läs vidare, code editor, contact) are added here one at a time.
*/

get_header();

while ( have_posts() ) {
	the_post();
	get_template_part( 'template-parts/content/content-page' );
}

get_footer();
