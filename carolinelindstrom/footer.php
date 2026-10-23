<!-- START footer.php -->
</main>

<footer class="site-footer">
	<div class="site-footer__inner container">
		<span class="site-footer__copy mono muted">&copy; <?php echo esc_html( get_bloginfo( 'name' ) . ' ' . wp_date( 'Y' ) ); ?></span>

		<?php
		wp_nav_menu(
			array(
				'theme_location' => 'footer',
				'container'      => 'nav',
				'container_attr' => array( 'aria-label' => __( 'Sidfotsmeny', 'carolinelindstrom' ) ),
				'menu_class'     => 'footer-list',
				'depth'          => 1,
				'fallback_cb'    => false,
			)
		);
		?>

		<a class="totop" href="#main"><?php esc_html_e( 'Till toppen', 'carolinelindstrom' ); ?></a>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
