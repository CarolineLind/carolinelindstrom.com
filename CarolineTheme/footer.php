
<!-- START footer.php -->



	<footer>
		<div class='footer-wrapp'>

			<div class="footer-header">
				
			</div>


			<div class="footer-main flexbox wrap">
				<div class="footer-logo-wrap col-xs-12 col-sm-6 col-md-4 col-lg-3 flexbox middle-xs">
					<?php	
					$custom_logo_id = get_theme_mod( 'custom_logo' );
					$image = wp_get_attachment_image_src( $custom_logo_id , 'full' );
					?>
					<a id="footer-logo" href="/" title="Caroline Lindstrom" class="flexbox">
						<img src="<?php echo $image[0]; ?>" width="726" height="309"  >
					</a> 
				</div>

				<div class="footer-text-wrap col-xs-12 col-sm-6 col-md-8 col-lg-9">
					<!--<?php
						wp_nav_menu(
							array(
								'theme_location'=> 'footer-menu',
								'menu_class'=> 'footer-bar'
							)
						);
					?>-->

					
					<h2>Här hittar du mig</h2>
					<ul>
						<li><a href="mailto:lindstrmcaroline@gmail.com"><i class="far fa-envelope"></i>lindstrmcaroline@gmail.com</a></li>
						<li><a href="https://www.linkedin.com/in/caroline-lindstr%C3%B6m-328a33141/"><i class="fab fa-linkedin-in"></i>LinkedIn</a></li>
						<li><a href="https://www.instagram.com/carrolinelindstrom/"><i class="fab fa-instagram"></i>Instagram</a></li>
						<li><a href="https://github.com/CarolineLind"><i class="fab fa-github"></i>Github</a></li>
						
					</ul>
				</div>

			</div>
			<div class="footer-footer center-xs end-sm">
				<p class="copyright">© <span class="headingstyle">Caroline Lindström 2023</span></p>
			</div>
		</div>
	</footer>




	<?php wp_footer();?>
</body>
</html>
<!-- END footer.php -->

