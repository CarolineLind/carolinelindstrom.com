<?php get_header();?>

 <?php
    if(is_active_sidebar('pageSidebar')) {
    	//dynamic_sidebar('pageSidebar');
    }

?>

<div class='page-wrapper'>

	<div class="utvaldBild">
		<?php
		if ( has_post_thumbnail() ) { 
			the_post_thumbnail( 'blog-large','full' );
		}
		?>
	</div>


    <!--<h1><?php the_title();?></h1>-->

    <?php get_template_part('includes/section', 'blogcontent');?>

</div>

<?php get_footer();?>