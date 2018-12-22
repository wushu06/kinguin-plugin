<?php
namespace Inc\Data\Api;
use Inc\Data\Api\PrepareForCron;

class Cron
{
    public function __construct()
    {
        add_filter('cron_schedules', array($this, 'hmu_cron_recurrence_interval') );
        $stock_price = 'hmu_kenguin_stock_and_price';
        if (!wp_next_scheduled($stock_price )) {
            wp_schedule_event(time(), 'every_two_minute', $stock_price );
        }
        add_action($stock_price , array($this,'hmu_kenguin_stock_function') );

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
        $prepare = new PrepareForCron();
        $prepare->hmuCronUpdateStockAndPrice(10);
    }

}