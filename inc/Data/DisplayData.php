<?php

namespace Inc\Data;
use Inc\Data\Api\Connect;

class DisplayData
{
	public $url = '?DateAdjusted=2018-03-09T00:00:00&WebOnly=1';
	//Display / get the stock level by product id
	function __construct()
	{
		include  plugin_dir_path( __FILE__ ).'insert_product.php';

	}


	function hmu_display_basic_orders($product_id)
	{
		$option = get_option('hmu_api_basic');
		$url = $option['basic_auth_url'].'StockLevelsByID/ALL?ProductID='.$product_id;
        $connect = new Connect();
        if( $wp_get_post_response = $connect->hmuApiBasicConnection('GET', $url)){
			// echo wp_remote_retrieve_response_code( $wp_get_post_response ) . ' ' . wp_remote_retrieve_response_message( $wp_get_post_response );
			@$res = json_decode($wp_get_post_response['body']);



			// print_r( json_decode($wp_get_post_response['body']) );
			$i = 1;
			if ($res)
				foreach ($res as $r) {
					//if ($i == 50) {	break;	}
					$stdInstance = json_decode(json_encode($r), true);
					return $stock_level = $stdInstance["StockLevel"];

					$i++;
				}
		}else {
			return $stock_level = 'There has been an error';
		}

	}

	//Display all products
	function hookeMeUp_display_basic_result($urls){




        $connect = new Connect();
        if( $wp_get_post_response = $connect->hmuApiBasicConnection('GET', $urls)):



		// echo wp_remote_retrieve_response_code( $wp_get_post_response ) . ' ' . wp_remote_retrieve_response_message( $wp_get_post_response );
		$res = json_decode($wp_get_post_response['body']);
		if($res !== 'No Records' ):

			echo '<pre>';
			//print_r( json_decode($wp_get_post_response['body']) );

			echo '</pre>';





			?>
            <table class="widefat fixed" cellspacing="0">

            <thead>

            <tr>
                <th> Count</th>
                <th> ID</th>
                <th> Product name</th>
                <th> brand</th>
                <th> STOCK LEVEL</th>
                <th> price</th>
                <th> MRRP </th>
                <th> WebSalesPrice </th>
                <th> Description</th>
                <th> WebLongDescription</th>
                <th> WebShortDescription</th>
                <th> Attribute one</th>
                <th> Attribute two</th>
                <th> Attribute three</th>
                <th> Subgroup</th>
                <th> Product Group</th>
                <th> Web product</th>
                <th> Current product</th>
                <th>Date adj online</th>
                <th> Style Number</th>
            </tr>

            </thead>
            <tbody>
			<?php
			$i = 1;
			$pro_arr = array();



			foreach ($res as $r ) {

				$stdInstance   = json_decode(json_encode($r),true);

				//if ($i == 50) { break; }
				// do stuff






				//
				$name = $stdInstance["ProductName"];
				$price = $stdInstance["SalesPrice"];
				$MRRP = $stdInstance['MRRP'];
				$WebSalesPrice = $stdInstance['WebSalesPrice'];
				$desc = $stdInstance["ProductName"];
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

				?>



                <tr>

                    <td><?php echo $i; ?></td>
                    <td><?php echo $ID ; ?></td>
                    <td><?php echo $name ; ?></td>
                    <td><?php echo $Brand; ?></td>
                    <td><?php echo $this->hmu_display_basic_orders($ID); ?></td>
                    <td><?php echo $price; ?></td>
                    <td><?php echo $MRRP; ?></td>
                    <td><?php echo $WebSalesPrice; ?></td>
                    <td><?php echo $desc; ?></td>
                    <td><?php echo $WebLongDescription; ?></td>
                    <td><?php echo $WebShortDescription; ?></td>
                    <td><?php echo $attr1; ?></td>
                    <td><?php echo $attr2; ?></td>
                    <td><?php echo $attr3; ?></td>
                    <td><?php echo $subgroup; ?></td>
                    <td><?php echo $ProdGroup; ?></td>
                    <td><?php echo $webproduct; ?></td>
                    <td><?php echo $CurrentProduct; ?></td>
                    <td><?php echo $DateAdjustedOnlineStock; ?></td>
                    <td><?php echo $StyleNumber; ?></td>
                </tr>





				<?php
				$i++;
			}
		else:
			echo '<p>No products found!</p>';
		endif;
		?>
        </tbody>



        </table>
		<?php

        else:
            echo 'No connection found!';
            endif;


	}


