<?php if( have_posts() ): while( have_posts() ): the_post();?>


	<div class="archive-post">

		<div class="postBild">
			<?php
			if ( has_post_thumbnail() ) { 
				the_post_thumbnail( 'blog-small','full' );
			}
			?>
		</div>

		<div class="postText">
			<a href="<?php the_permalink();?>"><h3><?php the_title();?></h3></a>
	    	<?php the_excerpt();?>
	    	<a href="<?php the_permalink();?>" class="button">
	    		Läs mer<i class="fas fa-mouse-pointer"></i>
	    	</a>
    	</div>

    </div>

<?php endwhile; else: 

echo ('Tyvärr hittade vi inget med ');
echo get_search_query();

endif;?>


