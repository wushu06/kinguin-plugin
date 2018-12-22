<?php
use Inc\Data\Api\BatchCreate;
$insertData = new BatchCreate();

?>
    <h1>Batch create Products</h1>

    <form action="" method="post">
        <input class="hmu-btn hmu-primary" type="submit" name="batch_insert" value="Insert Products">
        <input name="limit" type="number" id="limit" size="30" placeholder="Choose a limit">
    </form>

<?php
if( isset( $_POST['batch_insert'] ) ) {
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