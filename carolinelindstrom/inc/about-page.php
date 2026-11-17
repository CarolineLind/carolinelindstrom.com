<?php
/* --------------------------------------------------
 * ------------------- ABOUT PAGE -------------------
 * -------------------------------------------------- */

/*
The hero at the top of "Om mig" (page-om-mig.php, template-parts/about/hero.php):
the markup is the theme's, the words and pictures are Caroline's, set in
Appearance → Customize → "Om mig". Same model as the start page hero
(docs/decisions/0007-start-hero.md); the rest of the page is block editor content.
*/

/* The words as they were written for the design; used until something else is saved */
function clind_about_defaults() {
	return array(
		'title'    => __( 'Vem är jag då?', 'carolinelindstrom' ),
		'text'     => __( 'Tjej från Färgelanda, Dalsland, som från en ung ålder blev intresserad av internet och den digitala världen.', 'carolinelindstrom' ),
		'captions' => array(
			__( 'liten Caroline', 'carolinelindstrom' ),
			__( 'Färgelanda', 'carolinelindstrom' ),
			__( 'idag', 'carolinelindstrom' ),
		),
	);
}

function clind_about_customize( $wp_customize ) {
	$defaults = clind_about_defaults();

	$wp_customize->add_section(
		'clind_about',
		array(
			'title'       => __( 'Om mig', 'carolinelindstrom' ),
			'description' => __( 'Överst på sidan Om mig: rubriken, texten och de tre polaroidbilderna. Bildernas alt-text skrivs i mediebiblioteket; en bild utan alt-text räknas som dekoration.', 'carolinelindstrom' ),
			'priority'    => 151,
		)
	);

	$fields = array(
		'clind_about_title' => array( __( 'Rubrik (sidans h1)', 'carolinelindstrom' ), 'text', $defaults['title'], 'sanitize_text_field' ),
		'clind_about_text'  => array( __( 'Text', 'carolinelindstrom' ), 'textarea', $defaults['text'], 'sanitize_textarea_field' ),
	);

	foreach ( $fields as $id => $field ) {
		$wp_customize->add_setting(
			$id,
			array(
				'default'           => $field[2],
				'sanitize_callback' => $field[3],
			)
		);
		$wp_customize->add_control(
			$id,
			array(
				'label'   => $field[0],
				'section' => 'clind_about',
				'type'    => $field[1],
			)
		);
	}

	/* One image and one caption per polaroid, back to front */
	foreach ( array( 1, 2, 3 ) as $n ) {
		$wp_customize->add_setting(
			'clind_about_image_' . $n,
			array(
				'default'           => 0,
				'sanitize_callback' => 'absint',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Media_Control(
				$wp_customize,
				'clind_about_image_' . $n,
				array(
					/* translators: %d: the polaroid's number, 1 to 3 */
					'label'     => sprintf( __( 'Bild %d', 'carolinelindstrom' ), $n ),
					'section'   => 'clind_about',
					'mime_type' => 'image',
				)
			)
		);

		$wp_customize->add_setting(
			'clind_about_caption_' . $n,
			array(
				'default'           => $defaults['captions'][ $n - 1 ],
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			'clind_about_caption_' . $n,
			array(
				/* translators: %d: the polaroid's number, 1 to 3 */
				'label'   => sprintf( __( 'Bildtext %d', 'carolinelindstrom' ), $n ),
				'section' => 'clind_about',
				'type'    => 'text',
			)
		);
	}
}
add_action( 'customize_register', 'clind_about_customize' );

/* Everything the hero shows, ready to escape and print. image is an attachment ID, 0 until one is chosen */
function clind_about_content() {
	$defaults = clind_about_defaults();
	$photos   = array();

	foreach ( array( 1, 2, 3 ) as $n ) {
		$image_id = absint( get_theme_mod( 'clind_about_image_' . $n, 0 ) );
		$photos[] = array(
			'image'   => $image_id && wp_attachment_is_image( $image_id ) ? $image_id : 0,
			'caption' => get_theme_mod( 'clind_about_caption_' . $n, $defaults['captions'][ $n - 1 ] ),
		);
	}

	return array(
		'title'  => get_theme_mod( 'clind_about_title', $defaults['title'] ),
		'text'   => get_theme_mod( 'clind_about_text', $defaults['text'] ),
		'photos' => $photos,
	);
}
