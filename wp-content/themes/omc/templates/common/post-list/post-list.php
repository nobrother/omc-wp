<div class="post-list">
	<?php 
		if ( have_posts() ){

			while ( have_posts() ){
				the_post();
				include 'post-list-item.php';
			}

			omc_pagination_nav(); 
		}
	?>
</div>