<?php
/* --------------------------------------------------
 * ------------------- START PAGE -------------------
 * -------------------------------------------------- */

/*
The start page's sections (template-parts/front/): the markup is the theme's,
the words are Caroline's. Why they aren't built in the block editor:
docs/decisions/0007-start-hero.md.
	Hero: heading, text and link in Appearance → Customize → "Startsidan";
	the portrait is the start page's featured image.
	Banner: the keywords, in the same Customize section.
	Läs vidare: the heading in Customize; the notebooks are the pages in the
	menu at "Läs vidare på startsidan", each with its excerpt and the words on
	its cover (a box in the page editor, "Ord på bokomslaget").
*/

/* The words as they were written for the design; used until something else is saved */
function clind_hero_defaults() {
	return array(
		'title'      => __( 'Vad roligt att du kikar in.', 'carolinelindstrom' ),
		'text'       => __( 'Jag heter Caroline och jobbar som frontendutvecklare med fokus på tillgänglighet, prestanda och användarvänlighet. Den här sidan är dels en portfölj, dels en lekplats där jag får testa idéer utan ramar. Hoppas du gillar!', 'carolinelindstrom' ),
		'link_text'  => __( 'Läs mer om mig', 'carolinelindstrom' ),
		'words'      => "Tillgänglighet\nPrestanda\nAnvändarvänlighet\nHTML\nCSS\nJavaScript",
		'read_title' => __( 'Läs vidare', 'carolinelindstrom' ),
	);
}

function clind_hero_customize( $wp_customize ) {
	$defaults = clind_hero_defaults();

	$wp_customize->add_section(
		'clind_hero',
		array(
			'title'       => __( 'Startsidan', 'carolinelindstrom' ),
			'description' => __( 'Texterna på startsidan. Porträttet är startsidans utvalda bild, och korten under "Läs vidare" är sidorna i menyn "Läs vidare på startsidan" med sina utdrag.', 'carolinelindstrom' ),
			'priority'    => 150,
		)
	);

	$fields = array(
		'clind_hero_title'     => array( __( 'Rubrik (sidans h1)', 'carolinelindstrom' ), 'text', $defaults['title'], 'sanitize_text_field' ),
		'clind_hero_text'      => array( __( 'Text', 'carolinelindstrom' ), 'textarea', $defaults['text'], 'sanitize_textarea_field' ),
		'clind_hero_link_text' => array( __( 'Länktext', 'carolinelindstrom' ), 'text', $defaults['link_text'], 'sanitize_text_field' ),
		'clind_hero_link_page' => array( __( 'Länken går till', 'carolinelindstrom' ), 'dropdown-pages', 0, 'absint' ),
		'clind_banner_words'   => array( __( 'Nyckelord i bandet, ett per rad', 'carolinelindstrom' ), 'textarea', $defaults['words'], 'sanitize_textarea_field' ),
		'clind_read_title'     => array( __( 'Rubrik över korten', 'carolinelindstrom' ), 'text', $defaults['read_title'], 'sanitize_text_field' ),
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
				'section' => 'clind_hero',
				'type'    => $field[1],
			)
		);
	}
}
add_action( 'customize_register', 'clind_hero_customize' );

/*
Everything the hero shows, ready to escape and print. The link goes to the chosen
page, or to "Om mig" (slug om-mig) until one is chosen; an empty link text, or no
page to link to, leaves the link out.
*/
function clind_hero_content() {
	$defaults = clind_hero_defaults();

	$page_id = absint( get_theme_mod( 'clind_hero_link_page', 0 ) );
	if ( ! $page_id ) {
		$about   = get_page_by_path( 'om-mig' );
		$page_id = $about ? $about->ID : 0;
	}

	return array(
		'title'     => get_theme_mod( 'clind_hero_title', $defaults['title'] ),
		'text'      => get_theme_mod( 'clind_hero_text', $defaults['text'] ),
		'link_text' => get_theme_mod( 'clind_hero_link_text', $defaults['link_text'] ),
		'link_url'  => $page_id && 'publish' === get_post_status( $page_id ) ? get_permalink( $page_id ) : '',
	);
}

