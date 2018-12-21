<?php
use Inc\Data\Api\DisplayData;
$displayData = new DisplayData();

?>
<h1>All Porducts</h1>

<form action="" method="post">
    <input class="hmu-btn hmu-primary" type="submit" name="display_all_products" value="Display Products">
    <input name="limit" type="number" id="limit" size="30">
</form>

<?php
if( isset( $_POST['display_all_products'] ) ) {

    if(isset($_POST['limit']) && !empty($_POST['limit'])) {
        $limit = $_POST['limit'];
    }else {
        $limit =  10;
    }

    ob_start();
    $displayData->hmuDisplayData($limit);
    $output = ob_get_contents();  // stores buffer contents to the variable
    ob_end_clean();
    echo $output;
}

?>