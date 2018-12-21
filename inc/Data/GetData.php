<?php

/*
 *
 * NOT IN USE YET
 *
 *
 *
 */

namespace Inc\Data;


class GetData
{
	function __construct()
	{

	}

	function  hmu_main_loop($urls)
	{




		$option = get_option('hmu_api_basic');
		//$url = $option['basic_auth_url'].'Products/ALL?DateAdjusted=2018-03-25T00:00:00&WebOnly=1';
		$url = $urls;
		$user = $option["basic_auth_username"];
		$pass = $option["basic_auth_password"];


		//   echo $show = 'website: '.$url.' ck: '.$user.' cs: '.$pass;
		$data = array($url, $user, $pass);


		$wp_request_headers = array(
			'Authorization' => 'Basic ' . base64_encode( $user.':'.$pass )
		);

		$wp_request_url = $url;

		$wp_get_post_response = wp_remote_request(
			$wp_request_url,
			array(
				'method'    => 'GET',
				'headers'   => $wp_request_headers
			)
		);




		// echo wp_remote_retrieve_response_code( $wp_get_post_response ) . ' ' . wp_remote_retrieve_response_message( $wp_get_post_response );
		//$body = wp_remote_retrieve_body( $response );
		//Check for success
		if(!is_wp_error($wp_get_post_response) && ($wp_get_post_response['response']['code'] == 200 || $wp_get_post_response['response']['code'] == 201)) {
			//return $res['body'];
			/*	wp_insert_post(array(
					'post_title' => 'success',
					'post_content' => '',
					'post_status' => 'publish',
					'post_type' => "product",
				));*/





			$res = json_decode($wp_get_post_response['body']);




			$i = 1;
			$pro_arr = array();

			foreach ($res as $r) {

				$stdInstance = json_decode(json_encode($r), true);

				//if ($i == 2) {	break;	}




				//
				$name  = $stdInstance["ProductName"];
				$price = $stdInstance["SalesPrice"];
				$MRRP  = $stdInstance['MRRP'];
				$WebSalesPrice = $stdInstance['WebSalesPrice'];
				$desc  = $stdInstance["ProductName"];
				$WebLongDescription = $stdInstance["WebLongDescription"];
				$WebShortDescription = $stdInstance["WebShortDescription"];
				$attr1 = $stdInstance["Attrib1"];
				$attr2 = $stdInstance["Attrib2"];
				$attr3 = $stdInstance["Attrib3"];
				//	$code = $stdInstance["ShopCode"];
				$subgroup = $stdInstance["SubGroup"];
				$Brand = $stdInstance["Brand"];
				$ProdGroup = $stdInstance["ProdGroup"];
				$ID = $stdInstance["ProductID"];
				$webproduct = $stdInstance["WebProduct"];
				$CurrentProduct = $stdInstance['CurrentProduct'];
				$DateAdjustedOnlineStock = $stdInstance['DateAdjustedOnlineStock'];
				$StyleNumber = $stdInstance['StyleNumber'];
				//$stock = $this->hmu_display_basic_orders($ID);
				$stock = 0;

				

				$variation = array();

				if ($StyleNumber != '') {
					$db_name_1 = strtolower(str_replace(" ", "-", $Brand) . '-' . str_replace(" ", "-", $name) . '-' . $StyleNumber);
				} else {
					$db_name_1 = strtolower(str_replace(" ", "-", $Brand) . '-' . str_replace(" ", "-", $name) . '-' . $ID);
				}

				$db_name = str_replace("'", '', $db_name_1);

				$size = 'size';
				$colour = 'colour';

				if ($ProdGroup == 'Clubs') {
					if ($subgroup == 'Club Packages' || $subgroup == 'Driver Men' || $subgroup == 'Driver Ladies' || $subgroup == 'Fairways Men' || $subgroup == 'Fairways Ladies'
						|| $subgroup == 'Hybirds Men' || $subgroup == 'Hybirds Ladies'  ) {
						$colour = 'shaft';

					}
					if ($subgroup == 'Irons Men' || $subgroup == 'Irons Ladies') {
						$size = 'set-make-up';
						$colour = 'shaft';

					}
					if ($subgroup == 'Putters') {
						$size = 'length';


					}
                    if ($subgroup == 'Wedges') {
                        $colour = 'bounce';
                    }
				}


				$variation [$ID] = array(
					"attributes" => array(
						$size => $attr1, $colour => $attr2, 'hand' => $attr3
					),
					"price" => $price,
					"stock" => $stock
				);
				$pro_arr[] = array(
					"title" => $Brand . ' ' . $name,
					"name" => $db_name,
					"sku" => $ID,
					"description" => $WebLongDescription,
					"short_description" => $WebShortDescription,
					"categories" => array(
						'product_cat' => $ProdGroup,
						'subgroup' => $subgroup,
						'brands' => $Brand
					),
					"available_attributes" => array(
						$size, $colour, 'hand'
					),
					"variations" => $variation

				);


				echo '<pre>';
				//var_dump($pro_arr);
				echo '</pre>';

				$i++;
			}
		}

		else {
			/*	wp_insert_post(array(
					'post_title' => 'wrong',
					'post_content' => plugin_dir_path( __FILE__ ),
					'post_status' => 'publish',
					'post_type' => "product",
				));*/

			//	error_log("Oh no! We are out of FOOs!", 1, "nour@thebiggerboat.co.uk"); // this works
		}

		return $pro_arr;

	}


