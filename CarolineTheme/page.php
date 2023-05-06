<?php get_header();?>
<!-- START page.php -->
<div class='page-wrapper defaultList'>

	<div class='section'>

		<?php if(is_active_sidebar('pageSidebar')) {

			dynamic_sidebar('pageSidebar');
			echo "<h1>SIDEBAR</h1>";

		}

		?>

	    <!--h1><?php the_title();?></h1-->

	    <?php get_template_part('includes/section', 'content');?>

	</div>

</div>
<!-- END page.php -->
<?php get_footer();?>