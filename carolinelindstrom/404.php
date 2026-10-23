<?php
/* --------------------------------------------------
 * ---------------------- 404 -----------------------
 * -------------------------------------------------- */

get_header();
?>

<div class="container section">
	<header class="entry-header">
		<p class="eyebrow">404</p>
		<h1 class="entry-title"><?php esc_html_e( 'Sidan finns inte.', 'carolinelindstrom' ); ?></h1>
	</header>

	<p class="lead"><?php esc_html_e( 'Länken kan vara gammal, eller så har sidan flyttat.', 'carolinelindstrom' ); ?></p>
	<p class="lead"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Till startsidan', 'carolinelindstrom' ); ?></a></p>
</div>

<?php
get_footer();
