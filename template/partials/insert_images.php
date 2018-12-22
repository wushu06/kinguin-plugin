<?php
use Inc\Data\Api\PrepareForCron;
$insertImages = new PrepareForCron();

?>
    <h1>Insert product thumbnail</h1>

    <form action="" method="post">
        <input class="hmu-btn hmu-primary" type="submit" name="images_insert" value="Insert Bsic Data">
        <input name="imagesLimit" type="number" id="imagesLimit" size="30" placeholder="Choose a limit">
    </form>

<?php
if( isset( $_POST['images_insert'] ) ) {
    if (isset($_POST['imagesLimit']) && !empty($_POST['imagesLimit'])) {
        $imagesLimit = $_POST['imagesLimit'];
    } else {
        $imagesLimit = 1;
    }

    $insertImages->hmuInsertImages($imagesLimit);
}

?>