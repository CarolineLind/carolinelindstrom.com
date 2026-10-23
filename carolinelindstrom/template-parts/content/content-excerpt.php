<?php
/* One post in a list (index, archive, search) */
?>
<li id="post-<?php the_ID(); ?>" <?php post_class( 'entry-list__item' ); ?>>
	<h2 class="entry-list__title h3"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
	<p class="entry-list__meta mono muted"><time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time></p>
	<div class="entry-list__excerpt muted"><?php the_excerpt(); ?></div>
</li>
