<?php
/**
 * Created by PhpStorm.
 * User: nour
 * Date: 21/12/18
 * Time: 21:12
 */

namespace Inc\Data\Api;
use Inc\Base\BaseController;
use Inc\Data\Api\DataManager;


class BatchCreate
{

    public function hmuInsertData($limit)
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
                    //$publishers = $product->publishers;
                    //$releaseDate = $product->releaseDate;
                    // $is_instock = $product->stock;
                    $qty = $product->qty;
                    $price = $product->price;
                    //$isPreorder = $product->isPreorder;
                    //$regionalLimitations = $product->regionalLimitations;
                    //$kinguinId = $product->kinguinId;

                    $result = $manager->hmuCreateUpdate($post_name, $name, $desc);
                    $post_id = $result[0];
                    $msg = $result[1];
                    if (!$post_id) // If there is no post id something has gone wrong so don't proceed
                    {
                        $msg[] = 'Something went wrong! No post ID';
                        return false;
                    }

                    if(isset($product->coverImage)) $manager->insertImage($product->coverImage, $post_id);
                    if(isset($product->screenshots)) $manager->insertGalleryImages($product->screenshots, $post_id);
                    $manager->insertStockAndPrice($post_id, $qty, $price);
                    $manager->termsDispatcher($post_id, $product);
                    $manager->InsertRest($post_id, $product);
                }
            else:
                echo 'No result found!';
            endif;
        else:
            $msg[] = 'Something went wrong';
        endif;
        return $msg;
    }
}