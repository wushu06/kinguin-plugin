<?php 

namespace Inc\Api\Callbacks; 

use \Inc\Base\BaseController;

use \Inc\Api\SettingsApi;


class AdminCallbacks extends BaseController {

    function hmu_api_plugin () {
        
         require_once( $this->plugin_path."/template/dashboard.php" );
        
    
    }
    function hmu_basic_oauth_page () {
        require_once $this->plugin_path.'template/basic_oauth.php';
    }
    function hmu_oauth_one_page () {
        require_once $this->plugin_path.'template/oauth_one.php';
    }
    
    function hmu_cron_page () {
        require_once $this->plugin_path.'template/cron-page.php';        
    }


}