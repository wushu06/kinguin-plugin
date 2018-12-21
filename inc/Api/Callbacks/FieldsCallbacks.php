<?php 

namespace Inc\Api\Callbacks; 

use \Inc\Base\BaseController;


class FieldsCallbacks extends BaseController {

    public $cron_name;

    public function sanitizeCallback( $input )
    {
        $output = array();


        if(isset($_POST['btnSubmit'])):

            $output = get_option('hmu_api_cron');

            if (empty($output)) {
                $output['1'] = $input;

            } else {

                foreach ($output as $key => $value) {
                    $count = count($output);
                    if ($key < $count) {
                        $output[$key] = $value;

                    } else {
                        $output[$key + 1] = $input;

                    }


                }
            }

        endif;


        return $output;

    }



    public function dashboardSectionManager ()
    {

    }

    function hmu_api_basic_auth_url ($args)
    {
        $name = $args['label_for'];
        $classes = $args['class'];
        $option_name = $args['option_name'];
        $value =  get_option( $option_name );
        $isvalue = isset($value[$name]) ? $value[$name]  : '';
        $this->cron_name = $isvalue;

        echo '<input type="text" class="regular-text hmu-input" name="'. $option_name.'['.$name.']"  value="' . $isvalue . '"  placeholder="Url">';


    }

    function hmu_api_basic_auth_username ($args)
    {
        $name = $args['label_for'];
        $classes = $args['class'];
        $option_name = $args['option_name'];
        $value =  get_option( $option_name );
        $isvalue = isset($value[$name]) ? $value[$name]  : '';
        $this->cron_name = $isvalue;

        echo '<input type="text" class="regular-text hmu-input" name="'. $option_name.'['.$name.']"  value="' . $isvalue . '"  placeholder="Username">';


    }

    function hmu_api_basic_auth_password ($args)
    {
        $name = $args['label_for'];
        $classes = $args['class'];
        $option_name = $args['option_name'];
        $value =  get_option( $option_name );
        $isvalue = isset($value[$name]) ? $value[$name]  : '';
        $this->cron_name = $isvalue;

        echo '<input type="password" class="regular-text hmu-input" name="'. $option_name.'['.$name.']"  value="' . $isvalue . '"  placeholder="Password">';


    }

    function hmu_oauth_one_url($args)
    {
        $name = $args['label_for'];
        $classes = $args['class'];
        $option_name = $args['option_name'];
        $value =  get_option( $option_name );
        $isvalue = isset($value[$name]) ? $value[$name]  : '';
        $this->cron_name = $isvalue;

        echo '<input type="text" class="regular-text hmu-input" name="'. $option_name.'['.$name.']"  value="' . $isvalue . '"  placeholder="Url">';
    }

    function hmu_oauth_one_ck($args)
    {
        $name = $args['label_for'];
        $classes = $args['class'];
        $option_name = $args['option_name'];
        $value =  get_option( $option_name );
        $isvalue = isset($value[$name]) ? $value[$name]  : '';
        $this->cron_name = $isvalue;

        echo '<input type="text" class="regular-text hmu-input" name="'. $option_name.'['.$name.']"  value="' . $isvalue . '"  placeholder="CK">';
    }

    function hmu_oauth_one_cs($args)
    {
        $name = $args['label_for'];
        $classes = $args['class'];
        $option_name = $args['option_name'];
        $value =  get_option( $option_name );
        $isvalue = isset($value[$name]) ? $value[$name]  : '';
        $this->cron_name = $isvalue;

        echo '<input type="text" class="regular-text hmu-input" name="'. $option_name.'['.$name.']"  value="' . $isvalue . '"  placeholder="CS">';
    }

    function hmu_api_cron_name($args)
    {
        $name = $args['label_for'];
        $classes = $args['class'];
        $option_name = $args['option_name'];
        $value =  get_option( $option_name );
        $isvalue = isset($value[$name]) ? $value[$name]  : '';
        $this->cron_name = $isvalue;

        echo '<input type="text" class="regular-text hmu-input" name="'. $option_name.'['.$name.']"  value="' . $isvalue . '"  placeholder="Cron name">';
    }

    function cronTimeField ($args) {
        $name = $args['label_for'];
        $classes = $args['class'];
        $option_name = $args['option_name'];
        $value =  get_option( $option_name );
        $cron_value = isset($value[$name]) ? $value[$name]  : 'Select Time';


        echo '
         <select name="' . $option_name . '[' . $name . ']">
            <option value="">'.$cron_value.'</option>
            <option value="every_one_minute">1 min</option>
            <option value="every_thirty_minutes">30 min</option>
            <option value="hourly">hourly</option>
             <option value="twicedaily">twicedaily</option>
            <option value="daily">Daily</option>
            
           
          </select><br>
                       ';

    }

    function cronTaskSelect ($args) {

        $name = $args['label_for'];
        $classes = $args['class'];
        $option_name = $args['option_name'];
        $value =  get_option( $option_name );
        $cron_value = isset($value[$name]) ? $value[$name]  : 'Select Time';


        echo '
         <select name="' . $option_name . '[' . $name . ']">
            <option value="">'.$cron_value.'</option>
            <option value="update-products">Update Products</option>
            <option value="update-stock">Update Stock</option>
          </select><br>               ';

    }









}
?>
