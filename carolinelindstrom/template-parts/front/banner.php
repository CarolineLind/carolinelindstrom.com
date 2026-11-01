<?php
/*
The keyword banner under the hero (components/keyword-banner.css): the
keywords from Customize → "Startsidan" (inc/start-page.php) glide past in an
endless loop, each followed by a hand-drawn doodle (inc/doodles.php).
The track holds four identical copies and slides by half its width, so the
loop has no seam even on very wide screens. Only the first copy is a list for
screen readers; the other three are hidden from them.
*/

$clind_words = clind_banner_words();
if ( ! $clind_words ) {
	return;
}
$clind_doodle_names = array( 'wave', 'loop', 'sparkle', 'spiral' );
$clind_colors       = array( 'yellow', 'lilac', 'sky' );
?>
<div class="keyword-banner">
	<div class="keyword-banner__track">
		<?php for ( $clind_copy = 0; $clind_copy < 4; $clind_copy++ ) : ?>
			<ul class="keyword-banner__list"
				<?php
				if ( $clind_copy ) {
					echo ' aria-hidden="true"';
				} else {
					echo ' aria-label="' . esc_attr__( 'Nyckelord', 'carolinelindstrom' ) . '"';
				}
				?>
			>
				<?php foreach ( $clind_words as $clind_i => $clind_word ) : ?>
					<li class="keyword-banner__item" style="--i: <?php echo absint( $clind_i ); ?>">
						<?php echo esc_html( $clind_word ); ?>
						<?php clind_doodle( $clind_doodle_names[ $clind_i % 4 ], 'keyword-banner__doodle keyword-banner__doodle--' . $clind_colors[ $clind_i % 3 ] ); ?>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endfor; ?>
	</div>
</div>
