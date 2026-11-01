<?php
/*
Decorative dots and twinkling stars behind a section (components/sky.css).
Used behind "Här hittar du mig" in the footer and behind the start page hero.
The section places it (position: relative) and can set its own mask and dot colour.
*/
?>
<div class="sky" aria-hidden="true">
	<?php for ( $clind_star = 1; $clind_star <= 6; $clind_star++ ) : ?>
		<span class="sky__stars sky__stars--<?php echo absint( $clind_star ); ?>"></span>
	<?php endfor; ?>
</div>
