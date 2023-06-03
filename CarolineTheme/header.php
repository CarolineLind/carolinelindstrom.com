<!DOCTYPE html>
<!-- START header.php -->
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="description" content="En Wordpressida av Caroline Lindström." />
	<meta name="theme-color" content="rgb(247, 247, 247" />
	<meta name="HandheldFriendly" content="True" />
	<meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=5.0, width=device-width" />
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta property="og:url" content="https://carolinelindstrom.com/" />
    <meta property="og:type" content="website" />

<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-7BCC6RX3DT"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-7BCC6RX3DT');
</script>


	<?php wp_head();?>

	<link href="https://fonts.googleapis.com/css?family=Josefin+Sans:300,400,700|Raleway:300,400,500,600,800&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://use.typekit.net/age5dnw.css">

	<link rel="preconnect" href="https://fonts.googleapis.com">
	
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;700&family=Space+Mono:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">

	<!-- Place your kit's code here -->
    <script src="https://kit.fontawesome.com/0504f0b87c.js" crossorigin="anonymous"></script>

	
</head>
<body>
	
<header class="relative">
	
	<nav class='menu-wrapp' aria-label="Huvudmeny">
		<button class='menuButton toggleParent mobileOnly' aria-label='Meny'><i class="fas fa-bars"></i></button>
		
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
		<img src="<?php echo $image[0]; ?>" width="726" height="309"  >
	</a>

	 

</header>
<!-- END header.php -->







