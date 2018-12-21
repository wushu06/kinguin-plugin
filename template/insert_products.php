<?php
use Inc\Data\Api\DisplayData;
$insertData = new DisplayData();

?>
    <h1>All Porducts</h1>

    <form action="" method="post">
        <input class="hmu-btn hmu-primary" type="submit" name="insert_all_products" value="Insert Products">
        <input name="limit" type="number" id="limit" size="30">
    </form>

<?php
if( isset( $_POST['insert_all_products'] ) ) {
    wp_mail('nourwushu@gmail.com', 'kenguin mail', 'kenguib');
    if(isset($_POST['limit']) && !empty($_POST['limit'])) {
        $limit = $_POST['limit'];
    }else {
        $limit =  1;
    }

    $output ="<table class=\"widefat fixed\" cellspacing=\"0\">\n\n";
    $output .= "<thead>\n\n";
    $output .= "<tr>\n\n";
    $output .= "<th > Result </th>";
    $output .= "</tr>\n\n";
    $output .= "</thead>\n\n";
    $output .= "<tbody> \n";
    $output .= "<tr>\n";
    foreach ($insertData->hmuInsertData($limit) as $msg) {
        $output .= "<tr><td>".$msg."</td></tr>";
    }
    $output .= "</tr>\n";
    $output .= "</tbody> \n ";
    $output .= "\n</table>";

    echo $output;

    
}

?>