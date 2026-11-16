<?php
/*
The doodles behind the start page hero's paper and portrait
(components/hero-shapes.css). Decorative: the whole layer is aria-hidden.

Each shape is a 0 x 0 anchor at its start point, in % of the hero. Per shape:
	x, y, size     desktop place and width
	x-m, y-m, size-m   phones and tablets (below 1000 px)
	x-t, y-t       tablets only (721 to 999 px), where they differ
	rise, rise-m   px it rises over one screen of scrolling (phones: rise-m)
	dx, spin       px it drifts sideways and how far it turns meanwhile
	i              its place in the pop-in order
	mx             wide and wider: place in the side margin, 0 edge to 1 content
Classes: front-m lies on top of the paper's edge on phones (in the side margin),
late starts below the hero and rises in with its bottom edge, fill is a filled
shape instead of a pen stroke. wide (from 1440 px) and wider (from 1800 px)
only show on wide screens, where they fill the side margins; right counts mx
from the right edge. tall (screens from 1000 px high) and taller (from
1300 px) fill the bands above and below the paper and portrait, where the hero
grows with the screen (start-hero.css, TALL). Positions come from the prototype Caroline
approved (decision 0010); the wide ones were added later.
*/

$clind_hero_shapes = array(
	array( 'star-solo', 'yellow', 'front-m', 'i:0; x:4%; y:9%; size:76px; rise:130px; dx:-8px; spin:24deg; x-m:97%; y-m:11%; size-m:30px' ),
	array( 'sparkle', 'lilac', 'front-m', 'i:1; x:63%; y:7%; size:40px; rise:170px; dx:6px; spin:-30deg; x-m:3%; y-m:21%; size-m:24px; y-t:27%' ),
	array( 'spiral', 'sky', 'front-m', 'i:2; x:97%; y:22%; size:58px; rise:110px; dx:-6px; spin:28deg; x-m:97%; y-m:37%; size-m:28px; y-t:30%' ),
	array( 'sun', 'yellow', 'front-m', 'i:3; x:97.5%; y:80%; size:44px; rise:80px; dx:-6px; spin:20deg; x-m:3%; y-m:44%; size-m:24px; y-t:40%' ),
	array( 'loop', 'lilac', '', 'i:4; x:8%; y:94%; size:104px; rise:30px; dx:-4px; spin:8deg; x-m:8%; y-m:74%; size-m:50px; x-t:22%; y-t:70%' ),
	array( 'pen-ring', 'yellow', '', 'i:5; x:37%; y:96%; size:30px; rise:50px; dx:0px; spin:-30deg; x-m:92%; y-m:68%; size-m:20px; x-t:78%; y-t:62%' ),
	array( 'wave', 'yellow', '', 'i:6; x:74%; y:93%; size:92px; rise:70px; dx:8px; spin:-8deg; x-m:93%; y-m:86%; size-m:42px; x-t:80%; y-t:86%' ),
	array( 'sparkle', 'yellow', '', 'i:7; x:2%; y:52%; size:28px; rise:100px; dx:4px; spin:-20deg; x-m:9%; y-m:91%; size-m:20px; x-t:20%; y-t:90%' ),
	array( 'cloud', 'sky', 'late', 'i:8; x:22%; y:104%; size:46px; rise:100px; dx:4px; spin:-8deg; x-m:36%; y-m:103%; size-m:30px; rise-m:70px' ),
	array( 'blob', 'yellow', 'late fill', 'i:10; x:86%; y:103%; size:26px; rise:90px; dx:-4px; spin:16deg; x-m:72%; y-m:103%; size-m:18px; rise-m:70px' ),
	array( 'squiggle', 'sky', '', 'i:12; x:38%; y:6%; size:64px; rise:150px; dx:4px; spin:-12deg; x-m:30%; y-m:2.4%; size-m:46px; x-t:52%' ),
	array( 'star-solo', 'lilac', '', 'i:13; x:53%; y:93%; size:30px; rise:35px; dx:-4px; spin:24deg; x-m:5%; y-m:64%; size-m:20px; rise-m:20px; x-t:16%; y-t:58%' ),
	array( 'sparkle', 'sky', 'wide', 'i:1.5; mx:.5; y:30%; size:32px; rise:140px; dx:6px; spin:24deg' ),
	array( 'cloud', 'lilac', 'wide', 'i:5.5; mx:.55; y:74%; size:52px; rise:60px; dx:-4px; spin:-10deg' ),
	array( 'star-solo', 'sky', 'wide right', 'i:3.5; mx:.45; y:7%; size:40px; rise:160px; dx:-6px; spin:-24deg' ),
	array( 'pen-ring', 'lilac', 'wide right', 'i:7.5; mx:.55; y:52%; size:34px; rise:90px; dx:4px; spin:30deg' ),
	array( 'sun', 'lilac', 'wider', 'i:2.5; mx:.8; y:17%; size:48px; rise:120px; dx:6px; spin:-20deg' ),
	array( 'wave', 'sky', 'wider', 'i:6.5; mx:.75; y:55%; size:70px; rise:80px; dx:-6px; spin:10deg' ),
	array( 'sparkle', 'yellow', 'wider right', 'i:4.5; mx:.8; y:33%; size:30px; rise:130px; dx:-4px; spin:20deg' ),
	array( 'blob', 'lilac', 'wider right fill', 'i:9.5; mx:.75; y:68%; size:22px; rise:70px; dx:4px; spin:-16deg' ),
	array( 'spiral', 'lilac', 'tall', 'i:0.5; x:24%; y:13%; size:44px; rise:150px; dx:4px; spin:-24deg' ),
	array( 'loop', 'sky', 'tall', 'i:6; x:30%; y:87%; size:70px; rise:50px; dx:-4px; spin:8deg' ),
	array( 'cloud', 'yellow', 'tall', 'i:2; x:68%; y:14%; size:48px; rise:140px; dx:-6px; spin:10deg' ),
	array( 'squiggle', 'lilac', 'tall', 'i:8.5; x:64%; y:83%; size:56px; rise:60px; dx:4px; spin:-10deg' ),
	array( 'sun', 'sky', 'taller', 'i:4; x:46%; y:15%; size:40px; rise:130px; dx:4px; spin:20deg' ),
	array( 'sparkle', 'lilac', 'taller', 'i:7; x:48%; y:86%; size:30px; rise:70px; dx:-4px; spin:-20deg' ),
	array( 'star-solo', 'yellow', 'taller', 'i:9; x:86%; y:80%; size:34px; rise:90px; dx:6px; spin:24deg' ),
);
?>
<div class="hero-shapes" aria-hidden="true">
	<?php
	foreach ( $clind_hero_shapes as $clind_shape ) :
		list( $clind_name, $clind_color, $clind_flags, $clind_vars ) = $clind_shape;

		/* "x:4%; y:9%" → "--x:4%; --y:9%" */
		$clind_style = preg_replace( '/(^|;\s*)([a-z-]+):/', '$1--$2:', $clind_vars );

		$clind_class = 'hero-shape hero-shape--' . $clind_color;
		foreach ( array_filter( explode( ' ', $clind_flags ) ) as $clind_flag ) {
			$clind_class .= ' hero-shape--' . $clind_flag;
		}
		?>
		<div class="<?php echo esc_attr( $clind_class ); ?>" style="<?php echo esc_attr( $clind_style ); ?>">
			<?php clind_doodle( $clind_name ); ?>
		</div>
	<?php endforeach; ?>
</div>