	// loop to get the stock level
	function hmu_stock_level_loop( $show_result, $urls )
	{
		$option = get_option('hmu_api_basic');
		//$url = $option['basic_auth_url'].'Products/ALL'.$this->url;
		$url = $urls;
		$user = $option["basic_auth_username"];
		$pass = $option["basic_auth_password"];




		//   echo $show = 'website: '.$url.' ck: '.$user.' cs: '.$pass;
		$data = array($url, $user, $pass);


		$wp_request_headers = array(
			'Authorization' => 'Basic ' . base64_encode( $user.':'.$pass )
		);

		$wp_request_url = $url;

		$wp_get_post_response = wp_remote_request(
			$wp_request_url,
			array(
				'method'    => 'GET',
				'headers'   => $wp_request_headers
			)
		);

		if(!is_wp_error($wp_get_post_response) && ($wp_get_post_response['response']['code'] == 200 || $wp_get_post_response['response']['code'] == 201)) {

			// echo wp_remote_retrieve_response_code( $wp_get_post_response ) . ' ' . wp_remote_retrieve_response_message( $wp_get_post_response );
			$res = json_decode($wp_get_post_response['body']);


			$i = 1;
			$level_ID = array();

			$output = "<table class=\"widefat fixed\" cellspacing=\"0\">\n\n";
			$output .= "<thead>\n\n";
			$output .= "<tr>\n\n";
			$output .= "<th > Product title </th>";
			$output .= "<th > Product SKU</th>";
			$output .= "<th> Product ID</th>";
			$output .= "<th> Stock level</th> ";


			$output .= "</tr>\n\n";
			$output .= "</thead>\n\n";
			$output .= "<tbody> \n";


			foreach ($res as $r) {

				$stdInstance = json_decode(json_encode($r), true);

				//if ($i == 50) { break; }

				$crossover_ID = $stdInstance["ProductID"];


				$ID = wc_get_product_id_by_sku($crossover_ID);

				if ($ID) {


					// $post_type = get_post_type( $ID );
					$sku = get_post_meta($ID, '_sku', true);
					$level = $this->hmu_display_basic_orders($sku);
					$title = get_the_title($ID);


					require_once(ABSPATH . 'wp-content/plugins/woocommerce/includes/class-wc-product-variable.php');

					if ($show_result == true) {


						$output .= "<tr>\n";

						$output .= "<td>" . @$title . "</td>";
						$output .= "<td>" . @$sku . "</td>";
						$output .= "<td>" . @$ID . "</td>";

						$output .= "</tr>\n";


					} else {


						$this->hmu_insert_stock($ID, $level);


						$output .= "<tr>\n";

						$output .= "<td>" . $title . "</td>";
						$output .= "<td>" . $sku . "</td>";
						$output .= "<td>" . $ID . "</td>";
						$output .= "<td>" . $level . "</td>";

						$output .= "</tr>\n";


						//$var_ids = prod_a( $ID );
						// foreach ($var_ids as $var_id ) {
						// echo $var_id['id'];
						//$level = $this->hmu_display_basic_orders($var_id['sku']);
						// $this->hmu_insert_stock($var_id['id'], $level);
						//echo 'variation WP ID: '. $var_id.' CROSSOVER id: '.$crossover_ID.' level: '.$this->hmu_display_basic_orders( $crossover_ID ).'<hr>';

						//}

						/*   $product = new WC_Product_Variable($ID);
						   $variations = $product->get_available_variations();

						   foreach ($variations as $variation) {
							   echo  $var_id = $variation['variation_id'];
							   $this->hmu_insert_stock($var_id, $level);

							   echo 'variation WP ID: '. $var_id.' CROSSOVER id: '.$crossover_ID.' level: '.$this->hmu_display_basic_orders( $crossover_ID ).'<hr>';
						   }*/


						// echo 'Parent WP ID: '.$ID.' CROSSOVER id: '.$crossover_ID.' level: '.$this->hmu_display_basic_orders( $crossover_ID ).'<hr>';

						/* if ($post_type == 'product') {
							 $this->hmu_insert_stock($ID, $level);

						 }else {
							 echo $ID;
							 $product = new WC_Product_Variable($ID);
							 $variations = $product->get_available_variations();

							 foreach ($variations as $variation) {
								echo  $var_id = $variation['variation_id'];
								 $this->hmu_insert_stock($var_id, $level);

								 echo 'variation WP ID: '. $var_id.' CROSSOVER id: '.$crossover_ID.' level: '.$this->hmu_display_basic_orders( $crossover_ID ).'<hr>';
							 }


						 }*/


					}

				} // if ($ID)


				$i++;
			}
		}else {
			echo 'Error';

			$output ='';
		}

		$output .= "</tbody> \n ";
		$output .= "\n</table>";

		echo $output;


	}

}