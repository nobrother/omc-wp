<div class="two-col-right-sidebar">
	<div class="container">
		<div class="main-col">
			<header class="post-header">
				<h1><?php the_title() ?></h1>
			</header>
			<div class="post-body">
				<?php omc_include_file( __DIR__.'/post.php' ); ?>
			</div>
		</div>
		<div class="right-col"><?php omc_inject( 'sidebar/right-sidebar' ) ?></div>
	</div>
</div>