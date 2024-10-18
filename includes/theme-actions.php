<?php 

if(!defined('ABSPATH'))
    exit;


    // Return formatted top-nav menu
function top_nav_menu() {
    $menu = wp_get_nav_menu_items('main-menu');
    $result = [];
    foreach($menu as $item) {
        $my_item = [
            'name' => $item->title,
            'href' => $item->url
        ];
        $result[] = $my_item;
    }
    return $result;
}
// add endpoint
add_action( 'rest_api_init', function() {
    // top-nav menu
    register_rest_route( 'wp/v2', 'main-menu', array(
        'methods' => 'GET',
        'callback' => 'top_nav_menu',
    ) );
});

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
