<?php
/* A page or a single post: title, then the block editor content */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry' ); ?>>
	<!-- /* Consider if we truly want to contain every width. I think there background often vleed out inot the margin and edge of the viewport. Meanwhile the text might have padding and margin to keep it readable */ -->
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
