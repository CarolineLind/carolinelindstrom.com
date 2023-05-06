<?php get_header();?>

<div class='page-wrapper'>
	<h1><?php echo single_cat_title();?></h1>

	category ideelt template
	<?php get_template_part('includes/section', 'archive');?>

	<div class="pagination">
		<!--<?php previous_posts_link();?>
		<?php next_posts_link();?>-->
		<?php
			global $wp_query;
			$big = 999999999;
			echo paginate_links( array(
				'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link($big)) ),
				'format' => '?paged=%#%',
				'current' => max( 1, get_query_var('paged') ),
				'total' => $wp_query->max_num_pages
			));
		?>
	</div>
	    	
</div>

<?php get_footer();?>