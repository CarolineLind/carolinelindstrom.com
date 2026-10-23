<?php
/*--------------------------------------------------*/
/*-------------------- CLEANUP ---------------------*/
/*--------------------------------------------------*/

/*
The site is pages only: no posts, comments, search, feeds or archives.
Everything WordPress offers for those is turned off here, so it can't be
used, found or attacked. Lives in the theme since the site only has one theme.
*/

/*-------------------- COMMENTS --------------------*/

function cl_remove_comment_support() {
	foreach ( get_post_types() as $post_type ) {
		remove_post_type_support( $post_type, 'comments' );
		remove_post_type_support( $post_type, 'trackbacks' );
	}
}
add_action( 'init', 'cl_remove_comment_support', 100 );

add_filter( 'comments_open', '__return_false', 20 );
add_filter( 'pings_open', '__return_false', 20 );
add_filter( 'comments_array', '__return_empty_array', 20 );
add_filter( 'get_comments_number', '__return_zero', 20 );

/*---------------------- POSTS ---------------------*/

/* Hide posts and comments in the admin */
function cl_remove_admin_menus() {
	remove_menu_page( 'edit.php' );
	remove_menu_page( 'edit-comments.php' );
	remove_submenu_page( 'options-general.php', 'options-discussion.php' );
}
add_action( 'admin_menu', 'cl_remove_admin_menus', 999 );

/* Send direct visits to those admin screens to the dashboard */
function cl_block_admin_screens() {
	global $pagenow;

	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read only, no action taken on the value
	$post_type = isset( $_GET['post_type'] ) ? sanitize_key( wp_unslash( $_GET['post_type'] ) ) : 'post';
	$is_post_screen = in_array( $pagenow, array( 'edit.php', 'post-new.php' ), true ) && 'post' === $post_type;
	$is_comment_screen = in_array( $pagenow, array( 'edit-comments.php', 'options-discussion.php' ), true );

	if ( $is_post_screen || $is_comment_screen ) {
		wp_safe_redirect( admin_url() );
		exit;
	}
}
add_action( 'admin_init', 'cl_block_admin_screens' );

function cl_remove_dashboard_widgets() {
	remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
	remove_meta_box( 'dashboard_activity', 'dashboard', 'normal' );
}
add_action( 'wp_dashboard_setup', 'cl_remove_dashboard_widgets' );

function cl_remove_admin_bar_items( $wp_admin_bar ) {
	$wp_admin_bar->remove_node( 'comments' );
	$wp_admin_bar->remove_node( 'new-post' );
	$wp_admin_bar->remove_node( 'search' );
}
add_action( 'admin_bar_menu', 'cl_remove_admin_bar_items', 999 );

/*------------- SEARCH, FEEDS, ARCHIVES ------------*/

/* Skip the database query for searches; the 404 below handles the response */
function cl_skip_search_query( $posts, $query ) {
	if ( ! is_admin() && $query->is_main_query() && $query->is_search() ) {
		return array();
	}
	return $posts;
}
add_filter( 'posts_pre_query', 'cl_skip_search_query', 10, 2 );

/* Everything that isn't a page answers with a 404 */
function cl_404_unused_views() {
	$unused = is_search()
		|| is_feed()
		|| is_author()
		|| is_date()
		|| is_category()
		|| is_tag()
		|| is_tax()
		|| is_post_type_archive()
		|| is_singular( 'post' )
		|| ( is_home() && ! is_front_page() );

	if ( $unused ) {
		global $wp_query;
		$wp_query->set_404();
		status_header( 404 );
		nocache_headers();

		/* Otherwise WordPress guesses a "nearby" URL and redirects, e.g. /page/feed/ to /page/feed/feed/ */
		remove_action( 'template_redirect', 'redirect_canonical' );
	}
}
add_action( 'template_redirect', 'cl_404_unused_views', 1 );

/* Attachment pages are thin duplicate pages: their URLs go straight to the file instead */
add_filter( 'pre_option_wp_attachment_pages_enabled', '__return_zero' );

/* Keep the core sitemap to pages only (users would reveal login names) */
function cl_sitemap_providers( $provider, $name ) {
	return in_array( $name, array( 'users', 'taxonomies' ), true ) ? false : $provider;
}
add_filter( 'wp_sitemaps_add_provider', 'cl_sitemap_providers', 10, 2 );

function cl_sitemap_post_types( $post_types ) {
	unset( $post_types['post'] );
	return $post_types;
}
add_filter( 'wp_sitemaps_post_types', 'cl_sitemap_post_types' );

/*--------------------- XML-RPC --------------------*/

/* No pingbacks or remote publishing. Blocking the file itself is done on the server. */
add_filter( 'xmlrpc_enabled', '__return_false' );
add_filter( 'xmlrpc_methods', '__return_empty_array' );

function cl_remove_pingback_header( $headers ) {
	unset( $headers['X-Pingback'] );
	return $headers;
}
add_filter( 'wp_headers', 'cl_remove_pingback_header' );

/*---------------------- USERS ---------------------*/

/* The user endpoints reveal login names; only logged-in editors need them */
function cl_protect_user_endpoints( $result, $server, $request ) {
	if ( 0 === strpos( $request->get_route(), '/wp/v2/users' ) && ! current_user_can( 'edit_posts' ) ) {
		return new WP_Error( 'rest_forbidden', __( 'Inte tillåtet.', 'carolinelindstrom' ), array( 'status' => rest_authorization_required_code() ) );
	}
	return $result;
}
add_filter( 'rest_pre_dispatch', 'cl_protect_user_endpoints', 10, 3 );

/* oEmbed responses include the author's name and URL; drop them */
function cl_oembed_remove_author( $data ) {
	unset( $data['author_name'], $data['author_url'] );
	return $data;
}
add_filter( 'oembed_response_data', 'cl_oembed_remove_author' );

/*---------------------- HEAD ----------------------*/

function cl_clean_head() {
	/* Version number */
	remove_action( 'wp_head', 'wp_generator' );
	add_filter( 'the_generator', '__return_empty_string' );

	/* Feeds */
	remove_action( 'wp_head', 'feed_links', 2 );
	remove_action( 'wp_head', 'feed_links_extra', 3 );

	/* XML-RPC discovery */
	remove_action( 'wp_head', 'rsd_link' );

	/* Shortlink */
	remove_action( 'wp_head', 'wp_shortlink_wp_head' );
	remove_action( 'template_redirect', 'wp_shortlink_header', 11 );

	/* REST API discovery (the API itself keeps working for the editor) */
	remove_action( 'wp_head', 'rest_output_link_wp_head' );
	remove_action( 'template_redirect', 'rest_output_link_header', 11 );

	/* oEmbed discovery and the embed script (the editor can still embed others) */
	remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
	remove_action( 'wp_head', 'wp_oembed_add_host_js' );

	/* Emoji: modern systems draw emoji themselves */
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_enqueue_scripts', 'wp_enqueue_emoji_styles' );
	remove_action( 'admin_enqueue_scripts', 'wp_enqueue_emoji_styles' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	add_filter( 'emoji_svg_url', '__return_false' );
}
add_action( 'init', 'cl_clean_head' );
