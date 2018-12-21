<?php

namespace Inc\Base;

class BaseController
{
    public $plugin_path;

    public $plugin_url;

    public $plugin;

    public $subpagesOutput = array();

    public $dahboardFields = array();
    public $fieldsOutput = array();


    public function __construct()
    {
        /*$this->plugin_path = plugin_dir_path(dirname(__FILE__, 2));
        $this->plugin_url = plugin_dir_url(dirname(__FILE__, 2));*/
        $this->plugin_path = SITE_ROOT.'/';
        $this->plugin_url = plugins_url().'/hook-me-up-api/';

        $this->subpagesOutput = array(
            'basic_auth' =>
                array('Basic Auth', 'hmu_basic_oauth_page'),
            'oauth_one' =>
                array('OAuth one', 'hmu_oauth_one_page'),
            'cron_task' =>
                array('Cron Task', 'hmu_cron_page'),
        );

        /*
        * FIELDS
        */



    }


    public static function hmuSeoUrl($string)
    {
        //Lower case everything
        $string = strtolower($string);
        //Make alphanumeric (removes all other characters)
        $string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
        //Clean up multiple dashes or whitespaces
        $string = preg_replace("/[\s-]+/", " ", $string);
        //Convert whitespaces and underscore to dash
        $string = preg_replace("/[\s_]/", "-", $string);
        return $string;
    }

    public static function hmuCleanString($string)
    {
        // $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
        $string = str_replace("pa_", "", $string);
        $string = preg_replace('/[^A-Za-z0-9\-]/', ' ', $string); // Removes special chars.

        return preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
    }




}