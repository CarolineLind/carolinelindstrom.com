<?php
/*--------------------------------------------------*/
/*--------------------- INDEX ----------------------*/
/*--------------------------------------------------*/

/* Fallback for everything without its own template: blog, archives, search */

get_header();
?>

<div class="container section">
	<header class="entry-header">
		<h1 class="entry-title">
			<?php
			if ( is_search() ) {
				/* translators: %s: search term */
				printf( esc_html__( 'Sökresultat för "%s"', 'carolinelindstrom' ), esc_html( get_search_query() ) );
			} elseif ( is_archive() ) {
				echo esc_html( wp_strip_all_tags( get_the_archive_title() ) );
			} elseif ( is_home() && ! is_front_page() ) {
				single_post_title();
			} else {
				esc_html_e( 'Inlägg', 'carolinelindstrom' );
			}
			?>
		</h1>
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
