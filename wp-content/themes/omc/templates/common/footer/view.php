<?php
/** 
 * The template for displaying the footer.
 */
do_action( 'omc_before_footer' );
wp_footer(); 
do_action( 'omc_after_footer' );
// Load bottom script
omc_option( 'bottom_script' );?>
</body>
</html>
