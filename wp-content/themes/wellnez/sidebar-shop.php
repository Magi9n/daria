<?php
	// Block direct access
	if( ! defined( 'ABSPATH' ) ){
		exit( );
	}
	/**
	* @Packge 	   : Wellnez
	* @Version     : 1.0
	* @Author     : Vecurosoft
    * @Author URI : https://www.vecurosoft.com/
	*
	*/

	if( ! is_active_sidebar( 'wellnez-woo-sidebar' ) ){
		return;
	}
?>
<div class="col-lg-4 col-xl-3">
	<!-- Sidebar Begin -->
	<aside class="sidebar-area ps-lg-3 ps-xxl-0">
		<?php
			dynamic_sidebar( 'wellnez-woo-sidebar' );
		?>
	</aside>
	<!-- Sidebar End -->
</div>