<?php
/* A page or a single post: title, then the block editor content */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry container section' ); ?>>
	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
	</header>

	<div class="entry-content">
		<?php
		the_content();

		wp_link_pages(
			array(
				'before' => '<nav class="page-links" aria-label="' . esc_attr__( 'Sidor', 'carolinelindstrom' ) . '">',
				'after'  => '</nav>',
			)
		);
		?>
	</div>
</article>
