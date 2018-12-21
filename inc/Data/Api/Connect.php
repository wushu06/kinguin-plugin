<?php

namespace Inc\Data\Api;
use Inc\Base\BaseController;

class Connect
{

    public function hmuApiBasicConnection($method, $wp_request_url)
    {
        $option = get_option('hmu_api_basic');
        $user = $option["basic_auth_username"];
        $pass = $option["basic_auth_password"];

        $wp_request_headers = array(
            'api-ecommerce-auth' => 'fa6841f1345d4c20b6bb201a631ed3e8'
        );


        $wp_get_post_response = wp_remote_request(
            $wp_request_url,
            array(
                'method'    => $method,
                'headers'   => $wp_request_headers
            )
        );



        if(!is_wp_error($wp_get_post_response) && ($wp_get_post_response['response']['code'] == 200 || $wp_get_post_response['response']['code'] == 201)) {
            return $wp_get_post_response["body"];
        }else {
           // $base = new BaseController();
          //  $base->hmuErrorLog('Connection Error | Connect.php ', 'Message '.$wp_get_post_response['response']['code'] );

            return 'Connection Error';
        }
    }
}