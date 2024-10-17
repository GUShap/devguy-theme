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