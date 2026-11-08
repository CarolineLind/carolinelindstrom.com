<?php
/*
The start page hero (components/start-hero.css, js/start-hero.js): a sheet of
lined paper with the page's h1, a short text and a link, the portrait with
two colour shapes behind it, and doodles that rise with the scroll
(hero-shapes.php). The words come from Customize → "Startsidan"
(inc/start-page.php), the portrait is the start page's featured image.
Runs inside the loop in front-page.php.
*/

$clind_hero = clind_hero_content();
?>
<div class="start-hero">
	<?php get_template_part( 'template-parts/sky' ); ?>
	<?php get_template_part( 'template-parts/front/hero-shapes' ); ?>

	<div class="start-hero__inner container">
		<div class="start-hero__stage">
			<?php /* The shadow is its own layer, so following the mouse only moves it (transform), never repaints it */ ?>
			<div class="start-hero__shadow" aria-hidden="true"></div>

			<div class="start-hero__paper">
				<?php /* Lines, margin and glare on their own layer: axe can't check contrast through a gradient on the text's own parent */ ?>
				<div class="start-hero__lines" aria-hidden="true"></div>

				<h1 class="start-hero__title"><?php echo esc_html( $clind_hero['title'] ); ?></h1>

				<?php if ( $clind_hero['text'] ) : ?>
					<p class="start-hero__text"><?php echo esc_html( $clind_hero['text'] ); ?></p>
				<?php endif; ?>

				<?php if ( $clind_hero['link_text'] && $clind_hero['link_url'] ) : ?>
					<p class="start-hero__more">
						<a class="start-hero__link" href="<?php echo esc_url( $clind_hero['link_url'] ); ?>">
							<?php echo esc_html( $clind_hero['link_text'] ); ?>
							<svg class="start-hero__arrow" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M5 12h14M13 6l6 6-6 6" /></svg>
						</a>
					</p>
				<?php endif; ?>
			</div>
		</div>

		<?php if ( has_post_thumbnail() ) : ?>
			<div class="start-hero__photo">
				<?php
				/* The largest image on the first screen: never lazy, fetched first (LCP). sizes follows --photo-col, --photo-max-tablet and --photo-max-mobile */
				the_post_thumbnail(
					'large',
					array(
						'class'         => 'start-hero__img',
						'loading'       => false,
						'fetchpriority' => 'high',
						'sizes'         => '(width <= 720px) 260px, (width <= 999px) 320px, 360px',
					)
				);
				?>
			</div>
		<?php endif; ?>
	</div>
</div>
