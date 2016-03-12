<?php 
	/**
	 * Template Name: home
	 */
?>

<!-- Header -->
<?php omc_get_header( 'page' ); ?>
<!-- / Header -->

<!-- Main menu -->
<div id="main-menu">
	<?php omc_get_template_part( 'templates/nav', 'page' ); ?>
</div>
<!-- / Main menu -->

<!-- Main Content -->
<div id="content">
	<?php omc_get_template_part( 'templates/page-home', 'content' ); ?>
</div><!-- / Main Content -->

<!-- Sidebar -->
<aside id="right-sidebar">
<?php omc_get_template_part( 'templates/right-sidebar', 'page' ); ?>
</aside>
<!-- / Sidebar -->

<!-- Footer -->
<footer id="footer">
	<?php omc_get_template_part( 'templates/footer', 'page' ); ?>
</footer>
<!-- / Footer -->

<!-- Ending -->
<?php omc_get_footer( 'page' ); ?>
<!-- / Ending -->