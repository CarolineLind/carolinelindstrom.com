<?php
/*
Menu button and main menu. The button needs JavaScript (assets/js/header.js),
so it's hidden without it, and the menu then stays open on phones.
Each link gets a label that reserves its bold width and sparkles (clind_nav_item_title), and the menu a sliding line (.nav-ind).
*/
?>
<button class="menu-btn" type="button" aria-expanded="false" aria-controls="site-nav">
	<svg class="menu-btn__icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" aria-hidden="true"><path class="menu-btn__line menu-btn__line--1" d="M4 7h16" /><path class="menu-btn__line menu-btn__line--2" d="M4 12h16" /><path class="menu-btn__line menu-btn__line--3" d="M4 17h16" /></svg>
	<?php esc_html_e( 'Meny', 'carolinelindstrom' ); ?>
</button>

<nav id="site-nav" class="nav" aria-label="<?php esc_attr_e( 'Huvudmeny', 'carolinelindstrom' ); ?>">
	<?php
	wp_nav_menu(
		array(
			'theme_location' => 'primary',
			'container'      => false,
			'menu_class'     => 'nav-list',
			'depth'          => 1,
			'fallback_cb'    => false,
		)
	);
	?>
	<span class="nav-ind" aria-hidden="true"></span>
</nav>

<?php
/*
After following a menu link: mark the link that was current on the last page
(data-nav-from, set by clind_handoff_script) before the menu is painted. It looks
current for one frame, then header.js lets the current link take over.
*/
?>
<script>
(function () {
	var root = document.documentElement;
	var from = root.getAttribute('data-nav-from');
	var link = from !== null && document.querySelectorAll('#site-nav .navlink')[from];
	if (link && !link.hasAttribute('aria-current')) {
		link.classList.add('navlink--was-current');
		root.classList.add('nav-handoff');
	}
})();
</script>
