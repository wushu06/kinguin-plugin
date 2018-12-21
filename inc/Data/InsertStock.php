<?php

namespace Inc\Data;


class InsertStock
{
    //Display / get the stock level by product id
    function __construct()
    {
        include  plugin_dir_path( __FILE__ ).'insert_product.php';

    }

    function hmu_get_stock( $date, $action )
    {

        $option = get_option('hmu_api_basic');
        // $date = date("Y-m-d") ;
        //$url = $option['basic_auth_url'].'Products/ALL?DateAdjusted=2018-03-25T00:00:00&WebOnly=1';
        $url  = 'https://online.crossovertec.co.uk/StockLevels/ALL?DateAdjusted='.$date.'T00:00:00&MaxResults=1000&WebOnly=1';
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
            $output .= "<th > Product id </th>";
            $output .= "<th > Product title </th>";
            $output .= "<th > Product SKU</th>";
            $output .= "<th> Stock level</th> ";



            $output .= "</tr>\n\n";
            $output .= "</thead>\n\n";
            $output .= "<tbody> \n";

            foreach ($res as $r) {

                $stdInstance = json_decode(json_encode($r), true);
                $crossover_id = $stdInstance["ProductID"];
                $level = $stdInstance["StockLevel"];

                $result = '';
                if( $action === true ) {
                    $result = $this->hmu_insert_stock(wc_get_product_id_by_sku($crossover_id), $level);
                }


                $output .= "<tr>\n";

                $output .= "<td>" .   wc_get_product_id_by_sku($crossover_id). "</td>";
                $output .= "<td>" .   get_the_title( wc_get_product_id_by_sku($crossover_id) ). "</td>";
                $output .= "<td>" .  $crossover_id . "</td>";
                $output .= "<td>" .  $level. "</td>";

                $output .= "</tr>\n";





            }


        }
        $output .= "</tbody> \n ";
        $output .= "\n</table>";

       return $output;

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
        if( !$result ){
            $result = 'nothing returned';
        }
        return $result;

    }



}

