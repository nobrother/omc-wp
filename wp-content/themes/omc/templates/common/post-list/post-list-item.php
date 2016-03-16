<?php 

namespace OMC\Post;

global $post;

$post_obj = new Post( $post );

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
	
	<?php omc_post_thumbnail(); ?>

	<div class="post-list-item-body">
		<?php omc_post_excerpt(); ?>
	</div>

	<footer class="post-list-item-footer">
		
		<div class="post-item-actions">
			<a class="js-post-like post-item-like <?php $post_obj->is_liked() && _e( 'active' ) ?>" href="#">
				<span class="like"><i class="fa fa-thumbs-o-up"></i> Like</span>
				<span class="unlike"><i class="fa fa-thumbs-up"></i> Unlike</span>
			</a>
		</div>
		
		<!-- Likes, Comment Count, Share Count -->
		<div class="post-item-meta">			
			<!-- Like count-->
			<span class="post-item-like-count">
				<span class="like-count js-post-like-count">
				<?php	
					if( !is_wp_error( $liked = $post_obj->is_liked() ) ){ 
						 esc_html_e( $post_obj->like_count );
					} else {
						echo 0;
					}
				?>
				</span>
				<span>like(s)</span>
			</span><!-- / Like count -->			
		</div><!-- / Likes, Comment Count, Share Count -->
		
		<div class="byline vcard">
			<address class="author">
				<a rel="author" class="url fn n" href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) ?>">
					<?php the_author() ?>
				</a>
			</address> 
			on 
			<time class="entry-date published updated" datetime="<?php esc_attr_e( get_the_date( 'c' ) ) ?>">
				<?php echo get_the_date() ?>
			</time>
		</div>
		
		<div class="category-links">
			<?php echo get_the_category_list( ', ' ); ?>
		</div>
		
		<div class="tag-links">
			<?php echo get_the_tag_list( '', ', ' ); ?>
		</div>
		<?php 			
			edit_post_link(
				sprintf(
					/* translators: %s: Name of current post */
					__( 'Edit<span class="screen-reader-text"> "%s"</span>', 'twentysixteen' ),
					get_the_title()
				),
				'<span class="edit-link">',
				'</span>'
			);
		?>
	</footer>
</article>