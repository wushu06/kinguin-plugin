<?php
/*
 * Pulling products and stock
 * Stock is pulled separate
 *
 */
namespace Inc\Data\Api;
use Inc\Base\BaseController;

class Pull
{
    public function  hmuGenerateProductArray($url)
    {
        $base = new BaseController();
        $connect = new Connect();
        if( $wp_get_post_response = $connect->hmuApiBasicConnection('GET', $url)){

            $res = json_decode($wp_get_post_response['body']);
            $i = 1;
            $pro_arr = array();

            foreach ($res as $r) {

                $stdInstance = json_decode(json_encode($r), true);

                //if ($i == 2) {	break;	}

                //
                $name = $stdInstance["ProductName"];
                $price = $stdInstance["SalesPrice"];
                $MRRP = $stdInstance['MRRP'];
                $WebSalesPrice = $stdInstance['WebSalesPrice'];
                $desc = $stdInstance["ProductName"];
                $WebLongDescription = $stdInstance["WebLongDescription"];
                $WebShortDescription = $stdInstance["WebShortDescription"];
                $attr1 = $stdInstance["Attrib1"];
                $attr2 = $stdInstance["Attrib2"];
                $attr3 = $stdInstance["Attrib3"];
                //	$code = $stdInstance["ShopCode"];
                $subgroup = $stdInstance["SubGroup"];
                $Brand = $stdInstance["Brand"];
                $ProdGroup = $stdInstance["ProdGroup"];
                $ID = $stdInstance["ProductID"];
                $webproduct = $stdInstance["WebProduct"];
                $CurrentProduct = $stdInstance['CurrentProduct'];
                $DateAdjustedOnlineStock = $stdInstance['DateAdjustedOnlineStock'];
                $StyleNumber = $stdInstance['StyleNumber'];
                $stock = $this->hmuPullStock($ID);
                //$stock = 0;

                $variation = array();
                $attr["available_attributes"] = array(
                    'size', 'colour','hand'
                );
                $type = "variable";
                //$type = "simple";


                if ($StyleNumber != '') {
                    $variation [$ID] = array(
                        "attributes" => array(
                            'size' => $attr1, 'colour' => $attr2, 'hand' => $attr3
                        ),
                        "price" => $price,
                        "stock" => $stock
                    );
                    $pro_arr[] = array(
                        "title" => $Brand . ' ' . $name,
                        "name" => $base::hmuSeoUrl($name),
                        "type" => $type,
                        "sku" => $ID,
                        "description" => $WebLongDescription,
                        "short_description" => $WebShortDescription,
                        "simple_price"=>$price,
                        "simple_stock"=>$stock,
                        "categories" => array(
                            'product_cat' => $ProdGroup,
                            'subgroup' => $subgroup,
                            'brands' => $Brand
                        ),
                        "available_attributes" => $attr["available_attributes"],
                        "variations" => $variation,
                        "brands_attr"=>$Brand

                    );
                }


                $i++; // used to break large data
            }
        }

        else {
            echo 'No connection found!';

            $base->hmuErrorLog('Connection Error | Pull.php ', 'Broken connection' );

        }

        return $pro_arr;

    }

    public function hmuPullStock($product_id)
    {
        $option = get_option('hmu_api_basic');
        $url = $option['basic_auth_url'].'StockLevelsByID/ALL?ProductID='.$product_id;
        $connect = new Connect();
        if( $wp_get_post_response = $connect->hmuApiBasicConnection('GET', $url)){
            @$res = json_decode($wp_get_post_response['body']);
            if ($res)
                foreach ($res as $r) {
                    //if ($i == 50) {	break;	}
                    $stdInstance = json_decode(json_encode($r), true);
                    return $stock_level = $stdInstance["StockLevel"];

                }
        }else {
            return $stock_level = 'There has been an error';
        }

    }
}