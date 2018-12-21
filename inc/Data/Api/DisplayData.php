<?php

namespace Inc\Data\Api;
use Inc\Base\BaseController;


class DisplayData
{

    //Display all products
    function hmuDisplayData($limit)
    {
      $con = new Connect();

        $i = 1;

        if( $response =  $con->hmuApiBasicConnection('GET', 'https://api2.kinguin.net/integration/v1/products?limit='.$limit)):


                ?>
                <table class="widefat " cellspacing="0">

                <thead>

                <tr>
                    <th> Count</th>
                    <th> Name</th>
                    <th> Desc</th>
                    <th> Image</th>
                    <th> Developers</th>
                    <th> Publishers</th>
                    <th> Genres </th>
                    <th> Platform </th>
                    <th> releaseDate</th>
                    <th> qty</th>
                    <th> price</th>
                    <th> regionalLimitations</th>
                    <th> regionId</th>
                    <th> activationDetails</th>
                    <th> kinguinId</th>
                    <th> screenshots</th>
                    <th> videos</th>
                    <th> languages</th>
                    <th>systemRequirements</th>
                </tr>

                </thead>
                <tbody>
                <?php

                foreach (json_decode($response)->results as $product ) {



                    ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <?php
                        echo '<td>'.$name = $product->name.'</td>';
                        echo '<td>'. $desc = $product->description.'</td>';
                        echo '<td><img src="'. $image = $product->coverImage.'" width="100"/></td>';
                        echo '<td>';
                        $developers = $product->developers;
                        foreach ($developers as $developer) {
                            echo  $developer;
                        }
                        echo '</td>';
                        echo '<td>';
                        $publishers = $product->publishers;
                        foreach ($publishers as $publisher) {
                            echo  $publisher;
                        }
                        echo '</td>';
                        echo '<td>';
                        $genres = $product->genres;
                        foreach ($genres as $genre) {
                            echo  $genre;
                        }
                        echo '</td>';
                        echo '<td>'. $platform = $product->platform.'</td>';
                        echo '<td>'. $releaseDate = $product->releaseDate.'</td>';
                      //  echo '<td>'. $is_instock = $product->stock.'</td>';
                        echo '<td>'. $qty = $product->qty.'</td>';
                        echo '<td>'. $price = $product->price.'</td>';
                        //echo '<td>'. $isPreorder = $product->isPreorder.'</td>';
                        echo '<td>'. $regionalLimitations = $product->regionalLimitations.'</td>';
                        echo '<td>'. $regionId = $product->regionId.'</td>';
                        echo '<td>'. $activationDetails = $product->activationDetails.'</td>';
                        echo '<td>'. $kinguinId = $product->kinguinId.'</td>';
                        echo '<td>';
                        $screenshots = $product->screenshots;
                        foreach ($screenshots as $screenshot) {
                            echo  '<img src="'.$screenshot->url.'" width="50"/>';
                        }
                        echo '</td>';
                        echo '<td>';
                        $videos = $product->videos;
                        foreach ($videos as $video) {
                            echo  $video->video_id;
                        }
                        echo '</td>';
                        echo '<td>';
                        $languages = $product->languages;
                        foreach ($languages as $language) {
                            echo  $language;
                        }
                        echo '</td>';
                        echo '<td>';
                        $systemRequirements = $product->systemRequirements;
                        foreach ($systemRequirements as $systemRequirement) {
                            echo  $system = $systemRequirement->system;
                            $requirements = $systemRequirement->requirement;
                            foreach ($requirements as $requirement){
                                echo $requirement;
                            }
                        }
                        echo '</td>';


                        ?>

                        <td><?php  ?></td>

                    </tr>
                   <?php

                $i++; }
            else:
                echo '<p>No products found!</p>';
            endif;
            ?>
            </tbody>




            </table>
        <?php



    }

