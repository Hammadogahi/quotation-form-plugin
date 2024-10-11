<?php

/**
 * Plugin Name: Distance Calculator
 * Description: Calculate distance and pricing based on user input.
 * Version: 1.0
 * Author: Hammad
 * 
 * 
 * @package QuotationForm
 */

defined('ABSPATH') || exit;


define('PLUGIN_FILE_PATH', plugin_dir_path(__FILE__));
define('PLUGIN_FILE_URL', plugin_dir_url(__FILE__));
define('DISTANCE_CALCULATOR_PLUGIN_VERSION',  '1.0');



function enqueue_distance_calculator_script()
{
    // Enqueue jQuery UI
    wp_enqueue_style('jquery-ui-css', 'https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css');
    wp_enqueue_style('quotation-form-main-css', PLUGIN_FILE_URL . 'assets/main.css', array(), '1.0.0');
    wp_enqueue_script('jquery-ui-js', 'https://code.jquery.com/ui/1.12.1/jquery-ui.js', array('jquery'), null, true);

    // Enqueue ajax script
    wp_enqueue_script('distance-calculator', PLUGIN_FILE_URL . 'assets/api-call.js', array('jquery', 'jquery-ui-js'), time(), true);

    // Localize the script to pass the AJAX URL
    wp_localize_script('distance-calculator', 'ajax_object', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonces'    => array(
            'fetch_locations_nonce' =>  wp_create_nonce('fetch_locations'),
            'get_distance_nonce'    =>  wp_create_nonce('get_distance'),
            'form_submit_nonce'     =>  wp_create_nonce('form_submit'),
        )
    ));
}
add_action('wp_enqueue_scripts', 'enqueue_distance_calculator_script');


// Require the API Call File
require_once PLUGIN_FILE_PATH . 'includes/api-calls.php';


// Register the shortcode
function distance_calculator_shortcode_handler()
{
    ob_start();
    include PLUGIN_FILE_PATH . 'includes/calculator-form.php';
    return ob_get_clean();
}


//Register Shortcode on plugin initialization
function distance_calculator_init()
{
    add_shortcode('distance_calculator', 'distance_calculator_shortcode_handler');
}

add_action('init', 'distance_calculator_init');


// Register activation hook
register_activation_hook(__FILE__, 'distance_calculator_activate');

function distance_calculator_activate()
{
    if (! get_option('distance_calculator_version')) {
        update_option('distance_calculator_version', DISTANCE_CALCULATOR_PLUGIN_VERSION);
    }
}

// Register deactivation hook
register_deactivation_hook(__FILE__, 'distance_calculator_deactivate');

function distance_calculator_deactivate()
{

    delete_option('distance_calculator_version');
}
