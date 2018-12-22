<?php

namespace Inc\Data\Api;
use Inc\Base\BaseController;
use Inc\Data\Api\DataManager;

class Create
{
    public function hmuInsertSingleData($id)
    {
        $con = new Connect();
        $manager = new DataManager();
        global $wpdb;
        $postdate = date("Y-m-d H:i:s");
        $base = new BaseController();
        $msg = array();
        if( $response =  $con->hmuApiBasicConnection('GET', 'https://api2.kinguin.net/integration/v1/products/'.$id)):
            $product = json_decode($response);

            if(isset($product) && !empty($product)) {
                // foreach ($response as $product ) {
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

                if (isset($product->coverImage)) {
                    $manager->insertImage($product->coverImage, $post_id);
                }
                if (isset($product->screenshots)) {
                    $manager->insertGalleryImages($product->screenshots, $post_id);
                }
                $manager->insertStock($post_id, $qty);
                if (isset($product->developers)) {
                    $developers = $product->developers;
                    foreach ($developers as $developer) {
                        $manager->insertTerms($post_id, 'developers', $developer, 'product_cat');
                    }
                }
                if (isset($product->genres)) {
                    $genres = $product->genres;
                    foreach ($genres as $genre) {
                        $manager->insertTerms($post_id, 'genres', $genre, 'product_cat');

                    }
                }
                if (isset($product->languages)) {
                    $languages = $product->languages;
                    foreach ($languages as $language) {
                        $manager->insertTerms($post_id, 'languages', $language, 'product_cat');

                    }
                }
                if (isset($product->platform)) {
                    $manager->insertTerms($post_id, 'platforms', $product->platform, 'product_cat');
                }

                update_post_meta($post_id, '_visibility',
                    'visible'); // Set the product to visible, if not it won't show on the front end
                update_post_meta($post_id, '_price', $price);
                update_post_meta($post_id, '_regular_price', $price);
                if (isset($product->regionId)) {
                    update_post_meta($post_id, '_sku', $product->regionId . '_' . $post_id);
                }
                if (isset($product->systemRequirements)) {
                    $systemRequirements = $product->systemRequirements;
                    foreach ($systemRequirements as $systemRequirement) {
                        $system = $systemRequirement->system;
                        $requirements = $systemRequirement->requirement;
                        foreach ($requirements as $requirement) {
                            update_field('requirements', $requirement, $post_id);

                        }
                    }
                }
                if (isset($product->activationDetails)) {
                    update_field('activation_details', $product->activationDetails, $post_id);
                }
                if (isset($product->videos)) {
                    $videos = $product->videos;
                    foreach ($videos as $video) {

                        update_field('video', $video->video_id, $post_id);

                    }
                }

                // }
            }else{
                $msg[] = 'No product found with this id '.$id;
            }
        else :
            $msg[] = 'Something went wrong';
        endif;

        return $msg;
    }
}