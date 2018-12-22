<?php


namespace Inc\Data\Api;
use Inc\Base\BaseController;

class DataManager
{

   public function hmuCreateUpdate($post_name, $name, $desc)
   {
       global $wpdb;
       $postdate = date("Y-m-d H:i:s");
       $post_id = $wpdb->get_var($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE post_name = %s ",
           $post_name));
       if($post_id){
           $wpdb->update(
               $wpdb->posts,
               array(
                   'post_title' => $name,
                   'post_name' => $post_name,
                   'post_content' => $desc,
                   'post_status' => 'publish',
                   'post_type' => 'product',
                   'post_date' => $postdate
               ),
               array(
                   'ID' => $post_id
               ),

               array(
                   '%s',
                   '%s',
                   '%s',
                   '%s',
                   '%s',

               )
           );

           $msg[] = 'Product has been updated ID: ' . $post_id . ' Title: ' . $name;
       }else{
           $wpdb->insert(
               $wpdb->posts,
               array(
                   'post_title' => $name,
                   'post_name' => $post_name,
                   'post_content' => $desc,
                   'post_status' => 'publish',
                   'post_type' => 'product',
                   'post_date' => $postdate
               ),
               array(
                   '%s',
                   '%s',
                   '%s',
                   '%s',
                   '%s',

               )
           );
           $msg[] = 'Product has been created ID: ' . $post_id . ' Title: ' . $name;
           $post_id = $wpdb->insert_id;


       }


      return array($post_id, $msg);
   }

    public function insertStockAndPrice($post_id, $qty, $price)
    {
        update_post_meta($post_id, '_visibility', 'visible'); // Set the product to visible, if not it won't show on the front end
        update_post_meta($post_id, '_price', $price);
        update_post_meta($post_id, '_regular_price', $price);

        if ($qty != 0) {
            update_post_meta($post_id, '_stock', $qty);
            update_post_meta($post_id, '_stock_status', 'instock');
            update_post_meta($post_id, '_manage_stock', 'yes');
        }else{
            update_post_meta($post_id, '_stock_status', 'outofstock');
        }

    }

    public function termsDispatcher($post_id, $product)
    {
        if(isset($product->developers )) {
            $developers = $product->developers;
            foreach ($developers as $developer) {
                $this->insertTerms($post_id, 'developers', $developer, 'product_cat');
            }
        }
        if(isset($product->genres)) {
            $genres = $product->genres;
            foreach ($genres as $genre) {
                $this->insertTerms($post_id, 'genres', $genre, 'product_cat');

            }
        }
        if(isset($product->languages)) {
            $languages = $product->languages;
            foreach ($languages as $language) {
                $this->insertTerms($post_id, 'languages', $language, 'product_cat');

            }
        }
        if(isset($product->platform)) $this->insertTerms($post_id,'platforms',   $product->platform, 'product_cat');

    }
    public function insertTerms($post_id,$term, $sub_cat, $tax)
    {
        wp_set_object_terms($post_id, $term, $tax, true); // Set up its categories

        if ($sub_cat) {
            // insert sub categories
            $sub_cat_exist = term_exists($sub_cat, $tax);


            if ($sub_cat_exist) {
                wp_set_post_terms($post_id, $sub_cat_exist['term_id'], $tax, true);

            } else {

                $parent_term = term_exists($term, $tax); // array is returned if taxonomy is given
                $parent_term_id = $parent_term['term_id'];         // get numeric term id

                $child_term = wp_insert_term(
                    $sub_cat,   // the term
                    $tax, // the taxonomy
                    array(
                        'parent' => $parent_term_id,
                    )
                );

                $sub_id = $child_term['term_id'];

                wp_set_post_terms($post_id, $sub_id, $tax, true);
            }

        }

    }

    public function insertImage( $image, $post_id)
    {
        if ( has_post_thumbnail($post_id) ) {
            return;
        }

        require_once(ABSPATH . 'wp-admin/includes/media.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/image.php');

// example image

// magic sideload image returns an HTML image, not an ID
        $media = media_sideload_image($image, $post_id);

// therefore we must find it so we can set it as featured ID
        if(!empty($media) && !is_wp_error($media)){
            $args = array(
                'post_type' => 'attachment',
                'posts_per_page' => -1,
                'post_status' => 'any',
                'post_parent' => $post_id
            );

            // reference new image to set as featured
            $attachments = get_posts($args);

            if(isset($attachments) && is_array($attachments)){
                foreach($attachments as $attachment){
                    // grab source of full size images (so no 300x150 nonsense in path)
                    $image = wp_get_attachment_image_src($attachment->ID, 'full');
                    // determine if in the $media image we created, the string of the URL exists
                    if(strpos($media, $image[0]) !== false){
                        // if so, we found our image. set it as thumbnail
                        set_post_thumbnail($post_id, $attachment->ID);
                        // only want one image
                        break;
                    }
                }
            }
        }
    }

    public function insertGalleryImages($images, $product_id)
    {
        $ids = array();
        $meta = get_post_meta($product_id, '_product_image_gallery');
        if(!empty(array_filter($meta))){

            return;
        }

        foreach ($images as $image)
        {
            $ids['ids'] = $this->attach_image($image->url,  $product_id);
        }


        update_post_meta($product_id, '_product_image_gallery', implode(',', $ids['ids']));
    }

    function attach_image ($image,  $post_id)
    {
        require_once(ABSPATH . 'wp-admin/includes/media.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/image.php');

// example image

// magic sideload image returns an HTML image, not an ID

        $media = media_sideload_image($image, $post_id);

        if(!empty($media) && !is_wp_error($media)) {
            $args = array(
                'post_type' => 'attachment',
                'posts_per_page' => -1,
                'post_status' => 'any',
                'post_parent' => $post_id
            );

            // reference new image to set as featured
            $attachments = get_posts($args);
            foreach($attachments as $attachment){
                // grab source of full size images (so no 300x150 nonsense in path)
                $ids[] = $attachment->ID;
                // determine if in the $media image we created, the string of the URL exists

            }
        }
        return $ids;

    }

    public function InsertRest($post_id, $product)
    {
        if(isset($product->regionId)) update_post_meta($post_id, '_sku', $product->regionId.'_'.$post_id);
        if(isset($product->systemRequirements)) {
            $systemRequirements = $product->systemRequirements;
            foreach ($systemRequirements as $systemRequirement) {
                $system = $systemRequirement->system;
                $requirements = $systemRequirement->requirement;
                foreach ($requirements as $requirement) {
                    update_field('requirements', $requirement, $post_id);

                }
            }
        }
        if(isset($product->activationDetails)) update_field('activation_details', $product->activationDetails, $post_id);
        if(isset($product->videos)) {
            $videos = $product->videos;
            foreach ($videos as $video) {
                update_field('video', $video->video_id, $post_id);
            }
        }

    }



}