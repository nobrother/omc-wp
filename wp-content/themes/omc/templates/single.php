<?php

get_header( 'site' ); the_post(); ?>

<div id="primary" <?php post_class( 'content-area' ) ?>><!-- / Facebook snippet -->

  <main id="main" class="site-main" role="main">

    <div id="single-post">
      <div class="block block-colored" style="background-color:#f2f2f2">
        <div class="block-content">
          <div class="container">
            <div class="block-body">
							<?php the_content() ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main><!-- #main -->
</div><!-- #primary -->

<?php get_sidebar( 'site' ); ?>
<?php get_footer( 'site' ); ?>