<?php
use Inc\Data\Api\PrepareForCron;
$createbasic = new PrepareForCron();

?>
    <h1>Insert Basics only</h1>

    <form action="" method="post">
        <input class="hmu-btn hmu-primary" type="submit" name="basicLimit_insert" value="Insert Bsic Data">
        <input name="basicLimit" type="number" id="basicLimit" size="30" placeholder="Choose a limit">
    </form>

<?php
if( isset( $_POST['basicLimit_insert'] ) ) {
    if (isset($_POST['basicLimit']) && !empty($_POST['basicLimit'])) {
        $basicLimit = $_POST['basicLimit'];
    } else {
        $basicLimit = 1;
    }

    $createbasic->hmuCreateBasicEntries($basicLimit);
}

?>