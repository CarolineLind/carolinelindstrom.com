<!-- START footer.php -->
</main>

<footer class="site-footer">
	<?php get_template_part( 'template-parts/footer/contact' ); ?>

	<?php /* The bottom bar: "Här hittar du mig" slides up and uncovers it (layout/footer.css) */ ?>
	<div class="site-footer__bar">
		<div class="site-footer__inner container">
			<span class="site-footer__id">
				<?php clind_logo_mark(); ?>
				<span class="site-footer__copy mono muted">&copy; <?php echo esc_html( get_bloginfo( 'name' ) . ' ' . wp_date( 'Y' ) ); ?></span>
			</span>

			<?php get_template_part( 'template-parts/footer/footer-nav' ); ?>
		</div>
	</div>
</footer>

<?php get_template_part( 'template-parts/preferences/dialog' ); ?>

<?php wp_footer(); ?>
</body>
</html>
