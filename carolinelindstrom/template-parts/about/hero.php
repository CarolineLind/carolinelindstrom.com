<?php
/*
The "Om mig" hero (components/about-hero.css, js/about-hero.js): the page's h1
and a short text beside a pile of three taped polaroids, with doodles around
them. The pile spreads on hover and focus; with a mouse each polaroid can be
dragged, on touch too, and "Blanda bilderna" scatters them (the alternative to
dragging for keyboard and single taps, see docs/decisions/0012-about-hero.md).
Words and images come from Customize → "Om mig" (inc/about-page.php).
Without an image a polaroid shows an empty photo area, so the pile still works.
*/

$clind_about = clind_about_content();

/* Tape colour per polaroid, back to front */
$clind_tapes = array( 'yellow', 'sky', 'lilac' );
?>
<section class="about-hero" aria-labelledby="about-hero-title">
	<div class="about-hero__inner container">
		<div class="about-hero__text">
			<h1 id="about-hero-title" class="about-hero__title"><?php echo esc_html( $clind_about['title'] ); ?></h1>
			<?php if ( $clind_about['text'] ) : ?>
				<p class="about-hero__lead lead"><?php echo esc_html( $clind_about['text'] ); ?></p>
			<?php endif; ?>
		</div>

		<div class="about-hero__pile" data-about-pile>
			<?php clind_doodle( 'sparkle', 'about-hero__doodle about-hero__doodle--sparkle' ); ?>
			<?php clind_doodle( 'flower', 'about-hero__doodle about-hero__doodle--flower' ); ?>

			<?php foreach ( $clind_about['photos'] as $clind_i => $clind_photo ) : ?>
				<figure class="about-hero__photo about-hero__photo--<?php echo absint( $clind_i + 1 ); ?> about-hero__photo--<?php echo esc_attr( $clind_tapes[ $clind_i ] ); ?>" data-about-photo>
					<?php if ( $clind_photo['image'] ) : ?>
						<?php
						/* On the first screen: never lazy. sizes follows --polaroid-photo and --polaroid-photo-mobile */
						echo wp_get_attachment_image(
							$clind_photo['image'],
							'medium_large',
							false,
							array(
								'class'     => 'about-hero__img',
								'loading'   => false,
								'sizes'     => '(width <= 720px) 154px, 226px',
								'draggable' => 'false',
							)
						);
						?>
					<?php else : ?>
						<div class="about-hero__img about-hero__img--empty" aria-hidden="true"></div>
					<?php endif; ?>

					<?php if ( $clind_photo['caption'] ) : ?>
						<figcaption class="about-hero__caption"><?php echo esc_html( $clind_photo['caption'] ); ?></figcaption>
					<?php endif; ?>
				</figure>
			<?php endforeach; ?>

			<?php clind_doodle( 'face', 'about-hero__doodle about-hero__doodle--face' ); ?>

			<button class="about-hero__shuffle" type="button" data-about-shuffle>
				<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M16 3h5v5M4 20L21 3M21 16v5h-5M15 15l6 6M4 4l5 5" /></svg>
				<?php esc_html_e( 'Blanda bilderna', 'carolinelindstrom' ); ?>
			</button>
		</div>
	</div>
</section>
