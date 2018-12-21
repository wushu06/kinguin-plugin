<?php settings_errors(); ?>

    <form method="post" class="hmu-general-form" action="options.php" enctype="multipart/form-data">
        <?php
        settings_fields( 'hmu_cron_options_group' );
        do_settings_sections( 'cron_task' );
        submit_button( 'Create task', 'hmu-btn hmu-primary', 'btnSubmit' );
        ?>
    </form>