	/*
	 * grabbing all the data
	 */
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

		// request to api
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
				$name = $stdInstance["ProductName"];
				$price = $stdInstance["SalesPrice"];
				$MRRP = $stdInstance['MRRP'];
				$WebSalesPrice = $stdInstance['WebSalesPrice'];
				$desc = $stdInstance["ProductName"];
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
				$stock = $this->hmu_display_basic_orders($ID);
				//$stock = 0;

				$variation = array();

				if ($StyleNumber != '') {
					$db_name_1 = strtolower(str_replace(" ", "-", $Brand) . '-' . str_replace(" ", "-", $name) . '-' . $StyleNumber);
				} else {
					$db_name_1 = strtolower(str_replace(" ", "-", $Brand) . '-' . str_replace(" ", "-", $name) . '-' . $ID);
				}

				//$db_name = str_replace("'", '', _name_1);
				$db_name = $this->seoUrl($db_name_1);

				$size = 'size';
				$colour = 'colour';
				/*
				 * changing attr names
				 */

				if ($ProdGroup == 'Clubs') {
					if ($subgroup == 'Club Packages' || $subgroup == 'Irons Men' || $subgroup == 'Irons Ladies' || $subgroup == 'Drivers Men' || $subgroup == 'Drivers Ladies' || $subgroup == 'Fairways Men' ||
						$subgroup == 'Fairways Ladies'	|| $subgroup == 'Hybrids Men' || $subgroup == 'Hybrids Ladies') {
						$colour = 'shaft';

					}
					if ($subgroup == 'Irons Men' || $subgroup == 'Irons Ladies' ) {
						$size = 'set-make-up';

					}
                    if ($subgroup == 'Wedges') {
                        $colour = 'bounce';
                    }
					if ($subgroup == 'Putters') {
						$size = 'length';

					}
					if ($subgroup == 'Drivers Men' || $subgroup == 'Drivers Ladies' || $subgroup == 'Fairways Men' ||$subgroup == 'Fairways Ladies'	|| $subgroup == 'Hybrids Men' || $subgroup == 'Hybrids Ladies'
						|| $subgroup == 'Wedges' ) {
						$size = 'loft';

					}
				}
				if ($ProdGroup == 'Second Hand') {
					if ($subgroup == 'Club Packages' || $subgroup == 'Irons Men' || $subgroup == 'Irons Ladies' || $subgroup == 'Drivers Men' || $subgroup == 'Drivers Ladies' || $subgroup == 'Fairways Men' ||
						$subgroup == 'Fairways Ladies'	|| $subgroup == 'Hybrids Men' || $subgroup == 'Hybrids Ladies' || $subgroup == 'Wedges') {
						$colour = 'shaft';

					}
					if ($subgroup == 'Irons Men' || $subgroup == 'Irons Ladies' ) {
						$size = 'set-make-up';

					}
					if ($subgroup == 'Drivers Men' || $subgroup == 'Drivers Ladies' || $subgroup == 'Fairways Men' ||$subgroup == 'Fairways Ladies'	|| $subgroup == 'Hybrids Men' || $subgroup == 'Hybrids Ladies'
						|| $subgroup == 'Wedges' ) {
						$size = 'loft';

					}
				}
				if ($ProdGroup == 'Junior') {
					if ($subgroup == 'Club Packages' ) {
						$colour = 'shaft';
						$size = 'Size/Age';
					}

				}

				$attr = array();
				$attr["available_attributes"] = array(

				);

                $type = "variable";
                $simple_price = 0;
                $simple_stock = 0;

				/*
				 * check if attr is empty
				 */
				if(!empty($attr3) && !empty($attr1) && !empty($attr2) ){
					$attr["available_attributes"] = array(
						$size, $colour,'hand'
					);


				}
				if(!empty($attr3) && empty($attr1) && empty($attr2) ){
					$attr["available_attributes"] = array(
						'hand'
					);

				}

				if(empty($attr3) && !empty($attr1) && empty($attr2) ){
					$attr["available_attributes"] = array(
						$size
					);
				}

