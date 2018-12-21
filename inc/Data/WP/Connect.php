<?php

namespace Inc\Data\WP;
use Automattic\WooCommerce\Client;

use Automattic\WooCommerce\HttpClient\HttpClientException;

use Inc\Base\BaseController;

class Connect
{

    public function hmuApiBasicConnection()
    {
        /*header('Access-Control-Allow-Origin: *');
        header('Content-type: application/json');*/


        require SITE_ROOT . '/vendor/autoload.php';

        $woocommerce ='';


        $url = 'https://www.checkfire.co.uk/';
        $ck = 'ck_08c6960cbd01fbb52f11c8b9fea46531109940c5';
        $cs = 'cs_2a465bbcce03584eaae7e9d15f0bd5325dc84e66';

        $woocommerce = new Client(

            $url,
            $ck,
            $cs,

            [
                'wp_api' => true,
                'version' => 'wc/v1',
            ]
        );


//print_r($woocommerce->get('products'));


        try {
            // Array of response results.
            $results = $woocommerce->get('products');
            $_SESSION['result'] =  $results;
            // print_r($woocommerce->get('products'));
            // Example: ['customers' => [[ 'id' => 8, 'created_at' => '2015-05-06T17:43:51Z', 'email' => ...
            // var_dump($results);

            // Last request data.
            $lastRequest = $woocommerce->http->getRequest();
            //var_dump($lastRequest);
            $lastRequest->getUrl(); // Requested URL (string).
            $lastRequest->getMethod(); // Request method (string).
            $lastRequest->getParameters(); // Request parameters (array).
            $lastRequest->getHeaders(); // Request headers (array).
            $lastRequest->getBody(); // Request body (JSON).

            // Last response data.
            $lastResponse = $woocommerce->http->getResponse();
            $lastResponse->getCode(); // Response code (int).
            $lastResponse->getHeaders(); // Response headers (array).
            $lastResponse->getBody(); // Response body (JSON).
            /* echo '<pre>';
             var_dump($lastResponse);
             echo '</pre>';*/

            echo json_encode($results );

            // INSERT
            $data = [
                'name' => 'Premium Quality tbb test',
                'type' => 'simple',
                'regular_price' => '21.99',
                'description' => 'Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. Aenean ultricies mi vitae est. Mauris placerat eleifend leo.',

            ];

            //echo json_encode($woocommerce->post('products', $data));

        } catch (HttpClientException $e) {
              var_dump($e);
            $e->getMessage(); // Error message.
            $e->getRequest(); // Last request data.
            $e->getResponse(); // Last response data.
        }


    }
}