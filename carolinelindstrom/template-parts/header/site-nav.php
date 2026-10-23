<?php
/*
Logo and main menu.
Still to come (components): light/dark and motion toggles, mobile menu button, sliding menu indicator.
*/
?>
<nav class="nav container" aria-label="<?php esc_attr_e( 'Huvudmeny', 'carolinelindstrom' ); ?>">
	<?php clind_logo(); ?>

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
</nav>
