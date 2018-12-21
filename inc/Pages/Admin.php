<?php

namespace Inc\Pages;

use \Inc\Base\BaseController;

use \Inc\Api\SettingsApi;

use \Inc\Api\Callbacks\AdminCallbacks;

use \Inc\Api\Callbacks\FieldsCallbacks;

class Admin extends BaseController {

    public $settings;
	public $admin_callbacks;
	public $fields_callbacks;
    public $pages = array();
	public $subpages = array();



    function register() {


	        	$this->settings = new SettingsApi();

				$this->admin_callbacks = new AdminCallbacks();
				$this->fields_callbacks = new FieldsCallbacks();

				$this->set_pages();

				$this->setSubpages();

				$this->setSettings();
				$this->setSections();
				$this->setFields();

				$this->settings->add_pages( $this->pages )->withSubPage( 'Dashboard' )->addSubPages( $this->subpages )->register();


    }

    /*
    * create menu
    */

    function set_pages () {
        $this->pages = array(
            array(
                    'page_title' => 'Hook Me Up Api',
                    'menu_title' => 'Hook Me Up Api',
                    'capability' => 'manage_options',
                    'menu_slug' => 'hmu_api_plugin',
                    'callback' => array( $this->admin_callbacks, 'hmu_api_plugin' ),
                    'icon_url' => plugins_url(). '/hook-me-up-api/assets/images/api3.png',
                    'position' => 110
                )
            );



	}
	public function setSubpages()
	{
		foreach ($this->subpagesOutput as $slug => $title_callback) {
			    $this->subpages[] = array (
				'parent_slug' => 'hmu_api_plugin',
				'page_title' => $title_callback[0],
				'menu_title' => $title_callback[0],
				'capability' => 'manage_options',
				'menu_slug' => $slug,
				'callback' => array( $this->admin_callbacks, $title_callback[1] ),
			);

		}

	}
    /*
    * create fields
    */
	public function setSettings()
	{
        /*
         * # for each page create group of fields and give each group option name
         * #
         */


		$args = array(
            array(
                'option_group' => 'hmu_api_basic_auth_options_group',
                'option_name' => 'hmu_api_basic',
                //'callback' => array( $this->fields_callbacks,'sanitizeCallback' )
            ),
            array(
                'option_group' => 'hmu_api_oauth_one_group',
                'option_name' => 'hmu_api_oauth_one',
                //'callback' => array( $this->fields_callbacks,'sanitizeCallback' )
            ),
            array(
                'option_group' => 'hmu_api_dashboard_third_group',
                'option_name' => 'hmu_api_cron',
                'callback' => array( $this->fields_callbacks,'sanitizeCallback' )
            )

		);



		$this->settings->setSettings( $args );
	}

	public function setSections()
	{
        $args = array(
            array(
                'id' => 'hmu_api_dashboard_index',
                'title' => 'Dashboard',
                'callback' => array( $this->fields_callbacks, 'dashboardSectionManager' ),
                'page' => 'hmu_api_plugin' //dashboard page
            ),
            array(
                'id' => 'hmu_api_dashboard_basic',
                'title' => 'Basic Auth',
                'callback' => array( $this->fields_callbacks, 'dashboardSectionManager' ),
                'page' => 'basic_auth'
            ),
            array(
                'id' => 'hmu_api_dashboard_oauth_one',
                'title' => 'Auth One',
                'callback' => array( $this->fields_callbacks, 'dashboardSectionManager' ),
                'page' => 'oauth_one' //dashboard page
            ),
            array(
                'id' => 'hmu_api_dashboard_third',
                'title' => 'Cron',
                'callback' => array( $this->fields_callbacks, 'dashboardSectionManager' ),
                'page' => 'hmu_api_cron' //dashboard page
            )
        );

        $this->settings->setSections( $args );
	}
    public function dahboardFields()
    {
        return  array(
            // ID
            //0- title 1- callback 2-page 3- section 4- option name 5-input type
            //basic auth
            'basic_auth_url' => //id
                array('Basic Auth Url',
                    'hmu_api_basic_auth_url',
                    'basic_auth',
                    'hmu_api_dashboard_basic',
                    'hmu_api_basic',
                    'boolean'
                ),
            'basic_auth_username' => //id
                array('Basic Auth Username',
                    'hmu_api_basic_auth_username',
                    'basic_auth',
                    'hmu_api_dashboard_basic',
                    'hmu_api_basic',
                    'boolean'
                ),
            'basic_auth_password' => //id
                array('Basic Auth Password',
                    'hmu_api_basic_auth_password',
                    'basic_auth',
                    'hmu_api_dashboard_basic',
                    'hmu_api_basic',
                    'boolean'
                ),
            // oauth one
            'oauth_one_url' => //id
                array('OAuth One Url',
                    'hmu_oauth_one_url',
                    'oauth_one',
                    'hmu_api_dashboard_oauth_one',
                    'hmu_api_oauth_one',
                    'boolean'
                ),
            'oauth_one_ck' => //id
                array('OAuth One CK',
                    'hmu_oauth_one_ck',
                    'oauth_one',
                    'hmu_api_dashboard_oauth_one',
                    'hmu_api_oauth_one',
                    'boolean'
                ),
            'oauth_one_cs' => //id
                array('OAuth One CS',
                    'hmu_oauth_one_cs',
                    'oauth_one',
                    'hmu_api_dashboard_oauth_one',
                    'hmu_api_oauth_one',
                    'boolean'
                ),
            // cron
            'cron_name' => //id
                array('Cron Name',
                    'hmu_api_cron_name',
                    'hmu_api_cron',
                    'hmu_api_dashboard_third',
                    'hmu_api_cron',
                    'boolean'
                ),
            'cron_time' =>
                array(
                    'Cron Time',
                    'cronTimeField',
                    'hmu_api_cron',
                    'hmu_api_dashboard_third',
                    'hmu_api_cron',// option name
                    'boolean'
                ),
            'cron_task' =>
                array(
                    'Cron Task',
                    'cronTaskSelect',
                    'hmu_api_cron',
                    'hmu_api_dashboard_third',
                    'hmu_api_cron',// option name
                    'boolean'
                )



        );


    }


    public function setFields()
	{
		$args = array ();

        foreach ($this->dahboardFields()   as $id_dash => $dashtitle_callback ) {

            $args[] = array (
                'id' => $id_dash,
                'title' => $dashtitle_callback[0],
                'callback' => array( $this->fields_callbacks, $dashtitle_callback[1] ),
                'page' => $dashtitle_callback[2],
                'section' => $dashtitle_callback[3],
                'args' => array(
                    'option_name' => $dashtitle_callback[4],
                    'label_for' => $id_dash,
                    'class' => 'hmu-upload'
                )
            );
        }


        $this->settings->setFields( $args );
	}

}