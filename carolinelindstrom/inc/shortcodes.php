<?php
/* --------------------------------------------------
 * --------------------- SHORTCODES ----------------------
 * -------------------------------------------------- */

function clind__sky_shortcode() {

	ob_start();
	?>

	<div class="contact__sky" aria-hidden="true">
		<?php for ( $clind_star = 1; $clind_star <= 6; $clind_star++ ) : ?>
			<span class="contact__stars contact__stars--<?php echo absint( $clind_star ); ?>"></span>
		<?php endfor; ?>
	</div>

	<?php
	return ob_get_clean();
}

add_shortcode( 'sky', 'clind__sky_shortcode' );
