<?php
/**
 * Post class
 */
function omc_post_class( $classes, $class, $post_id ){
	if( is_sticky() && is_home() && ! is_paged() )
		array_push( $classes, 'sticky-post', 'featured-post' );
	return $classes;
}
add_filter( 'post_class', 'omc_post_class', 10, 3 );

/**
 * Displays excerpt
 */
function omc_post_excerpt( $class = 'post-excerpt' ) {	 
	global $post;
?>
		<div class="<?php esc_attr_e( $class ); ?>">
			<?php 
				echo $excerpt = get_the_excerpt();
				if( strpos( $excerpt, omc_excerpt_more() ) === false )					
					echo omc_excerpt_more();
			?>
		</div>
<?php }
/**
 * Add 'See more' link
 */
function omc_excerpt_more() {
	$link = sprintf( '<a href="%1$s" class="more-link">%2$s</a>',
		esc_url( get_permalink() ),
		'See more'
	);
	return ' &hellip; ' . $link;
}
function omc_excerpt_add_more( $text, $raw_excerpt ){
	if( $raw_excerpt !== '' ){
		return $text.omc_excerpt_more();
	}
	return $text;
}
add_filter( 'excerpt_more', 'omc_excerpt_more' );
add_filter( 'the_content_more_link', 'omc_excerpt_more' );
add_filter( 'wp_trim_excerpt', 'omc_excerpt_add_more', 10, 2 );

/**
 * Prints HTML with meta information for the categories, tags.
 */
function omc_entry_meta() {
	if ( 'post' === get_post_type() ) {
		$author_avatar_size = apply_filters( 'omc_author_avatar_size', 49 );
		printf( '<span class="byline"><span class="author vcard">%1$s<span class="screen-reader-text">%2$s </span> <a class="url fn n" href="%3$s">%4$s</a></span></span>',
			get_avatar( get_the_author_meta( 'user_email' ), $author_avatar_size ),
			_x( 'Author', 'Used before post author name.', 'omc' ),
			esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
			get_the_author()
		);
	}

	if ( in_array( get_post_type(), array( 'post', 'attachment' ) ) ) {
		omc_entry_date();
	}

	$format = get_post_format();
	if ( current_theme_supports( 'post-formats', $format ) ) {
		printf( '<span class="entry-format">%1$s<a href="%2$s">%3$s</a></span>',
			sprintf( '<span class="screen-reader-text">%s </span>', _x( 'Format', 'Used before post format.', 'omc' ) ),
			esc_url( get_post_format_link( $format ) ),
			get_post_format_string( $format )
		);
	}

	if ( 'post' === get_post_type() ) {
		omc_entry_taxonomies();
	}

	if ( ! is_singular() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
		echo '<span class="comments-link">';
		comments_popup_link( sprintf( __( 'Leave a comment<span class="screen-reader-text"> on %s</span>', 'omc' ), get_the_title() ) );
		echo '</span>';
	}
}

/**
 * Prints HTML with category and tags for current post.
 */
function omc_entry_taxonomies() {
	$categories_list = get_the_category_list( ', ' );
	if ( $categories_list && omc_categorized_blog() ) 
		printf( '<span class="cat-links"></span>%s</span>',	$categories_list );

	$tags_list = get_the_tag_list( '', ', ' );
	if ( $tags_list )
		printf( '<span class="tags-links">%s</span>',	$tags_list );
}

function omc_post_thumbnail() {
	if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
		return;
	}

	if ( is_singular() ) :
	?>

	<div class="post-item-thumbnail">
		<?php the_post_thumbnail(); ?>
	</div>

	<?php else : ?>

	<a class="post-list-item-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true">
		<?php the_post_thumbnail( 'post-thumbnail', array( 'alt' => the_title_attribute( 'echo=0' ) ) ); ?>
	</a>

	<?php endif; // End is_singular()
}

/**
 * Determines whether blog/site has more than one category.
 *
 * Create your own omc_categorized_blog() function to override in a child theme.
 *
 * @since Twenty Sixteen 1.0
 *
 * @return bool True if there is more than one category, false otherwise.
 */
function omc_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'omc_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'omc_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so omc_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so omc_categorized_blog should return false.
		return false;
	}
}

/**
 * Flushes out the transients used in omc_categorized_blog().
 *
 * @since Twenty Sixteen 1.0
 */
function omc_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Like, beat it. Dig?
	delete_transient( 'omc_categories' );
}
add_action( 'edit_category', 'omc_category_transient_flusher' );
add_action( 'save_post',     'omc_category_transient_flusher' );



/**
 * Echo archive pagination in page numbers format.
 */
function omc_pagination_nav() {

	if( is_singular() )
		return;

	global $wp_query;

	//* Stop execution if there's only 1 page
	if( $wp_query->max_num_pages <= 1 )
		return;

	$paged = get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1;
	$max   = intval( $wp_query->max_num_pages );

	//* Add current page to the array
	if ( $paged >= 1 )
		$links[] = $paged;

	//* Add the pages around the current page to the array
	if ( $paged >= 3 ) {
		$links[] = $paged - 1;
		$links[] = $paged - 2;
	}

	if ( ( $paged + 2 ) <= $max ) {
		$links[] = $paged + 2;
		$links[] = $paged + 1;
	}

	echo '<nav><ul class="pagination">';

	//* Previous Post Link
	if ( get_previous_posts_link() )
		printf( '<li class="previous">%s</li>' . "\n", get_previous_posts_link( '<span>&laquo;</span>' ) );

	//* Link to first page, plus ellipses if necessary
	if ( ! in_array( 1, $links ) ) {

		$class = 1 == $paged ? ' class="active"' : '';

		printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( 1 ) ), '1' );

		if ( ! in_array( 2, $links ) )
			echo '<li class="omission"><a>&#x02026;</a></li>';

	}

	//* Link to current page, plus 2 pages in either direction if necessary
	sort( $links );
	foreach ( (array) $links as $link ) {
		$class = $paged == $link ? ' class="active"' : '';
		printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( $link ) ), $link );
	}

	//* Link to last page, plus ellipses if necessary
	if ( ! in_array( $max, $links ) ) {

		if ( ! in_array( $max - 1, $links ) )
			echo '<li class="omission"><a>&#x02026;</a></li>' . "\n";

		$class = $paged == $max ? ' class="active"' : '';
		printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( $max ) ), $max );

	}

	//* Next Post Link
	if ( get_next_posts_link() )
		printf( '<li class="next">%s</li>' . "\n", get_next_posts_link( '<span>&raquo;</span>' ) );

	echo '</ul></nav>' . "\n";

}