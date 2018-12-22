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
                    <th> kinguinId</th>
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
                    <th> screenshots</th>
                    <th> videos</th>
                    <th> languages</th>
                    <th>systemRequirements</th>
                </tr>

                </thead>
                <tbody>
                <?php
                if(isset(json_decode($response)->results)):
                foreach (json_decode($response)->results as $product ) {

                    ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <?php
                        echo '<td>'. $kinguinId = isset($product->kinguinId) ? $product->kinguinId : "" .'</td>';

                        echo '<td>'.$name = $product->name.'</td>';
                        echo '<td>'. $desc = isset($product->description) ? $product->description : ""  .'</td>';
                        if( isset($product->coverImage)):
                        echo '<td><img src="'. $image = $product->coverImage.'" width="100"/></td>';
                        endif;
                        echo '<td>';
                        $developers = $product->developers;
                        if( isset($developer)):
                        foreach ($developers as $developer) {
                            echo  $developer;
                        }
                        endif;
                        echo '</td>';
                        echo '<td>';
                        $publishers = $product->publishers;
                        if( isset($publishers)):
                        foreach ($publishers as $publisher) {
                            echo  $publisher;
                        }
                        endif;
                        echo '</td>';
                        echo '<td>';
                        $genres = $product->genres;
                        if( isset($genres)):
                        foreach ($genres as $genre) {
                            echo  $genre;
                        }
                        endif;
                        echo '</td>';
                        echo '<td>'. $platform =  isset($product->platform) ? $product->platform : "".'</td>';
                        echo '<td>'. $releaseDate = isset($product->releaseDate) ? $product->releaseDate : "" .'</td>';
                      //  echo '<td>'. $is_instock = $product->stock.'</td>';
                        echo '<td>'. $qty = $product->qty.'</td>';
                        echo '<td>'. $price = $product->price.'</td>';
                        //echo '<td>'. $isPreorder = $product->isPreorder.'</td>';
                        echo '<td>'. $regionalLimitations = isset($product->regionalLimitations) ? $product->regionalLimitations : "".'</td>';
                        echo '<td>'. $regionId = isset($product->regionId) ? $product->regionId : "".'</td>';
                        echo '<td>'. $activationDetails = isset($product->activationDetails) ? $product->activationDetails : "" .'</td>';
                        echo '<td>';

                        if(isset($product->screenshots)):
                            $screenshots = $product->screenshots;
                        foreach ($screenshots as $screenshot) {
                            echo  '<img src="'.$screenshot->url.'" width="50"/>';
                        }
                        endif;
                        echo '</td>';
                        echo '<td>';

                        if(isset($product->videos)):
                            $videos = $product->videos;
                        foreach ($videos as $video) {
                            echo  $video->video_id;
                        }
                        endif;
                        echo '</td>';
                        echo '<td>';
                        $languages = $product->languages;
                        if(isset($languages)):
                        foreach ($languages as $language) {
                            echo  $language;
                        }
                        endif;
                        echo '</td>';
                        echo '<td>';
                        $systemRequirements = $product->systemRequirements;
                        if(isset($systemRequirements)):
                        foreach ($systemRequirements as $systemRequirement) {
                            echo  $system = $systemRequirement->system;
                            $requirements = $systemRequirement->requirement;
                            foreach ($requirements as $requirement){
                                echo $requirement;
                            }
                        }
                        endif;
                        echo '</td>';


                        ?>

                        <td><?php  ?></td>

                    </tr>
                   <?php

                $i++; }
                else:
                    echo 'No result found';
                endif;

            else:
                echo '<p>No products found!</p>';
            endif;
            ?>
            </tbody>




            </table>
        <?php
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