<?php
/*
"Läs vidare" on the start page: a carousel of notebooks, one per page
(components/read-more.css for the carousel, components/notebook.css for the
book, js/read-more.js for the buttons). Each slide is one link: a notebook
that opens on hover and keyboard focus (on touch screens: when it's the
current slide), and the page's title, excerpt and "Läs mer".
The pages come from the menu at "Läs vidare på startsidan", with their excerpt
and cover words (clind_read_more_items() in inc/start-page.php).
Built after the WAI-ARIA carousel pattern: the section is the carousel, each
slide a group "1 av 4", buttons below. No autoplay. Without JavaScript the
notebooks are listed one under the other and the buttons are hidden.
*/

$clind_items = clind_read_more_items();
if ( ! $clind_items ) {
	return;
}
$clind_defaults = clind_hero_defaults();
$clind_count    = count( $clind_items );

/* Doodle and colour per notebook, in the design's order; a fifth starts over */
$clind_looks = array(
	array( 'face', 'lilac' ),
	array( 'figure', 'yellow' ),
	array( 'arrow', 'sky' ),
	array( 'star', 'dark' ),
);
?>
<section class="read-more" aria-roledescription="<?php esc_attr_e( 'karusell', 'carolinelindstrom' ); ?>" aria-labelledby="read-more-title">
	<div class="read-more__inner container">
		<h2 id="read-more-title" class="read-more__title"><?php echo esc_html( get_theme_mod( 'clind_read_title', $clind_defaults['read_title'] ) ); ?></h2>

		<div class="read-more__slides" id="read-more-slides">
			<?php foreach ( $clind_items as $clind_i => $clind_item ) : ?>
				<?php $clind_look = $clind_looks[ $clind_i % count( $clind_looks ) ]; ?>
				<div class="read-more__slide notebook--<?php echo esc_attr( $clind_look[1] ); ?><?php echo 0 === $clind_i ? ' read-more__slide--current' : ''; ?>" role="group" aria-roledescription="<?php esc_attr_e( 'bild', 'carolinelindstrom' ); ?>" aria-label="<?php echo esc_attr( sprintf( /* translators: 1: slide number, 2: number of slides */ __( '%1$d av %2$d', 'carolinelindstrom' ), $clind_i + 1, $clind_count ) ); ?>">
					<a class="read-more__link" href="<?php echo esc_url( $clind_item['url'] ); ?>">
						<span class="read-more__stage" aria-hidden="true">
							<span class="notebook">
								<span class="notebook__shadow"></span>
								<span class="notebook__book">
									<span class="notebook__back"></span>
									<span class="notebook__spine"></span>
									<span class="notebook__edge-top"></span>
									<span class="notebook__edge-side"></span>
									<span class="notebook__paper"></span>
									<span class="notebook__page">
										<?php clind_doodle( $clind_look[0], 'notebook__doodle' ); ?>
										<span class="notebook__page-title"><?php echo esc_html( $clind_item['title'] ); ?></span>
									</span>
									<span class="notebook__cover">
										<span class="notebook__inside"></span>
										<span class="notebook__front">
											<span class="notebook__label">
												<span class="notebook__title"><?php echo esc_html( $clind_item['title'] ); ?></span>
												<?php if ( $clind_item['words'] ) : ?>
													<span class="notebook__words">
														<?php foreach ( $clind_item['words'] as $clind_word ) : ?>
															<span><?php echo esc_html( $clind_word ); ?></span>
														<?php endforeach; ?>
													</span>
												<?php endif; ?>
											</span>
										</span>
									</span>
									<span class="notebook__band"></span>
									<span class="notebook__ribbon"></span>
								</span>
							</span>
						</span>

						<div class="read-more__copy">
							<h3 class="read-more__name"><span class="read-more__num" aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $clind_i + 1 ) ); ?></span><?php echo esc_html( $clind_item['title'] ); ?></h3>
							<?php if ( $clind_item['text'] ) : ?>
								<p class="read-more__text"><?php echo esc_html( $clind_item['text'] ); ?></p>
							<?php endif; ?>
							<span class="read-more__more" aria-hidden="true">
								<?php esc_html_e( 'Läs mer', 'carolinelindstrom' ); ?>
								<svg class="read-more__arrow" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" focusable="false"><path d="M5 12h14M13 6l6 6-6 6" /></svg>
							</span>
						</div>
					</a>
				</div>
			<?php endforeach; ?>
		</div>

		<?php if ( $clind_count > 1 ) : ?>
			<div class="read-more__nav">
				<div class="read-more__dots">
					<?php for ( $clind_i = 0; $clind_i < $clind_count; $clind_i++ ) : ?>
						<button class="read-more__dot" type="button" aria-controls="read-more-slides" aria-label="<?php echo esc_attr( sprintf( /* translators: 1: slide number, 2: number of slides */ __( 'Visa %1$d av %2$d', 'carolinelindstrom' ), $clind_i + 1, $clind_count ) ); ?>"<?php echo 0 === $clind_i ? ' aria-current="true"' : ''; ?>></button>
					<?php endfor; ?>
				</div>
				<span class="read-more__count" aria-hidden="true">1 / <?php echo absint( $clind_count ); ?></span>
				<span class="read-more__status sr-only" aria-live="polite" data-text="<?php /* translators: 1: slide number, 2: number of slides */ esc_attr_e( 'Bild %1$d av %2$d', 'carolinelindstrom' ); ?>"></span>
				<button class="read-more__step read-more__step--prev" type="button" aria-controls="read-more-slides" aria-label="<?php esc_attr_e( 'Föregående', 'carolinelindstrom' ); ?>" aria-disabled="true">
					<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M5 12h14M13 6l6 6-6 6" /></svg>
				</button>
				<button class="read-more__step read-more__step--next" type="button" aria-controls="read-more-slides" aria-label="<?php esc_attr_e( 'Nästa', 'carolinelindstrom' ); ?>">
					<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M5 12h14M13 6l6 6-6 6" /></svg>
				</button>
			</div>
		<?php endif; ?>
	</div>
</section>
