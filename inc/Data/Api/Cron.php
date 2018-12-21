<?php
namespace Inc\Data\Api;

class Cron
{
    public function __construct()
    {


        add_filter('cron_schedules', array($this, 'hmu_cron_recurrence_interval') );
        $cron_name = 'hmu_kenguin_stock';
        if (!wp_next_scheduled($cron_name )) {
            wp_schedule_event(time(), 'every_two_minute', $cron_name );
        }

        add_action($cron_name , array($this,'hmu_kenguin_stock_function') );


       // wp_clear_scheduled_hook( 'hmu-update-stock' );


    }



    function hmu_cron_recurrence_interval($schedules) {

        $schedules['every_thirty_minutes'] = array(
            'interval' => 1800,
            'display' => __('Every 30 Minutes', 'textdomain')
        );

        $schedules['every_two_minute'] = array(
            'interval' => 120,
            'display' => __('Every 2 Minutes', 'textdomain')
        );
        $schedules['every_three_minutes'] = array(
            'interval' => 180,
            'display' => __('Every 3 Minutes', 'textdomain')
        );
        $schedules['every_fifteen_minutes'] = array(
            'interval' => 900,
            'display' => __('Every 15 Minutes', 'textdomain')
        );

        return $schedules;
    }


    function hmu_kenguin_stock_function()
    {


    }

}