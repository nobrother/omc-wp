<?php
/**
 * Posts list here
 */

get_header( 'post' ); ?>
	
	<!-- POSTS -->
	<div id="posts">
		
	<?php 
		if( have_posts() ){
			
			while( have_posts() ){ 
				the_post(); 
				omc_get_template_part( 'templates/post', 'item' ); 
			} 
			
			the_posts_navigation();

		} 
		
		else { 
			get_template_part( 'content', 'none' ); 
		}
	?>
	</div><!-- // POSTS -->

<?php get_sidebar( 'post' ); ?>
<?php get_footer( 'post' ); ?>
