<article id="post-item-<?php the_ID(); ?>" <?php post_class( 'post-item' ); ?>>
	
	<!-- HEADER -->
	<header class="post-item-header">
		<h1>
			<a href="<?php echo get_permalink() ?>"><?php the_title() ?></a>
		</h1>
	</header><!-- // HEADER -->
	
	<!-- BODY -->
	<div class="post-item-body">
		<?php 
			the_content( 
				sprintf(
					'Continue reading %s <span class="meta-nav">&rarr;</span>',
					the_title( '<span class="sr-only">"', '"</span>', false )
				)
			);
		?>		
	</div><!-- // BODY -->
</article><!-- // POST -->