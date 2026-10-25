<?php
/* --------------------------------------------------
 * -------------------- CONTACT ---------------------
 * -------------------------------------------------- */

/*
"Här hittar du mig" at the top of the footer (template-parts/footer/contact.php).
The email address and the Spotify link are set under Appearance → Customize →
"Kontakt i sidfoten", so a private address never ends up in this public repo
and the favourite can change without code. Empty fields leave their part out.
*/

function clind_contact_customize( $wp_customize ) {
	$wp_customize->add_section(
		'clind_contact',
		array(
			'title'    => __( 'Kontakt i sidfoten', 'carolinelindstrom' ),
			'priority' => 160,
		)
	);

	$wp_customize->add_setting(
		'clind_contact_email',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_email',
		)
	);
	$wp_customize->add_control(
		'clind_contact_email',
		array(
			'label'   => __( 'E-postadress', 'carolinelindstrom' ),
			'section' => 'clind_contact',
			'type'    => 'email',
		)
	);

	$wp_customize->add_setting(
		'clind_spotify_url',
		array(
			'default'           => '',
			'sanitize_callback' => 'clind_sanitize_spotify_url',
		)
	);
	$wp_customize->add_control(
		'clind_spotify_url',
		array(
			'label'       => __( 'Senaste favoriten (Spotify-länk)', 'carolinelindstrom' ),
			'description' => __( 'Länken från Dela → Kopiera länk, till ett album, en spellista eller en låt.', 'carolinelindstrom' ),
			'section'     => 'clind_contact',
			'type'        => 'url',
		)
	);
}
add_action( 'customize_register', 'clind_contact_customize' );

/* Only Spotify links the player can show; anything else is dropped */
function clind_sanitize_spotify_url( $url ) {
	return clind_spotify_embed_src( $url ) ? esc_url_raw( $url ) : '';
}

/*
A Spotify share link → the player's address, e.g.
https://open.spotify.com/album/ID?si=… → https://open.spotify.com/embed/album/ID
Empty if it isn't a link the player can show.
*/
function clind_spotify_embed_src( $url ) {
	if ( ! preg_match( '#^https://open\.spotify\.com/(?:intl-[a-z-]+/)?(album|playlist|track|artist|episode|show)/([A-Za-z0-9]+)#', (string) $url, $parts ) ) {
		return '';
	}
	return 'https://open.spotify.com/embed/' . $parts[1] . '/' . $parts[2];
}

/*
The Spotify player, held back until the visitor allows third-party content,
like embeds in the content (inc/embeds.php). Empty if no link is set.
*/
function clind_spotify_player() {
	$src = clind_spotify_embed_src( get_theme_mod( 'clind_spotify_url', '' ) );
	if ( ! $src ) {
		return '';
	}
	$iframe = sprintf(
		'<iframe src="%s" width="100%%" height="352" allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture" loading="lazy"></iframe>',
		esc_url( $src )
	);
	return clind_hold_back_embeds( $iframe );
}

/*
A brand icon for the social links, inline so it takes the link's colour.
The shapes come from Bootstrap Icons (MIT, assets/icons/social/MIT-bootstrap-icons.txt);
the files there are the single source, and only their path is used.
*/
function clind_social_icon( $name ) {
	$file = get_template_directory() . '/assets/icons/social/' . sanitize_file_name( $name ) . '.svg';
	if ( ! is_readable( $file ) ) {
		return;
	}
	$tags = new WP_HTML_Tag_Processor( (string) file_get_contents( $file ) ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- a local theme file, not a URL
	if ( ! $tags->next_tag( 'path' ) ) {
		return;
	}
	printf(
		'<svg class="contact__icon" width="20" height="20" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true" focusable="false"><path d="%s"/></svg>',
		esc_attr( (string) $tags->get_attribute( 'd' ) )
	);
}
