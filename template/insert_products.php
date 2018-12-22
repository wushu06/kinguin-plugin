<?php
use Inc\Data\Api\Create;
$create = new Create();

?>
    <h1>Create products by range</h1>

    <form action="" method="post">
        <input class="hmu-btn hmu-primary" type="submit" name="insert_all_products" value="Insert Products">
        <input name="range" type="text" id="range" size="30" placeholder="Products range ie: 1,50">
    </form>

<?php
if( isset( $_POST['insert_all_products'] ) ) {
    if(isset($_POST['range']) && !empty($_POST['range'])) {
        $range = $_POST['range'];
    }else {
        $range =  1;
    }
    $range = explode(',', $range);



    $output ="<table class=\"widefat fixed\" cellspacing=\"0\">\n\n";
    $output .= "<thead>\n\n";
    $output .= "<tr>\n\n";
    $output .= "<th > Result </th>";
    $output .= "</tr>\n\n";
    $output .= "</thead>\n\n";
    $output .= "<tbody> \n";
    $output .= "<tr>\n";
    for ($x = $range[0]; $x <= $range[1]; $x++) {
        foreach ($create->hmuInsertSingleData($x) as $msg) {
            $output .= "<tr><td>".$msg."</td></tr>";
        }
    }
    $output .= "</tr>\n";
    $output .= "</tbody> \n ";
    $output .= "\n</table>";

    echo $output;



}

?>