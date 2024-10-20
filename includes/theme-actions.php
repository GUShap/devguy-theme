<?php 

if(!defined('ABSPATH'))
    exit;

//  support logo
function theme_custom_logo_setup() {
    add_theme_support('custom-logo', array(
        'height'      => 100,     // Set desired logo height
        'width'       => 400,     // Set desired logo width
        'flex-height' => true,    // Allow flexible height
        'flex-width'  => true,    // Allow flexible width
    ));
}
add_action('after_setup_theme', 'theme_custom_logo_setup');


function add_cors_http_header() {
    header("Access-Control-Allow-Origin: *");
}
add_action('init', 'add_cors_http_header');
