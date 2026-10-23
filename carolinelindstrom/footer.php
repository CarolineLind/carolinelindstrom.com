<!-- START footer.php -->
</main>

<footer class="site-footer">
	<div class="site-footer__inner container">
		<span class="site-footer__copy mono muted">&copy; <?php echo esc_html( get_bloginfo( 'name' ) . ' ' . wp_date( 'Y' ) ); ?></span>

		<?php
		wp_nav_menu(
			array(
				'theme_location'       => 'footer',
				'container'            => 'nav',
				'container_aria_label' => __( 'Sidfotsmeny', 'carolinelindstrom' ),
				'menu_class'           => 'footer-list',
				'depth'                => 1,
				'fallback_cb'          => false,
			)
		);
		?>

		<?php /* Hidden until preferences.js runs, since it needs JavaScript */ ?>
		<button type="button" class="site-footer__settings" data-prefs-open hidden><?php esc_html_e( 'Inställningar', 'carolinelindstrom' ); ?></button>

		<a class="totop" href="#main"><?php esc_html_e( 'Till toppen', 'carolinelindstrom' ); ?></a>
	</div>
</footer>

<?php get_template_part( 'template-parts/preferences/dialog' ); ?>

<?php wp_footer(); ?>
</body>
</html>
