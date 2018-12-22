<?php
use Inc\Data\Api\PrepareForCron;
$insertSP = new PrepareForCron();

?>
    <h1>Insert Stock and price</h1>

    <form action="" method="post">
        <input class="hmu-btn hmu-primary" type="submit" name="stock_insert" value="Insert Bsic Data">
        <input name="stockLimit" type="number" id="stockLimit" size="30" placeholder="Choose a limit">
    </form>

<?php
if( isset( $_POST['stock_insert'] ) ) {
    if (isset($_POST['stockLimit']) && !empty($_POST['stockLimit'])) {
        $stockLimit = $_POST['stockLimit'];
    } else {
        $stockLimit = 1;
    }

    $insertSP->hmuCronUpdateStockAndPrice($stockLimit);
}

?>