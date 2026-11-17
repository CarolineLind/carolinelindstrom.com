<?php
/*
A row of facts about the page (inc/page-meta.php, components/page-meta.css).
	get_template_part( ..., array( 'part' => 'reading' ) )  above the page:
		"6–9 minuter · 1 438 ord"
	get_template_part( ..., array( 'part' => 'dates' ) )    below it:
		"Publicerad 24 december 2019 · Uppdaterad 27 mars 2025", right-aligned.
		"Uppdaterad" only when it was changed on a later day.
Runs inside the loop.
*/

$clind_part = isset( $args['part'] ) && 'dates' === $args['part'] ? 'dates' : 'reading';
?>
<div class="page-meta page-meta--<?php echo esc_attr( $clind_part ); ?> container">
	<p class="page-meta__row">
		<?php
		if ( 'reading' === $clind_part ) :
			$clind_words   = clind_word_count();
			$clind_minutes = clind_reading_minutes( $clind_words );
			?>
			<span>
				<?php
				if ( $clind_minutes[0] === $clind_minutes[1] ) {
					/* translators: %s: reading time in minutes */
					$clind_time = sprintf( _n( '%s minut', '%s minuter', $clind_minutes[0], 'carolinelindstrom' ), number_format_i18n( $clind_minutes[0] ) );
				} else {
					/* translators: 1: fewest minutes, 2: most minutes */
					$clind_time = sprintf( __( '%1$s–%2$s minuter', 'carolinelindstrom' ), number_format_i18n( $clind_minutes[0] ), number_format_i18n( $clind_minutes[1] ) );
				}
				echo esc_html( $clind_time );
				?>
			</span>
			<span>
				<?php
				/* translators: %s: number of words */
				echo esc_html( sprintf( _n( '%s ord', '%s ord', $clind_words, 'carolinelindstrom' ), number_format_i18n( $clind_words ) ) );
				?>
			</span>
		<?php else : ?>
			<span>
				<?php esc_html_e( 'Publicerad', 'carolinelindstrom' ); ?>
				<time datetime="<?php echo esc_attr( get_the_date( 'Y-m-d' ) ); ?>"><?php echo esc_html( get_the_date( 'j F Y' ) ); ?></time>
			</span>
			<?php if ( get_the_modified_date( 'Y-m-d' ) > get_the_date( 'Y-m-d' ) ) : ?>
				<span>
					<?php esc_html_e( 'Uppdaterad', 'carolinelindstrom' ); ?>
					<time datetime="<?php echo esc_attr( get_the_modified_date( 'Y-m-d' ) ); ?>"><?php echo esc_html( get_the_modified_date( 'j F Y' ) ); ?></time>
				</span>
			<?php endif; ?>
		<?php endif; ?>
	</p>
</div>
