<?php
use Inc\Data\Api\PrepareForCron;
$insertImages = new PrepareForCron();

?>
    <h1>Insert product info</h1>

    <form action="" method="post">
        <input class="hmu-btn hmu-primary" type="submit" name="info_insert" value="Insert product info">
        <input name="infoLimit" type="number" id="infoLimit" size="30" placeholder="Choose a limit">
    </form>

<?php
if( isset( $_POST['info_insert'] ) ) {
    if (isset($_POST['infoLimit']) && !empty($_POST['infoLimit'])) {
        $infoLimit = $_POST['infoLimit'];
    } else {
        $infoLimit = 1;
    }

    $insertImages->hmuInsertRest($infoLimit);
}

?>