<?php
/*
The footer's three items: the privacy page, the preferences dialog and back to
the top (components/footer-nav.css). Each has its text and a ring with an icon.
The text names the item; the ring and icon are decorative.
*/

$clind_privacy_url = clind_privacy_url();
?>
<nav class="footer-nav" aria-label="<?php esc_attr_e( 'Sidfot', 'carolinelindstrom' ); ?>">
	<?php if ( $clind_privacy_url ) : ?>
		<a class="footer-nav__item" href="<?php echo esc_url( $clind_privacy_url ); ?>">
			<?php esc_html_e( 'Integritetspolicy', 'carolinelindstrom' ); ?>
			<span class="footer-nav__icon" aria-hidden="true">
				<svg class="footer-nav__ring" width="44" height="44" viewBox="0 0 44 44" focusable="false"><circle cx="22" cy="22" r="20" /></svg>
				<svg class="footer-nav__glyph" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" focusable="false"><path d="M12 3l7 3v5c0 4.5-3 8.5-7 10-4-1.5-7-5.5-7-10V6l7-3z" /><path d="M9.5 12l1.8 1.8L15 10" /></svg>
			</span>
		</a>
	<?php endif; ?>

	<?php /* Hidden until preferences.js runs, since it needs JavaScript */ ?>
	<button class="footer-nav__item" type="button" aria-haspopup="dialog" data-prefs-open hidden>
		<?php esc_html_e( 'Inställningar', 'carolinelindstrom' ); ?>
		<span class="footer-nav__icon" aria-hidden="true">
			<svg class="footer-nav__ring" width="44" height="44" viewBox="0 0 44 44" focusable="false"><circle cx="22" cy="22" r="20" /></svg>
			<svg class="footer-nav__glyph" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" focusable="false"><path d="M4 8h9M17 8h3M4 16h3M11 16h9" /><circle cx="15" cy="8" r="2" /><circle cx="9" cy="16" r="2" /></svg>
		</span>
	</button>

	<?php
	/*
	To <body>, not <main>: the header's scroll-padding would stop the scroll short
	of the top. The next Tab then starts from the skip link, as on a fresh page.
	*/
	?>
	<a class="footer-nav__item" href="#top">
		<?php esc_html_e( 'Till toppen', 'carolinelindstrom' ); ?>
		<span class="footer-nav__icon" aria-hidden="true">
			<svg class="footer-nav__ring" width="44" height="44" viewBox="0 0 44 44" focusable="false"><circle cx="22" cy="22" r="20" /></svg>
			<svg class="footer-nav__glyph" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" focusable="false"><path d="M12 19V5M6 11l6-6 6 6" /></svg>
		</span>
	</a>
</nav>
