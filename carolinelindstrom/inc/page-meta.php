<?php
/* --------------------------------------------------
 * ------------------- PAGE META --------------------
 * -------------------------------------------------- */

/*
Reading time and word count above a page, publish and update dates below it
(template-parts/content/page-meta.php, components/page-meta.css).

Reading time is a range, from an average reader to a slower one. Adults read
non-fiction silently at 238 words a minute on average and aloud at 183
(Brysbaert 2019, Journal of Memory and Language 109). The slow end, a little
below reading aloud, is a judgement: docs/decisions/0012-about-hero.md.
*/
const CLIND_WPM_FAST = 240;
const CLIND_WPM_SLOW = 175;

/*
The page's word count: the text of its content, plus any words the theme draws
above it (the "Om mig" hero), so the count matches what the visitor reads.
Words are runs of letters or digits, so "å", "ä" and "ö" count as letters.
*/
function clind_word_count( $post = null ) {
	$post = get_post( $post );
	if ( ! $post ) {
		return 0;
	}

	$text = wp_strip_all_tags( strip_shortcodes( $post->post_content ) );

	if ( 'om-mig' === $post->post_name && function_exists( 'clind_about_content' ) ) {
		$about = clind_about_content();
		$text .= ' ' . $about['title'] . ' ' . $about['text'];
	}

	return (int) preg_match_all( '/[\p{L}\p{N}][\p{L}\p{N}\'’-]*/u', $text );
}

/* The reading time as [ fewest, most ] minutes, at least one */
function clind_reading_minutes( $words ) {
	return array(
		max( 1, (int) ceil( $words / CLIND_WPM_FAST ) ),
		max( 1, (int) ceil( $words / CLIND_WPM_SLOW ) ),
	);
}
