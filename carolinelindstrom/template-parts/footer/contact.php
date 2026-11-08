<?php
/*
"Här hittar du mig": email, social links and the latest favourite on Spotify
(components/contact.css). The email address and Spotify link come from the
Customizer (inc/contact.php).
The email is split into one span per letter for the wave on hover; the link's
aria-label gives screen readers the whole address instead of single letters.
*/

$clind_email  = get_theme_mod( 'clind_contact_email', '' );
$clind_player = clind_spotify_player();

/*
Public profiles: name, address, icon and the pill's colour (contact.css).
Wide screens show the name and an arrow; phones show the icon, with the name
kept for screen readers. The fill is a real element, not ::before: axe can't
measure contrast through a pseudo-element. The label is there twice: light, and
a dark copy (.contact__ink, hidden from screen readers) that is wiped in at the
fill's pace, so the colour changes exactly where the fill passes.
*/
$clind_socials = array(
	array( 'LinkedIn', 'https://www.linkedin.com/in/caroline-lindstr%C3%B6m-328a33141/', 'linkedin', 'sky' ),
	array( 'Instagram', 'https://www.instagram.com/carrolinelindstrom/', 'instagram', 'yellow' ),
	array( 'GitHub', 'https://github.com/CarolineLind', 'github', 'lilac' ),
);
?>
<div class="contact">
	<?php /* Decorative dots and twinkling stars behind the content */ ?>
	<?php get_template_part( 'template-parts/sky' ); ?>

	<div class="contact__inner container">
		<div class="contact__main">
			<h2 class="contact__title"><?php esc_html_e( 'Här hittar du mig', 'carolinelindstrom' ); ?></h2>

			<?php if ( $clind_email ) : ?>
				<a class="contact__email" href="<?php echo esc_url( 'mailto:' . $clind_email ); ?>">
					<?php
					/*
					The name comes from the link's own text, not aria-label, so it always matches what's
					on screen (WCAG 2.5.3, for voice control). The per-letter spans below are hidden from
					screen readers, which would otherwise spell the address out one letter at a time.
					*/
					?>
					<span class="sr-only"><?php echo esc_html( $clind_email ); ?></span>
					<span class="contact__email-text" aria-hidden="true">
						<?php
						/* Two parts, before and from the @, so a long address wraps there and not mid-word */
						$clind_at    = (int) mb_strpos( $clind_email, '@' );
						$clind_parts = array( mb_substr( $clind_email, 0, $clind_at ), mb_substr( $clind_email, $clind_at ) );
						$clind_i     = 0;
						foreach ( $clind_parts as $clind_part ) :
							?>
							<span class="contact__email-part">
								<?php foreach ( mb_str_split( $clind_part ) as $clind_char ) : ?>
									<span class="contact__char<?php echo '@' === $clind_char ? ' contact__char--at' : ''; ?>" style="--i: <?php echo absint( $clind_i++ ); ?>"><?php echo esc_html( $clind_char ); ?></span>
								<?php endforeach; ?>
							</span>
						<?php endforeach; ?>
					</span>
				</a>
			<?php endif; ?>

			<ul class="contact__socials">
				<?php foreach ( $clind_socials as $clind_social ) : ?>
					<?php
					ob_start();
					clind_social_icon( $clind_social[2] );
					?>
					<span class="contact__social-name"><?php echo esc_html( $clind_social[0] ); ?></span>
					<svg class="contact__arrow" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M5 12h14M13 6l6 6-6 6" /></svg>
					<?php $clind_label = ob_get_clean(); ?>
					<li>
						<a class="contact__social contact__social--<?php echo esc_attr( $clind_social[3] ); ?>" href="<?php echo esc_url( $clind_social[1] ); ?>">
							<span class="contact__fill" aria-hidden="true"></span>
							<?php /* Escaped above, built from esc_html(), esc_attr() and literal SVG */ ?>
							<span class="contact__label"><?php echo $clind_label; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
							<span class="contact__ink" aria-hidden="true"><?php echo $clind_label; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>

		<?php if ( $clind_player ) : ?>
			<div class="contact__music">
				<h2 class="contact__subtitle"><?php esc_html_e( 'Senaste favoriten', 'carolinelindstrom' ); ?></h2>
				<?php
				/* Built and escaped in clind_spotify_player(), held back by clind_hold_back_embeds() */
				echo $clind_player; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				?>
			</div>
		<?php endif; ?>
	</div>
</div>
