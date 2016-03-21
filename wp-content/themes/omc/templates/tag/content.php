<header class="tag-header">
	<div class="container">
		<h1>Tag: <?php single_tag_title() ?></h1>
	</div>
</header>

<div class="tag-body two-col-right-sidebar">
	<div class="container">
		<div class="main-col"><?php omc_inject( 'post-list' ) ?></div>
		<div class="right-col"><?php omc_inject( 'sidebar/right-sidebar' ) ?></div>
	</div>
</div>