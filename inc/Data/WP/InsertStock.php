<?php

namespace Inc\Data\WP;


class InsertStock
{

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

