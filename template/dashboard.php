<?php

use Inc\Base\BaseController;
$control = new BaseController();

use Inc\Data\Api\DisplayData;
$displayData = new DisplayData();

use Inc\Data\InsertProducts;
$insert_product = new InsertProducts();

use Inc\Data\InsertStock;
$insert_stock = new InsertStock();

$option = get_option('hmu_api_basic');




?>
<?php include 'kingguin.php'; ?>

    <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>


    <div class="wrap">
		<?php settings_errors(); ?>
		<?php
		$active_tab = '';
		if( isset( $_GET[ 'tab' ] ) )
			$active_tab = $_GET[ 'tab' ];
		?>

		<?php


		if( isset( $_GET[ 'delete' ] ) && $_GET[ 'delete' ] =='ba'  ) {


			delete_option('hmu_api_basic');
			$url = admin_url() . '?page=hmu_api_plugin&tab=basic_auth';
			header('Location: ' . $url);
			die();
		}

		?>




        <h2 class="nav-tab-wrapper">
            <a href="?page=hmu_api_plugin&tab=basic_auth" class="nav-tab <?php echo ( $_GET[ 'tab' ] ==''  ||  $_GET[ 'tab' ] =='basic_auth'  ) ? 'tab-active' :  '';
			?> <?php
			echo ( $_GET[ 'tab' ] ==''  ) ?
				$_GET[ 'tab' ] :  ''; ?>">Basic
                Auth</a>
            <a href="?page=hmu_api_plugin&tab=all_products" class="nav-tab <?php echo  ( $_GET[ 'tab' ] =='all_products' ) ? 'tab-active' :  ''; ?>">All Porducts</a>
            <a href="?page=hmu_api_plugin&tab=cron" class="nav-tab <?php echo  ( $_GET[ 'tab' ] =='cron' ) ? 'tab-active' :  ''; ?>">Cron</a>
        </h2>
        <!-- ===========================================================================================================

                       MAIN TAG - init the plugin main functions

     =============================================================================================================-->


        <div class="container">

			<?php
			if( $active_tab == 'basic_auth' || $active_tab == ''  ):
				?>


                <form method="post" class="hmu-general-form" action="options.php">
                    <?php
                    settings_fields( 'hmu_api_basic_auth_options_group' );
                    do_settings_sections( 'basic_auth' );
                    submit_button( 'Save Settings', 'hmu-btn hmu-primary', 'btnSubmit' );
                    ?>

                </form>
                <!-- ==== received data  ===== -->

				<?php

				$option = get_option('hmu_api_basic');
				$url = $option['basic_auth_url'];
				$username = $option["basic_auth_username"];
				$password = $option["basic_auth_password"];

				?>

                <h3>Basic Control</h3>


                <?php include 'insert_products.php'; ?>
                <hr>
                <h3>Website:</h3>

                <table class="widefat fixed" cellspacing="0">

                    <thead>

                    <tr>

                        <th> Website Url</th>
                        <th> Key</th>
                        <th> Value</th>
                    </tr>

                    </thead>
                    <tbody>
                    <tr>
                        <td><?php echo $url; ?></td>
                        <td><?php echo $username; ?></td>
                        <td><input id="hmuInput" type="password" value="<?php echo $password; ?>" disabled><i class="fas fa-eye"></i></td>
                    </tr>
                    </tbody>

                </table>

                <hr>
                <br/>
                <a class=" hmu-input hmu-delete" href="<?php echo admin_url() ?>?page=hmu_api_plugin&tab=basic_auth&delete=ba">Delete table</a>
		<!-- ===============================================================================================

                   Product tag - display products

           =============================================================================================================-->

			<?php elseif ($active_tab == 'all_products'): ?>
                <?php include 'display_products.php'; ?>

        <!-- ===========================================================================================================

                Cron tag - add and delete crons

        =============================================================================================================-->
			<?php elseif ($active_tab == 'cron'): ?>
                <h1>Cron</h1>

                <form method="post" class="hmu-general-form" action="options.php">
					<?php
					settings_fields( 'hmu_api_dashboard_third_group' );
					do_settings_sections( 'hmu_api_cron' );
					submit_button( 'Save Settings', 'hmu-btn hmu-primary', 'btnSubmit' );
					?>

                </form>
                <hr>

                <form method="POST" action="">
                    <input class="hmu-input hmu-delete" name="delete_cron" type="submit" value="Delete cron task">
                </form>

				<?php



				if(isset($_POST['delete_cron'])){

					$option = get_option ('hmu_api_cron');
					wp_clear_scheduled_hook( 'hmu-update-stock' );
					wp_clear_scheduled_hook( 'hmu-update-products' );

					delete_option('hmu_api_cron');
					$default = array();
					add_option('hmu_api_cron', $default);
					$output = get_option('hmu_api_cron');
					$output = array();




					echo 'Task has been deleted';

					/*if ( $option && !empty($option) ) {
						foreach ($option as $key => $value) {
							$cron_name_row = $value['cron_name'];
							$cron_name_nospace = preg_replace("/[\s_]/", "-", $cron_name_row);
							$cron_name = 'hmu-api-' . $cron_name_nospace;
							wp_clear_scheduled_hook($cron_name);

							delete_option('hmu_api_cron');

							add_option('hmu_api_cron', $default);
							$output = get_option('hmu_api_cron');
							$output = array();


							echo 'Task has been deleted';
						}
					}*/
				}






				?>
				<?php
				$option  = get_option ('hmu_api_cron');
				if( $option ):
					/*  if( $option['error'] ){
						  echo 'File couldnt be moved';
						  exit();

					  }*/


					$output ="<table class='widefat fixed' cellspacing='0'>\n\n";
					$output .= "<thead>\n\n";
					$output .= "<tr>\n\n";
					$output .= "<th > Task ID</th>";
					$output .= "<th> Task Name</th>";
					$output .= "<th> Task Schedule</th>";


					$output .= "</tr>\n\n";
					$output .= "</thead>\n\n";
					$output .= "<tbody> \n";



					foreach (@$option as $key => $value) {
						$output .= "<tr>\n";
						$output .=  "<td>" . $key . "</td>";
						$output .=  "<td>" .$value['cron_name']. "</td>";
						$output .=  "<td>" .$value['cron_time']. "</td>";
						$output .= "</tr>\n";

					}

					$output .= "</tbody> \n ";
					$output .= "\n</table>";

					echo $output;


				endif;




				?>



			<?php endif; ?>



        </div>




    </div><!-- container -->


<?php



