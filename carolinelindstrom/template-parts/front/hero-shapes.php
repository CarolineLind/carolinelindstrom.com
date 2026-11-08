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
Classes: front-m lies on top of the paper's edge on phones (in the side margin),
late starts below the hero and rises in with its bottom edge, fill is a filled
shape instead of a pen stroke. Positions come from the prototype Caroline
approved (decision 0010).
*/

$clind_hero_shapes = array(
	array( 'star-solo', 'yellow', 'front-m', 'i:0; x:4%; y:9%; size:76px; rise:130px; dx:-8px; spin:24deg; x-m:97%; y-m:11%; size-m:30px' ),
	array( 'sparkle', 'lilac', 'front-m', 'i:1; x:63%; y:7%; size:40px; rise:170px; dx:6px; spin:-30deg; x-m:3%; y-m:27%; size-m:24px' ),
	array( 'spiral', 'sky', 'front-m', 'i:2; x:97%; y:22%; size:58px; rise:110px; dx:-6px; spin:28deg; x-m:97%; y-m:44%; size-m:28px; y-t:30%' ),
	array( 'sun', 'yellow', 'front-m', 'i:3; x:97.5%; y:80%; size:44px; rise:80px; dx:-6px; spin:20deg; x-m:3%; y-m:51%; size-m:24px; y-t:40%' ),
	array( 'loop', 'lilac', '', 'i:4; x:8%; y:94%; size:104px; rise:30px; dx:-4px; spin:8deg; x-m:8%; y-m:74%; size-m:50px; x-t:22%; y-t:70%' ),
	array( 'pen-ring', 'yellow', '', 'i:5; x:44%; y:96%; size:30px; rise:50px; dx:0px; spin:-30deg; x-m:92%; y-m:68%; size-m:20px; x-t:78%; y-t:62%' ),
	array( 'wave', 'yellow', '', 'i:6; x:74%; y:93%; size:92px; rise:70px; dx:8px; spin:-8deg; x-m:93%; y-m:86%; size-m:42px; x-t:80%; y-t:86%' ),
	array( 'sparkle', 'yellow', '', 'i:7; x:2%; y:52%; size:28px; rise:100px; dx:4px; spin:-20deg; x-m:9%; y-m:91%; size-m:20px; x-t:20%; y-t:90%' ),
	array( 'cloud', 'sky', 'late', 'i:8; x:22%; y:104%; size:46px; rise:100px; dx:4px; spin:-8deg; x-m:36%; y-m:103%; size-m:30px; rise-m:70px' ),
	array( 'blob', 'yellow', 'late fill', 'i:10; x:86%; y:103%; size:26px; rise:90px; dx:-4px; spin:16deg; x-m:72%; y-m:103%; size-m:18px; rise-m:70px' ),
	array( 'squiggle', 'sky', '', 'i:12; x:38%; y:6%; size:64px; rise:150px; dx:4px; spin:-12deg; x-m:52%; y-m:2.4%; size-m:46px' ),
	array( 'star-solo', 'lilac', '', 'i:13; x:57%; y:95%; size:30px; rise:35px; dx:-4px; spin:24deg; x-m:5%; y-m:64%; size-m:20px; rise-m:20px; x-t:16%; y-t:58%' ),
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
