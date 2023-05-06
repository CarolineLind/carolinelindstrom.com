<?php get_header();?>

 <?php
    if(is_active_sidebar('pageSidebar')) {
    	//dynamic_sidebar('pageSidebar');
    }

?>

<div class='page-wrapper single-project-wrapper'>

	<div class="utvaldBild">
		<?php
		if ( has_post_thumbnail() ) { 
			the_post_thumbnail( 'blog-large','full' );
		}
		?>
	</div>


    <?php get_template_part('includes/section', 'blogcontent-projekt');?>

    <ul class="anpassadeFalt">
    	<li>
    		<div>Genomfördes:</div>
    		<div><?php echo get_post_meta($post->ID, 'Genomfördes', true );?></div>
    	</li>
    	<li>
    		<div>Projektets längd:</div>
    		<div><?php echo get_post_meta($post->ID, 'Projektets längd', true );?></div>
    	</li>
    	<li>
    		<div>Budget:</div>
    		<div><?php echo get_post_meta($post->ID, 'Budget', true );?></div>
    	</li>
    </ul>

</div>

<?php get_footer();?>
