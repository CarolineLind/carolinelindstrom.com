<?php
/*--------------------------------------------------*/
/*--------------------- INDEX ----------------------*/
/*--------------------------------------------------*/

/*
Required fallback. Search, archives and posts are turned off (inc/cleanup.php),
so this only shows if the front page is set to "latest posts".
*/

get_header();
?>

<div class="container section">
	<header class="entry-header">
		<h1 class="entry-title"><?php esc_html_e( 'Inlägg', 'carolinelindstrom' ); ?></h1>
	</header>

	<?php if ( have_posts() ) : ?>
		<ul class="entry-list">
			<?php
			while ( have_posts() ) {
				the_post();
				get_template_part( 'template-parts/content/content-excerpt' );
			}
			?>
		</ul>

		<?php the_posts_pagination(); ?>
	<?php else : ?>
		<p class="lead"><?php esc_html_e( 'Här fanns inget ännu.', 'carolinelindstrom' ); ?></p>
	<?php endif; ?>
</div>

<?php
get_footer();
