<div class="post-meta">

	<div class="author" itemprop="author">
		<div class="byline vcard clearfix" itemscope itemtype="http://schema.org/Person">
			<?php echo get_avatar( get_the_author_meta( 'ID' ), 42 ); ?>
			<a rel="author" class="url fn n" href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) ?>" itemprop="name">
				<?php the_author() ?>
			</a>
			<time class="post-date" datetime="<?php esc_attr_e( get_the_date( 'c' ) ) ?>">
				<?php echo get_the_date() ?>
			</time>
		</div>
	</div> 	

	<div class="category-links">
		<?php echo get_the_category_list( ', ' ); ?>
	</div>

	<div class="tag-links">
		<?php echo get_the_tag_list( '', ', ' ); ?>
	</div>
</div>