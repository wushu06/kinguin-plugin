<?php

namespace Inc\Data\Api;
use Inc\Base\BaseController;
use Inc\Data\Api\DataManager;

class PrepareForCron
{


    function hmuCronUpdateStockAndPrice($limit)
    {

        $con = new Connect();
        global $wpdb;
        $base = new BaseController();
        if( $response =  $con->hmuApiBasicConnection('GET', 'https://api2.kinguin.net/integration/v1/products?limit='.$limit)):
            foreach (json_decode($response)->results as $product ) {
                $name = $product->name;
                $post_name = $base::hmuSeoUrl($name);
                $is_instock = $product->stock;
                $qty = $product->qty;
                $price = $product->price;

                $post_id = $wpdb->get_var($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE post_name = %s ", $post_name));

                if ($post_id) {
                    $manager = new DataManager();
                    $manager->insertStockAndPrice($post_id, $qty, $price);
                }
            }

        endif;

    }

    public function hmuCreateBasicEntries($limit)
    {
        $con = new Connect();
        $manager = new DataManager();
        $base = new BaseController();

        if( $response =  $con->hmuApiBasicConnection('GET', 'https://api2.kinguin.net/integration/v1/products?limit='.$limit)):
            if(isset(json_decode($response)->results)):
                foreach (json_decode($response)->results as $product ) {
                    $name = $product->name;
                    $post_name = $base::hmuSeoUrl($name);
                    $desc = isset($product->description) ? $product->description : "";
                   $manager->hmuCreateUpdate($post_name, $name, $desc);
                }

            endif;
        endif;
    }

    public function hmuInsertImages($limit)
    {
        $con = new Connect();
        global $wpdb;
        $base = new BaseController();
        if( $response =  $con->hmuApiBasicConnection('GET', 'https://api2.kinguin.net/integration/v1/products?limit='.$limit)):
            foreach (json_decode($response)->results as $product ) {
                $name = $product->name;
                $post_name = $base::hmuSeoUrl($name);

                $post_id = $wpdb->get_var($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE post_name = %s ",
                    $post_name));
                if ($post_id) {
                    $manager = new DataManager();
                    if(isset($product->coverImage)) $manager->insertImage($product->coverImage, $post_id);

                }

            }
        endif;

    }
    public function hmuInsertGallery($limit)
    {
        $con = new Connect();
        global $wpdb;
        $base = new BaseController();
        if( $response =  $con->hmuApiBasicConnection('GET', 'https://api2.kinguin.net/integration/v1/products?limit='.$limit)):
            foreach (json_decode($response)->results as $product ) {
                $name = $product->name;
                $post_name = $base::hmuSeoUrl($name);

                $post_id = $wpdb->get_var($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE post_name = %s ",
                    $post_name));
                if ($post_id) {
                    $manager = new DataManager();
                    if(isset($product->screenshots)) $manager->insertGalleryImages($product->screenshots, $post_id);

                }

            }
        endif;

    }

    public function hmuInsertCategories($limit)
    {
        $con = new Connect();
        global $wpdb;
        $base = new BaseController();
        if( $response =  $con->hmuApiBasicConnection('GET', 'https://api2.kinguin.net/integration/v1/products?limit='.$limit)):
            foreach (json_decode($response)->results as $product ) {
                $name = $product->name;
                $post_name = $base::hmuSeoUrl($name);

                $post_id = $wpdb->get_var($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE post_name = %s ",
                    $post_name));
                if ($post_id) {
                    $manager = new DataManager();
                    $manager->termsDispatcher($post_id, $product);

                }

            }
        endif;

    }

    public function hmuInsertRest($limit)
    {
        $con = new Connect();
        global $wpdb;
        $base = new BaseController();
        if( $response =  $con->hmuApiBasicConnection('GET', 'https://api2.kinguin.net/integration/v1/products?limit='.$limit)):
            foreach (json_decode($response)->results as $product ) {
                $name = $product->name;
                $post_name = $base::hmuSeoUrl($name);

                $post_id = $wpdb->get_var($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE post_name = %s ",
                    $post_name));
                if ($post_id) {
                    $manager = new DataManager();
                    $manager->InsertRest($post_id, $product);
                }

            }
        endif;

    }



}