/* The banner's keywords: one per line in Customize, empty lines skipped */
function clind_banner_words() {
	$defaults = clind_hero_defaults();
	$words    = preg_split( '/\R/', (string) get_theme_mod( 'clind_banner_words', $defaults['words'] ) );
	return array_values( array_filter( array_map( 'trim', $words ), 'strlen' ) );
}

/*
The "Läs vidare" cards: the published pages in the menu at "Läs vidare på
startsidan", in the menu's order. Until a menu is set there: Om mig,
Tillgänglighet, Prestanda and Frontend lead, if they're published.
Each card: title (the menu label), link, description (the page's excerpt) and
up to three words for the notebook's cover.
*/
function clind_read_more_items() {
	$pages     = array();
	$locations = get_nav_menu_locations();

	if ( ! empty( $locations['read-more'] ) ) {
		foreach ( (array) wp_get_nav_menu_items( $locations['read-more'] ) as $item ) {
			if ( 'post_type' === $item->type && 'publish' === get_post_status( (int) $item->object_id ) ) {
				$pages[] = array( (int) $item->object_id, $item->title );
			}
		}
	} else {
		foreach ( array( 'om-mig', 'tillganglighet', 'prestanda', 'frontend-lead' ) as $slug ) {
			$page = get_page_by_path( $slug );
			if ( $page && 'publish' === $page->post_status ) {
				$pages[] = array( $page->ID, get_the_title( $page ) );
			}
		}
	}

	return array_map(
		function ( $page ) {
			return array(
				'title' => $page[1],
				'url'   => get_permalink( $page[0] ),
				'text'  => has_excerpt( $page[0] ) ? get_the_excerpt( $page[0] ) : '',
				'words' => clind_book_words( $page[0] ),
			);
		},
		$pages
	);
}

/* ------------------ COVER WORDS ------------------- */

/*
The words on a page's notebook in "Läs vidare", e.g. "Biografi, Kompetenser,
Intressen". A small box in the page editor's sidebar; stored as post meta
(underscore first, so it doesn't show among the custom fields).
*/
function clind_book_words( $page_id ) {
	$words = explode( ',', (string) get_post_meta( $page_id, '_clind_book_words', true ) );
	return array_slice( array_values( array_filter( array_map( 'trim', $words ), 'strlen' ) ), 0, 3 );
}

function clind_book_words_box() {
	add_meta_box( 'clind_book_words', __( 'Ord på bokomslaget', 'carolinelindstrom' ), 'clind_book_words_field', 'page', 'side' );
}
add_action( 'add_meta_boxes', 'clind_book_words_box' );

function clind_book_words_field( $post ) {
	wp_nonce_field( 'clind_book_words', 'clind_book_words_nonce' );
	?>
	<label for="clind-book-words"><?php esc_html_e( 'Upp till tre ord, med kommatecken emellan. Visas på sidans anteckningsbok under "Läs vidare" på startsidan.', 'carolinelindstrom' ); ?></label>
	<input type="text" id="clind-book-words" name="clind_book_words" class="widefat" value="<?php echo esc_attr( get_post_meta( $post->ID, '_clind_book_words', true ) ); ?>">
	<?php
}

function clind_book_words_save( $post_id ) {
	if ( ! isset( $_POST['clind_book_words_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['clind_book_words_nonce'] ), 'clind_book_words' ) ) {
		return;
	}
	if ( wp_is_post_autosave( $post_id ) || ! current_user_can( 'edit_page', $post_id ) ) {
		return;
	}
	$words = isset( $_POST['clind_book_words'] ) ? sanitize_text_field( wp_unslash( $_POST['clind_book_words'] ) ) : '';
	update_post_meta( $post_id, '_clind_book_words', $words );
}
add_action( 'save_post_page', 'clind_book_words_save' );
