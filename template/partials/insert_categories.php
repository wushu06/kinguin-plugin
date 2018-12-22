<?php
use Inc\Data\Api\PrepareForCron;
$insertImages = new PrepareForCron();

?>
    <h1>Insert product categories</h1>

    <form action="" method="post">
        <input class="hmu-btn hmu-primary" type="submit" name="categories_insert" value="Insert Bsic Data">
        <input name="categoryLimit" type="number" id="categoryLimit" size="30" placeholder="Choose a limit">
    </form>

<?php
if( isset( $_POST['categories_insert'] ) ) {
    if (isset($_POST['categoryLimit']) && !empty($_POST['categoryLimit'])) {
        $categoryLimit = $_POST['categoryLimit'];
    } else {
        $categoryLimit = 1;
    }

    $insertImages->hmuInsertCategories($categoryLimit);
}

?>