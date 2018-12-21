<?php 

namespace Inc\Base;



class Deactivate  {


    public static function deactivate () {

        $roles = array ('5_discount'=>'5% Discount', '10_discount'=>'10% Discount', '15_discount'=>'15% Discount');

        foreach($roles as $slug=>$role) {
            remove_role( $slug, $role );
        }

        wp_clear_scheduled_hook( 'my_activation_cron' );
       /* $option  = get_option ('hmu_api_plugin');
        $option['cron_time'] ='';
        $option['cron_name'] ='';
        update_option('hmu_api_plugin', $option['cron_time']);
        update_option('hmu_api_plugin', $option['cron_name']);*/



        flush_rewrite_rules();
    }
}