<?php
/*
A page or a single post: the block editor content. The page's h1 is written in
the content (Caroline's choice), so the theme doesn't print the title.
The container keeps text at a readable width; a block that should reach the edges
of the window (a background, say) uses the editor's "Full width" (.alignfull).
*/
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry container section' ); ?>>
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