    function hmuInsertData($limit)
    {

        $con = new Connect();
        global $wpdb;
        $postdate = date("Y-m-d H:i:s");
        $base = new BaseController();


        // check if the post already exists
        //$count = get_page_by_title($product_data['title'], OBJECT, 'product');



        if( $response =  $con->hmuApiBasicConnection('GET', 'https://api2.kinguin.net/integration/v1/products?limit='.$limit)):
            foreach (json_decode($response)->results as $product ) {
                $name = $product->name;
                $post_name = $base::hmuSeoUrl($name);
                $desc = $product->description;
                $image = $product->coverImage;
                $developers = $product->developers;

                $publishers = $product->publishers;

                $genres = $product->genres;

                $platform = $product->platform;
                $releaseDate = $product->releaseDate;
                $is_instock = $product->stock;
                $qty = $product->qty;
                $price = $product->price;
                $isPreorder = $product->isPreorder;
                $regionalLimitations = $product->regionalLimitations;
                $regionId = $product->regionId;
                $activationDetails = $product->activationDetails;
                $kinguinId = $product->kinguinId;
                $screenshots = $product->screenshots;

                $videos = $product->videos;

                $languages = $product->languages;

                $systemRequirements = $product->systemRequirements;


                /*
                 * insert product
                 */

                $post_id = $wpdb->get_var($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE post_name = %s ", $post_name));

                if ($post_id) {

                    $wpdb->update(
                        $wpdb->posts,
                        array(
                            'post_title' => $name,
                            'post_name' => $post_name,
                            'post_content'=> $desc,
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

                    $msg[] = 'Product has been updated ID: '.$post_id. ' Title: '.$name;

                }else{

                   $wpdb->insert(
                        $wpdb->posts,
                            array(
                                'post_title' => $name,
                                'post_name' => $post_name,
                                'post_content'=> $desc,
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
			         $post_id = $wpdb->insert_id;




                    $msg[] = 'Porduct has been insert ID: '.$post_id. ' Title: '.$name;
                }
                if (!$post_id) // If there is no post id something has gone wrong so don't proceed
                {
                    $msg[] = 'Something went wrong! No post ID';
                    return false;
                }


                $this->insertImage($image, $post_id);
                $this->insertGalleryImages($screenshots, $post_id);
                $this->insertStock($post_id, $qty);
                foreach ($developers as $developer) {
                    $this->insertTerms($post_id,'developers', $developer, 'product_cat');
                }
                foreach ($genres as $genre) {
                    $this->insertTerms($post_id,'genres', $genre, 'product_cat');

                }
                foreach ($languages as $language) {
                    $this->insertTerms($post_id,'languages', $language, 'product_cat');

                }
                $this->insertTerms($post_id,'platforms',   $platform, 'product_cat');


                update_post_meta($post_id, '_visibility', 'visible'); // Set the product to visible, if not it won't show on the front end
                update_post_meta($post_id, '_price', $price);
                update_post_meta($post_id, '_regular_price', $price);
                update_post_meta($post_id, '_sku', $regionId.'_'.$post_id);
                foreach ($systemRequirements as $systemRequirement) {
                    $system = $systemRequirement->system;
                    $requirements = $systemRequirement->requirement;
                    foreach ($requirements as $requirement){
                        update_field('requirements', $requirement, $post_id);

                    }
                }
                update_field('activation_details', $activationDetails, $post_id);
                foreach ($videos as $video) {

                    update_field('video', $video->video_id, $post_id);

                }




            }
        else:
            $msg[] = 'Something went wrong';
        endif;
        return $msg;
    }

    public function insertStock($post_id, $qty)
    {
        if ($qty != 0) {
            update_post_meta($post_id, '_stock', $qty);
            update_post_meta($post_id, '_stock_status', 'instock');
            update_post_meta($post_id, '_manage_stock', 'yes');
        }else{
            update_post_meta($post_id, '_stock_status', 'outofstock');
        }

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




}