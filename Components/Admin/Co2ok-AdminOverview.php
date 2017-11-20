<?php
/**
 * Created by PhpStorm.
 * User: Chris-Home
 * Date: 11/20/2017
 * Time: 19:33
 */

namespace co2ok_plugin_woocommerce\Components\Admin;

class Co2ok_AdminOverview
{

    public function __construct()
    {
        add_action('admin_menu', array($this, 'co2ok_plugin_setup_menu'));


        //add_action('admin_enqueue_scripts', array($this, 'co2ok_admin_style'),100000000);
        //add_action('admin_enqueue_scripts', array($this, 'co2ok_admin_javascript'),100000000);

    }

    function co2ok_admin_style($hook)
    {
        if($hook != 'co2ok-plugin') {
          //  return;
        }
        wp_enqueue_style( 'custom_wp_admin_css', plugins_url('admin-style.css', __FILE__) );

        wp_register_style('co2ok_stylesheet', plugins_url('../../css/co2ok.css', __FILE__) );
        wp_enqueue_style('co2ok_stylesheet');



        wp_enqueue_style( 'co2ok-google-fonts', 'http://fonts.googleapis.com/css?family=Roboto:300,400', false );
    }

    function co2ok_admin_javascript()
    {
        wp_register_script('co2ok_js_wp', plugins_url('../../js/co2ok-plugin.js', __FILE__) );
        wp_enqueue_script('co2ok_js_wp');
    }

    function co2ok_plugin_setup_menu()
    {
        add_menu_page( 'Co2ok Plugin Page', 'Co2ok Plugin', 'manage_options', 'co2ok-plugin', array($this, 'test_init'));
    }

    function test_init()
    {
        if (isset($_POST['co2ok_template_style'])) {
            update_option('co2ok_button_template', $_POST['co2ok_template_style']);
            $value = $_POST['co2ok_template_style'];
        }

        if (isset($_POST['co2ok_plugin_language'])) {
            update_option('co2ok_plugin_language', $_POST['co2ok_plugin_language']);
            $co2ok_plugin_language = $_POST['co2ok_plugin_language'];
        }

        $value = get_option('co2ok_button_template', 'co2ok_button_template_default');
        $co2ok_plugin_language = get_option('co2ok_plugin_language', 'co2_ok_language_EN');

        include_once plugin_dir_path(__FILE__).'views/default.php';
    }

}
$admin = new Co2ok_AdminOverview();