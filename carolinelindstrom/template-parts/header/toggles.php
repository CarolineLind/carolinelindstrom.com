<?php
/*
Quick switches for motion and light/dark. They save to the same choices as the
preferences dialog (window.clindPrefs, assets/js/header.js). Shown only when
JavaScript runs.
Icon and text say what pressing does. Both texts are in the markup, stacked, and
CSS shows the right one from data-motion/data-theme on <html>: right from the
first paint, and the button keeps the width of the longer text, so nothing shifts.
*/
?>
<div class="toggles">
	<button class="toggle toggle--motion" type="button" data-toggle="motion">
		<svg class="toggle__pause" width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><rect x="6" y="5" width="4" height="14" rx="1.2" /><rect x="14" y="5" width="4" height="14" rx="1.2" /></svg>
		<svg class="toggle__play" width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M8 5.5v13l11-6.5z" /></svg>
		<span class="toggle__label">
			<span class="toggle__text toggle__text--pause"><?php esc_html_e( 'Pausa rörelse', 'carolinelindstrom' ); ?></span>
			<span class="toggle__text toggle__text--play"><?php esc_html_e( 'Spela rörelse', 'carolinelindstrom' ); ?></span>
		</span>
	</button>
	<button class="toggle toggle--theme" type="button" data-toggle="theme">
		<svg class="toggle__sun" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="12" cy="12" r="4" /><path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4" /></svg>
		<svg class="toggle__moon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9z" /></svg>
		<span class="toggle__label">
			<span class="toggle__text toggle__text--light"><?php esc_html_e( 'Ljust läge', 'carolinelindstrom' ); ?></span>
			<span class="toggle__text toggle__text--dark"><?php esc_html_e( 'Mörkt läge', 'carolinelindstrom' ); ?></span>
		</span>
	</button>
</div>
