<?php
use Inc\Data\Api\PrepareForCron;
$insertGallery = new PrepareForCron();

?>
    <h1>Insert product thumbnail</h1>

    <form action="" method="post">
        <input class="hmu-btn hmu-primary" type="submit" name="gallery_insert" value="Insert Gallery images">
        <input name="galleryLimit" type="number" id="galleryLimit" size="30" placeholder="Choose a limit">
    </form>

<?php
if( isset( $_POST['gallery_insert'] ) ) {
    if (isset($_POST['galleryLimit']) && !empty($_POST['galleryLimit'])) {
        $galleryLimit = $_POST['galleryLimit'];
    } else {
        $galleryLimit = 1;
    }

    $insertGallery->hmuInsertGallery($galleryLimit);
}

?>