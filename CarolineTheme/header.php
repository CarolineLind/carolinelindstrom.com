<!DOCTYPE html>
<!-- START header.php -->
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="description" content="En Wordpressida av Caroline Lindström." />
	<meta name="theme-color" content="rgb(247, 247, 247" />
	<meta name="HandheldFriendly" content="True" />
	<meta name="viewport" content="initial-scale=1.0, maximum-scale=5.0, width=device-width" />
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta property="og:url" content="https://carolinelindstrom.com/" />
    <meta property="og:type" content="website" />




	<?php wp_head();?>


	<link rel="preconnect" href="https://fonts.googleapis.com">
	
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;700&family=Space+Mono:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">

	<!-- Place your kit's code here -->
    <script src="https://kit.fontawesome.com/0504f0b87c.js" crossorigin="anonymous"></script>

	
</head>
<body>
	
<header class="">
	
	<nav class='menu-wrapp' aria-label="Huvudmeny">
		<button class='menuButton toggleParent js-toggleParent mobileOnly' aria-label='Öppna meny' type="button" aria-expanded="false">
			<!--i class="fas fa-bars"></i-->
			<div class="menu_checkbox">
				<div></div>
				<div></div>
				<div></div>
			</div>
		</button>
		
		<div class="menu-main headingstyle">
			<?php
				wp_nav_menu(
					array(
						'theme_location'=> 'top-menu',
						//'menu' => 'Top Bar'
						'menu_class'=> 'top-bar'
					)
				);
			?>
			<!--ul>
				<li>
					<a href="/">testlänk 1</a>
				</li>
				<li>
					<a href="/">testlänk 2</a>
				</li>
			</ul-->
		</div>
	</nav>


	
	<!--<?php if ( has_custom_logo() ) : ?>
		<a id="logo" href="/" title="Caroline Lindstrom">
		<?php the_custom_logo(); ?>
		</a>
	<?php endif; ?>-->
	


	<?php	
	$custom_logo_id = get_theme_mod( 'custom_logo' );
	$image = wp_get_attachment_image_src( $custom_logo_id , 'full' );
	?>
	<a id="logo" href="/" title="Caroline Lindstrom">
		<img src="<?php echo $image[0]; ?>" width="726" height="309"  alt="Logga för Carolines webbsida. Föreställer texten <cl>">
	</a>

	 

</header>
<!-- END header.php -->







