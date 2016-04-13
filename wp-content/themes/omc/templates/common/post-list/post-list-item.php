<?php 

namespace OMC\Post;

global $post;
$post_obj = new Post( $post );
$data = array( 'post_obj' => $post_obj );
?>

<article 
	id="post-<?php the_ID(); ?>" 
	<?php post_class( 'post-list-item js-post' ); ?>
	data-id="<?php the_ID() ?>"
>
	<header class="post-list-item-header">
		<?php 
			the_title( 
				sprintf( '<h2 class="entry-title"><a href="%s">', esc_url( get_permalink() ) ), 
				'</a></h2>' 
			); 
		?>
	</header>
	
	<?php omc_inject( 'post-thumbnail' ) ?>

	<div class="post-list-item-body">
		<?php omc_post_excerpt(); ?>
	</div>

	<footer class="post-list-item-footer">
		
		<?php omc_inject( 'post-meta', true, $data ) ?>
		
		<?php omc_inject( 'post-actions', true, $data ) ?>
		
		<!-- Likes counts, Comment Count, Share Count -->
		<?php omc_inject( 'post-social', true, $data ) ?>
		<!-- / Likes counts, Comment Count, Share Count -->
		
		<?php edit_post_link( sprintf('Edit "%s"',	get_the_title()	), '<div class="edit-link" target="_blank">',	'</div>' ) ?>
	</footer>
</article>