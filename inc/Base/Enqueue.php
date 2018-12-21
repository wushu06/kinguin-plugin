<?php 

namespace Inc\Base; 

use Inc\Base\BaseController;

class Enqueue extends BaseController {

    function __construct () {

        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );
    }

    function enqueue ($hook) {
	    if($hook != 'toplevel_page_hmu_api_plugin'
            && $hook != 'hook-me-up-api_page_basic_auth'
            && $hook != 'hook-me-up-api_page_oauth_one') {
		    return;
	    }
        // enqueue all our scripts
        wp_enqueue_style( 'mystyle', plugins_url(). '/hook-me-up-api/assets/app.css', array(), null, 'screen' );
        wp_enqueue_style( 'jqueryui', plugins_url(). '/hook-me-up-api/assets/jqueryui.css', array(), null, 'screen' );
        wp_enqueue_style( 'fontAwesome', 'https://use.fontawesome.com/releases/v5.0.6/css/all.css', array(), null, 'screen' );
        wp_enqueue_script( 'myscript', plugins_url(). '/hook-me-up-api/assets/app.js', array(), null, true );
        wp_enqueue_script( 'date-picker', plugins_url(). '/hook-me-up-api/assets/datePicker.js', array(), null, true );

    }

}