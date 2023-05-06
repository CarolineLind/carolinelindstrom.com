<?php if( have_posts() ): while( have_posts() ): the_post();?>


    <?php the_content();?>

    <!--<?php the_author();?>-->

    <!--<?php
    	$fname = get_the_author_meta('first_name');
    	$lname = get_the_author_meta('last_name');
    	echo $fname . ' ' . $lname;
    ?>>-->

    <div class="senastUppdaterad">Senast uppdaterad: <?php echo get_the_date('d/m/y');?></div>

    <div class="kategoriLank">
    <?php
    $categories = get_the_category();
    foreach($categories as $cat) {
    	echo "<a href='" . get_category_link($cat) ."'>$cat->name</a>";
    }

    ?>
    </div>

	

<?php endwhile; else: endif;?>


