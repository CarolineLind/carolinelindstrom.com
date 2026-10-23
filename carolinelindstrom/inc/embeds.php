<?php
/* --------------------------------------------------
 * --------------------- EMBEDS ---------------------
 * -------------------------------------------------- */

/*
Content from other sites (Spotify, YouTube etc.) may only load after the visitor
has allowed it in the preferences dialog. Every iframe from another domain is
replaced on the server with a card, and the original iframe is kept in an inert
<template> that the browser never loads. assets/js/preferences.js swaps it in
when third-party content is allowed.
*/

function clind_hold_back_embeds( $content ) {
	if ( is_admin() || false === stripos( $content, '<iframe' ) ) {
		return $content;
	}
	return preg_replace_callback( '#<iframe\b[^>]*>.*?</iframe>#is', 'clind_embed_placeholder', $content );
}
add_filter( 'the_content', 'clind_hold_back_embeds', 99 );

/* Card + held-back iframe for one embed */
function clind_embed_placeholder( $matches ) {
	$tags = new WP_HTML_Tag_Processor( $matches[0] );
	if ( ! $tags->next_tag( 'iframe' ) ) {
		return $matches[0];
	}

	$src  = (string) $tags->get_attribute( 'src' );
	$host = (string) wp_parse_url( $src, PHP_URL_HOST );

	/* Our own iframes aren't third-party */
	if ( '' === $host || wp_parse_url( home_url(), PHP_URL_HOST ) === $host ) {
		return $matches[0];
	}

	$service = clind_embed_service( $src, $host );

	/* An iframe needs a title for screen readers; many embed codes leave it out */
	if ( ! $tags->get_attribute( 'title' ) ) {
		$tags->set_attribute( 'title', $service['title'] );
	}

	/* Same height as the player, so nothing jumps when it's swapped in */
	$height = $tags->get_attribute( 'height' );
	$style  = is_numeric( $height ) ? sprintf( ' style="--embed-height: %dpx"', absint( $height ) ) : '';

	return sprintf(
		'<div class="embed-consent"%1$s><div class="embed-consent__fallback"><p class="embed-consent__text">%2$s</p><p><a class="embed-consent__link" href="%3$s" target="_blank" rel="noopener">%4$s<span class="sr-only"> %5$s</span></a></p></div><template>%6$s</template></div>',
		$style,
		/* translators: %s: what was hidden, e.g. "Spotify-spelaren" */
		esc_html( sprintf( __( 'Du har valt att inte visa %s.', 'carolinelindstrom' ), $service['name'] ) ),
		esc_url( $service['url'] ),
		esc_html( $service['link'] ),
		esc_html__( '(öppnas i ny flik)', 'carolinelindstrom' ),
		$tags->get_updated_html()
	);
}

/* Names, titles and a direct link for the services the site embeds */
function clind_embed_service( $src, $host ) {
	$path = (string) wp_parse_url( $src, PHP_URL_PATH );

	if ( 'open.spotify.com' === $host ) {
		return array(
			'name'  => __( 'Spotify-spelaren', 'carolinelindstrom' ),
			'title' => __( 'Spotify-spelare', 'carolinelindstrom' ),
			'link'  => __( 'Lyssna på Spotify', 'carolinelindstrom' ),
			'url'   => 'https://open.spotify.com' . preg_replace( '#^/embed#', '', $path ),
		);
	}

	if ( preg_match( '#(^|\.)youtube(-nocookie)?\.com$#', $host ) && preg_match( '#/embed/([\w-]+)#', $path, $id ) ) {
		return array(
			'name'  => __( 'YouTube-videon', 'carolinelindstrom' ),
			'title' => __( 'YouTube-video', 'carolinelindstrom' ),
			'link'  => __( 'Titta på YouTube', 'carolinelindstrom' ),
			'url'   => 'https://www.youtube.com/watch?v=' . $id[1],
		);
	}

	if ( 'player.vimeo.com' === $host && preg_match( '#/video/(\d+)#', $path, $id ) ) {
		return array(
			'name'  => __( 'Vimeo-videon', 'carolinelindstrom' ),
			'title' => __( 'Vimeo-video', 'carolinelindstrom' ),
			'link'  => __( 'Titta på Vimeo', 'carolinelindstrom' ),
			'url'   => 'https://vimeo.com/' . $id[1],
		);
	}

	return array(
		'name'  => __( 'innehållet från en annan sida', 'carolinelindstrom' ),
		/* translators: %s: domain name of the embedded site */
		'title' => sprintf( __( 'Innehåll från %s', 'carolinelindstrom' ), $host ),
		/* translators: %s: domain name of the embedded site */
		'link'  => sprintf( __( 'Öppna på %s', 'carolinelindstrom' ), $host ),
		'url'   => $src,
	);
}
