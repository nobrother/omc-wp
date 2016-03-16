<?php 
/* 
 * Modify page here
 * You can add extra font, css js here by using correct functions
 */

 
/* Modification End */ 

omc_include_file( __DIR__.'/header.php' ); 
?>

<!-- Main menu -->
<div id="main-menu">
	<?php omc_include_file( OMC_COMMON_DIR.'/nav/nav.php' );  ?>
</div>
<!-- / Main menu -->

<!-- Main Content -->
<div id="content">
	<header class="blog-header">
		<div class="container">
			<h1>Blog</h1>
		</div>
	</header>
	
	<div class="blog-body">
		<div class="container">
			<?php omc_include_file( OMC_COMMON_DIR.'/post-list/post-list.php' );  ?>
			<?php omc_include_file( OMC_COMMON_DIR.'/sidebar/right-sidebar.php' );  ?>
		</div>
	</div>
</div><!-- / Main Content -->

<!-- Ending -->
<?php omc_include_file( OMC_COMMON_DIR.'/footer/footer.php' ); ?>
<!-- / Ending -->