<!-- START front-page.php -->
<?php get_header();?>

<div class='page-wrapper front-page-wrapper defaultList'>

	<div class='section'>

	    <!--h1><?php the_title();?></h1-->

	    <?php 
	    get_template_part('includes/section', 'content');
	    ?>

		  <!--<?php 
		get_search_form();
		?>-->

	</div>

</div>

<?php get_footer();?>
<!-- END front-page.php -->