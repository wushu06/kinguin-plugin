<?php

namespace Inc\Api;

class SettingsApi
{

    // menu pages and subpages
    public $admin_pages = array();
    public $admin_subpages = array();

    // fields
    public $settings = array();
    public $sections = array();
    public $fields = array();


    public function register()
    {
        if (!empty($this->admin_pages)) {

            add_action('admin_menu', array($this, 'add_menu_pages'));
        }
        if (!empty($this->settings)) {
            add_action('admin_init', array($this, 'registerCustomFields'));
        }

    }

    /*
    * Create menu and submenu
    *- 1- add menu pages (and subpages)
    *- 2- add pages functio + add subpages function + withsubpage function
    */
    public function add_pages( $pages)
    {
        $this->admin_pages = $pages;

        return $this;

    }

    public function addSubPages($pages)
    {
        $this->admin_subpages = array_merge($this->admin_subpages, $pages);

        return $this;
    }

    public function withSubPage($title = null)
    {
        if (empty($this->admin_pages)) {
            return $this;
        }

        $admin_page = $this->admin_pages[0];

        $subpage = array(
            array(
                'parent_slug' => $admin_page['menu_slug'],
                'page_title' => $admin_page['page_title'],
                'menu_title' => ($title) ? $title : $admin_page['menu_title'],
                'capability' => $admin_page['capability'],
                'menu_slug' => $admin_page['menu_slug'],
                'callback' => $admin_page['callback']
            )
        );

        $this->admin_subpages = $subpage;

        return $this;
    }


    public function add_menu_pages()
    {
        foreach ($this->admin_pages as $page) {
            add_menu_page(

                $page['page_title'],
                $page['menu_title'],
                $page['capability'],
                $page['menu_slug'],
                $page['callback'],
                $page['icon_url'],
                $page['position']);
        }

        foreach ($this->admin_subpages as $page) {
            add_submenu_page(

                $page['parent_slug'],
                $page['page_title'],
                $page['menu_title'],
                $page['capability'],
                $page['menu_slug'],
                $page['callback']);
        }
    }

    /*
    * Registering fields
    * 1- register settings
    * 2- add settings section
    * 3- add settings field
    */
    public function setSettings( $settings)
    {
        $this->settings = $settings;

        return $this;
    }

    public function setSections( $sections)
    {
        $this->sections = $sections;

        return $this;
    }

    public function setFields( $fields)
    {
        $this->fields = $fields;

        return $this;
    }

    public function registerCustomFields()
    {
        // register setting
        foreach ($this->settings as $setting) {
            register_setting(
                $setting["option_group"],
                $setting["option_name"],
                (isset($setting["callback"]) ? $setting["callback"] : ''));
        }

        // add settings section
        foreach ($this->sections as $section) {
            add_settings_section(
                $section["id"],
                $section["title"],
                (isset($section["callback"]) ? $section["callback"] : ''),
                $section["page"]);
        }

        // add settings field
        foreach ($this->fields as $field) {
            add_settings_field(
                $field["id"],
                $field["title"],
                (isset($field["callback"]) ? $field["callback"] : ''),
                $field["page"], $field["section"],
                (isset($field["args"]) ? $field["args"] : ''));
        }
    }


}