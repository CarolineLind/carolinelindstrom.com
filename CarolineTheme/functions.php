<?php
/*--------------------------------------------------*/
/*------------------- FUNCTIONS --------------------*/
/*--------------------------------------------------*/

/* testar */
if ( ! function_exists( 'CarolineTheme_setup' ) ) {
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 *
	 * @since Caroline Theme 1.1
	 *
	 * @return void
	 */
	function CarolineTheme_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on Twenty Twenty-One, use a find and replace
		 * to change 'twentytwentyone' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'twentytwentyone', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * This theme does not use a hard-coded <title> tag in the document head,
		 * WordPress will provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/**
		 * Add post-formats support.
		 */
		add_theme_support(
			'post-formats',
			array(
				'link',
				'aside',
				'gallery',
				'image',
				'quote',
				'status',
				'video',
				'audio',
				'chat',
			)
		);

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );
		set_post_thumbnail_size( 1568, 9999 );

		/*
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 * 
		 */
		$logo_width  = 300;
		$logo_height = 100;

		add_theme_support( 'custom-logo', array(
				'height'               => $logo_height,
				'width'                => $logo_width,
				'flex-width'           => true,
				'flex-height'          => true,
				'unlink-homepage-logo' => true,
			)
		);

	}
} 
add_action( 'after_setup_theme', 'CarolineTheme_setup' );

/* Load styles */
function load_css(){
	/* header styles */
	wp_register_style('flexboxgrid', get_template_directory_uri() . '/css/flexboxgrid-6.3.1/css/flexboxgrid.css', array(), false, 'all');
	wp_enqueue_style('flexboxgrid');

	wp_register_style('main', get_template_directory_uri() . '/css/main.css', array(),  wp_get_theme()->get('Version'), 'all');
	wp_enqueue_style('main');

	wp_register_style('header', get_template_directory_uri() . '/css/header.css', array(),  wp_get_theme()->get('Version'), 'all');
	wp_enqueue_style('header');

	wp_register_style('formelements', get_template_directory_uri() . '/css/formelements.css', array(),  wp_get_theme()->get('Version'), 'all');
	wp_enqueue_style('formelements');

	wp_register_style('blog', get_template_directory_uri() . '/css/blog.css', array(),  wp_get_theme()->get('Version'), 'all');
	wp_enqueue_style('blog');

	wp_register_style('welcome', get_template_directory_uri() . '/css/welcome.css', array(),  wp_get_theme()->get('Version'), 'all');
	wp_enqueue_style('welcome');

	wp_register_style('playground', get_template_directory_uri() . '/css/playground.css', array(),  wp_get_theme()->get('Version'), 'all');
	wp_enqueue_style('playground');

}
add_action('wp_enqueue_scripts', 'load_css');

function prefix_add_footer_styles() {
	/* footer styles */
	wp_register_style('footer', get_template_directory_uri() . '/css/footer.css', array(),  wp_get_theme()->get('Version'), 'all');
	wp_enqueue_style('footer');
}; 
add_action( 'get_footer', 'prefix_add_footer_styles' );


/* Load script */
function load_js(){
	/* footer scripts */
	/*wp_enqueue_script('jquery');*/

	wp_register_script('js-in-footer', get_template_directory_uri() . '/js/js-in-footer.js',  wp_get_theme()->get('Version'), true);
	wp_enqueue_script('js-in-footer');
}
add_action('wp_enqueue_scripts', 'load_js');


/* Theme options */
add_theme_support('menus');
add_theme_support('post-thumbnails');
add_theme_support('widgets');


/* Menus */
register_nav_menus(
	array(
		'top-menu' => 'Top Menu Location',
		'mobile-menu' => 'Mobile Menu Location',
		'footer-menu' => 'Footer Menu Location',
	)
);


/* Custom Image Sizes */
add_image_size('blog-large', 1000, 600, false);
add_image_size('blog-small', 300, 200, false);


/* Register Sidebar */
function my_sidebars() {

	register_sidebar (
		array(
			'name'          => 'Page Sidebar',
			'id'            => 'pageSidebar',
			'description'   => 'Can be used for a sidebar on certain pages',
			'before_widget' => '<section class="widgetContainer pageSidebar">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widgetTitle">',
			'after_title'   => '</h2>'
		)
	);

	register_sidebar (
		array(
			'name'          => 'Blog Sidebar',
			'id'            => 'blogSidebar',
			'description'   => 'Can be used for a sidebar on blog pages',
			'before_widget' => '<section class="widgetContainer blogSidebar">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widgetTitle">',
			'after_title'   => '</h2>'
		)
	);

	register_sidebar(
		array(
			'name'          => 'Footer area one',
			'id'            => 'footer_area_one',
			'description'   => 'Can be used to add custom footer content',
			'before_widget' => '<section class="widgetContainer footer-area-one">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widgetTitle">',
			'after_title'   => '</h2>',
		)
	);


}
add_action('widgets_init', 'my_sidebars');


/* Register post type */
function my_post_type() {

	$args = array(
		'labels' => array(
			'name' => 'Projekt',
			'singular_name' => 'Projekt',
		),
		'public' => true,
		'has_archive' => true,
		'supports' => array('title', 'editor', 'thumbnail', 'custom-fields'),
		'menu_icon' => 'dashicons-images-alt2',
		//'rewrite' => array('slug' => 'projekt'),
	);

	register_post_type('projekt', $args);
	/* sök efter denna funktion för fler alternativ */

}
add_action('init', 'my_post_type');


/* Register post type categories */
function my_taxonomy() {

	$args = array(
		'labels' => array(
			'name' => 'Projekttyper',
			'singular_name' => 'Projekttyp',
		),
		'public' => true,
		'hierarchical' => true,
	);

	register_taxonomy('Projekttyper', array('projekt'), $args);

}
add_action('init', 'my_taxonomy');




/*--------------------------------------------------*/