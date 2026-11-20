<?php
/* --------------------------------------------------
 * --------------------- OM MIG ---------------------
 * -------------------------------------------------- */

/*
"Om mig" (slug om-mig; WordPress picks this file by the slug). The theme draws
the hero with the page's h1 (template-parts/about/hero.php, words and images in
Customize → "Om mig"), then comes the page's block editor content.
A field of dots lies behind the reading time and the hero (.about-top,
components/about-hero.css).
*/

get_header();

while ( have_posts() ) {
	the_post();
	?>
	<div class="about-top">
		<?php
		get_template_part( 'template-parts/sky', null, array( 'stars' => false ) );
		get_template_part( 'template-parts/content/page-meta', null, array( 'part' => 'reading' ) );
		get_template_part( 'template-parts/about/hero' );
		?>
	</div>
	<?php
	get_template_part( 'template-parts/content/content-page' );
	get_template_part( 'template-parts/content/page-meta', null, array( 'part' => 'dates' ) );
}

get_footer();
