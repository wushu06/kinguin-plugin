<?php

namespace Inc\Data;
use Inc\Data\DisplayData;
use Inc\Data\InsertProducts;
use Inc\Data\InsertStock;

class Cron extends DisplayData
{
	public $option ;
	public $cron;
	public $insert;
	public $display;



	public function __construct()
	{
		$this->display_data = new DisplayData();
		$this->insert = new InsertProducts();

		$option  = get_option ('hmu_api_cron');
		if($option )

			foreach ($option  as $key => $value) {

				@$cron_time  =  $value['cron_time'] ;
				//$cron_name_row = $value['cron_name'];
				//$cron_name_nospace = preg_replace("/[\s_]/", "-",  $cron_name_row);
				$cron_select = $value['cron_task'];
				$cron_name = 'hmu-'.$cron_select;
				if(isset($cron_time) && !empty($cron_time)) {

					add_filter('cron_schedules', array($this, 'hmu_cron_recurrence_interval') );

					if (!wp_next_scheduled($cron_name )) {
						wp_schedule_event(time(), $cron_time, $cron_name );
					}

					add_action($cron_name , array($this,'hmu_upload_file_fun') );

				}else {

					wp_clear_scheduled_hook( 'hmu-update-stock' );
					wp_clear_scheduled_hook( 'hmu-update-products' );
				}
			}


	}



	function hmu_cron_recurrence_interval($schedules) {

		$schedules['every_thirty_minutes'] = array(
			'interval' => 1800,
			'display' => __('Every 30 Minutes', 'textdomain')
		);

		$schedules['every_one_minute'] = array(
			'interval' => 60,
			'display' => __('Every 1 Minutes', 'textdomain')
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


	function hmu_upload_file_fun()
	{



		@$option  = get_option ('hmu_api_cron');
		$cron_task = $option['cron_task'];
		$option_url = get_option('hmu_api_basic');

		$st = new InsertStock();


		//$date = "2018-05-11"; // date("Y-m-d");
		 $date =  date("Y-m-d");
		$url = $option_url['basic_auth_url'].'Products/ALL?DateAdjusted='.$date.'T00:00:00&WebOnly=1';

		if($option):

			foreach ($option as $key => $value) {
				if ($value['cron_task'] == 'update-products') {

					$data_array = $this->hmu_main_loop($url);
					$this->insert->insert_all($data_array);

				}

				if ($value['cron_task']== 'update-stock') {

					//$this->hmu_stock_level_loop(false, $url);
                    $st->hmu_get_stock( $date, true );
				}
			}
		endif;


			   /*wp_insert_post(array(
					'post_title' => 'test',
					'post_content' => 'content',
					'post_status' => 'publish',
					'post_type' => "product",
				));*/

	}






} // end class