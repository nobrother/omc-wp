<?php

get_header( 'site' ); ?>

<div id="primary" <?php post_class( 'content-area' ) ?>>

  <main id="main" class="site-main" role="main">

    <div id="single-moment">
      <div class="block block-colored" style="background-color:#f2f2f2">
        <div class="block-content">
          <div class="container">
					
						<div class="block-header">
							<?php get_search_form(); ?>
						</div>
						
            <div class="block-body">
							
							<div id="pg-search">
								<div class="block block-colored" style="background-color:#f2f2f2">
									<div class="block-content">
										<div class="container">
											<div class="block-body">
											
												<!-- List -->
												<div id="post-list" class="post-list">
												<?php 
													
													// Loop
													if( have_posts() ){ 
														
														global $wp_query, $post;
														
														$s = get_search_query();
														$eof = ( $wp_query->max_num_pages <= 1 ) ? 1 : 0;
														$not_in = '';
														$key = md5( uniqid().$_COOKIE['unique_user_id'] );
														
														while( have_posts() ){
															the_post();
															$not_in .= $post->ID . ',';
															get_template_part( 'templates/content', 'post-loop' );
														}
														
														// Store not in
														set_transient( 'post_list_not_in_'.$key, rtrim( $not_in, ',' ), DAY_IN_SECONDS );
														
														// Add script
														add_action( 'omc_embed_script', function() use( $key, $eof, $s) { ?>
														<script>
															<?php 
																$filters = array(
																	's' => $s,
																);
															?>
															mainList = postList('<?php echo $key ?>', <?php echo $eof ?>, <?php echo json_encode( $filters ) ?>);
														</script>
														<?php });
													}
													
													// No result
													else { ?>
														<h2>No Post</h2>
													<?php } ?>
												</div><!-- / List -->
												
												<!-- Loading -->
												<div id="post-list-loading" class="post-list-loading">
													<i class="fa fa-refresh fa-spin loading"></i>
													<div class="ending">
														<i class="fa fa-child"></i>
														<i class="fa fa-child"></i>
														<i class="fa fa-child"></i>
													</div>								
												</div><!-- / Loading -->
											</div>
										</div>
									</div>
								</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main><!-- #main -->
</div><!-- #primary -->

<?php get_sidebar( 'site' ); ?>
<?php get_footer( 'site' ); ?>