				if(empty($attr3) && empty($attr1) && !empty($attr2) ){
					$attr["available_attributes"] = array(
						$colour
					);
				}

				if(!empty($attr1) &&  !empty($attr2) && empty($attr3) ) {
					$attr["available_attributes"] = array(
						$size, $colour
					);
				}
				if(!empty($attr1) &&  empty($attr2) && !empty($attr3) ) {
					$attr["available_attributes"] = array(
						$size, 'hand'
					);
				}
				if(empty($attr1) &&  !empty($attr2) && !empty($attr3) ) {
					$attr["available_attributess"] = array(
						$colour, 'hand'
					);
				}
				if(empty($attr1) &&  empty($attr2) && empty($attr3) ) { // all empty
					$attr["available_attributess"] = array(	);
					$type = "simple";
					$simple_price = $price;
					$simple_stock = $stock;
					
				}


                if($WebSalesPrice && $WebSalesPrice != 0){
                    $price = $WebSalesPrice;

                }

				/*
				 * must have style number
				 */

				if ($StyleNumber != '') {
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
						"type" => $type,
						"sku" => $ID,
						"description" => $WebLongDescription,
						"short_description" => $WebShortDescription,
						"simple_price"=>$simple_price,
						"simple_stock"=>$simple_stock,
						"categories" => array(
							'product_cat' => $ProdGroup,
							'subgroup' => $subgroup,
							'brands' => $Brand
						),
						"available_attributes" => $attr["available_attributes"],
						"variations" => $variation,
						"brands_attr"=>$Brand

					);
				}


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
			$output .= "<th> Note </th> ";


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


						$result = $this->hmu_insert_stock($ID, $level);


						$output .= "<tr>\n";

						$output .= "<td>" . $title . "</td>";
						$output .= "<td>" . $sku . "</td>";
						$output .= "<td>" . $ID . "</td>";
						$output .= "<td>" . $level . "</td>";
						$output .= "<td>" . $result . "</td>";

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

	function hmu_insert_stock($ID, $level)
	{
		update_post_meta($ID, '_stock', $level);
	
		if($level != 0) {
			update_post_meta($ID, '_stock_status', 'instock');
			update_post_meta($ID, '_manage_stock', 'yes');
		}else{
			update_post_meta($ID, '_stock_status', 'outofstock');
		}
		$result = get_post_meta($ID,'_stock' );
		return $result[0];

	}



	//oauth one display all products
	function hmu_display_oauth_1_products()
	{


		//var_dump(hookeMeUp_select_website_data());
		if (isset($_SESSION['result'])) {
			$result = $_SESSION['result'];

			?>


            <main class="main-area">

                <table class="widefat fixed" cellspacing="0">
                    <tr>

                        <th>Link</th>
                        <th>Product Name</th>
                        <th>Price</th>
                        <th>Image</th>
                    </tr>
					<?php


					//var_dump($result);
					if ($result = $_SESSION['result']) {

						$result = json_decode(json_encode($result), true);
						foreach ($result as $value) {
							$id = $value['id'];
							$name = $value['name'];
							$price = $value['price'];
							$srcs = $value['images'];
							foreach ($srcs as $src) {
								$image = $src['src'];
							}

							?>
                            <tr>
                                <td>
                                    <a href="single.php?id=<?php echo $id; ?>">Link</a></td>
                                <td><h6><?php echo $name; ?></h6></td>
                                <td><p><?php echo(!empty($price) ? '£' . $price : 'No Price'); ?></td>
                                <td><img src="<?php echo $image; ?>" alt="" width="100px" height="100px"></td>


                            </tr>
						<?php }
					} ?>


                </table>

            </main><!-- .main-area -->

			<?php

		}
	}
	public function seoUrl($string) {
		//Lower case everything
		$string = strtolower($string);
		//Make alphanumeric (removes all other characters)
		$string = preg_replace("/[^a-z0-9_\s-]/", "-", $string);
		//Clean up multiple dashes or whitespaces
		$string = preg_replace("/[\s-]+/", " ", $string);
		//Convert whitespaces and underscore to dash
		$string = preg_replace("/[\s_]/", "-", $string);
		return $string;
	}



}

