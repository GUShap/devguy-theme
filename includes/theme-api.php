<?php

if (!defined('ABSPATH'))
    exit;

// add endpoint to get media file by id
add_action('rest_api_init', function () {
    register_rest_route('wp/v2', '/media/(?P<id>\d+)', array(
        'methods' => 'GET',
        'callback' => 'get_media_file',
        'permission_callback' => '__return_true', // Allows public access (modify this if needed)
    ));
});

function get_media_file(WP_REST_Request $request)
{
    $id = $request->get_param('id');
    $media_data = get_image_data($id);
    if ($media_data) {
        return new WP_REST_Response($media_data, 200);
    } else {
        return new WP_REST_Response("No media found", 404);
    }
}

// Nav Endpoint
add_action('rest_api_init', function () {
    // top-nav menu
    register_rest_route('wp/v2', 'left-menu', array(
        'methods' => 'GET',
        'callback' => 'left_menu_nav',
    ));
});
// Return formatted top-nav menu
function left_menu_nav()
{
    $menu = wp_get_nav_menu_items('left-menu');
    $result = [];
    foreach ($menu as $item) {
        $my_item = [
            'name' => $item->title,
            'href' => $item->url
        ];
        $result[] = $my_item;
    }
    return $result;
}

// Register the custom REST API route
add_action('rest_api_init', function () {
    register_rest_route('wp/v2', '/settings', array(
        'methods' => 'GET',
        'callback' => 'get_custom_settings',
        'permission_callback' => '__return_true', // Allows public access (modify this if needed)
        'args' => array(
            'setting' => array(
                'required' => true,
                'validate_callback' => function ($param, $request, $key) {
                    return is_string($param); // Ensure it's a string
                }
            )
        )
    ));
});

// Callback function to return the requested setting
function get_custom_settings(WP_REST_Request $request)
{
    // Get the query var 'setting'
    $setting = $request->get_param('setting');

    // Define allowed settings
    $allowed_settings = array(
        'site_title' => get_bloginfo('name'),
        'site_description' => get_bloginfo('description'),
        'site_logo' => get_site_logo_data(),
        'admin_email' => get_option('admin_email'),
    );

    // Check if the requested setting exists in the allowed list
    if (array_key_exists($setting, $allowed_settings)) {
        return new WP_REST_Response(array(
            'setting' => $setting,
            'value' => $allowed_settings[$setting]
        ), 200);
    } else {
        return new WP_REST_Response(array(
            'error' => 'Invalid setting requested'
        ), 400);
    }
}
// 

add_action("rest_api_init", function () {
    register_rest_route("wp/v2", "/options", [
        "methods" => "GET",
        "callback" => "acf_options_route",
        'permission_callback' => '__return_true', // Allows public access (modify this if needed)
        'args' => array(
            'setting' => array(
                'required' => true,
                'validate_callback' => function ($param, $request, $key) {
                    return is_string($param); // Ensure it's a string
                }
            )
        )
    ]);
});

function acf_options_route(WP_REST_Request $request)
{

    $setting = $request->get_param('setting');
    $logo_id = '';
    $res_data = [];
    switch ($setting) {
        case 'all':
            $logos_ids = ['blue_logo', 'white_logo', 'dark_logo'];
            foreach ($logos_ids as $logo) {
                $logo_id = get_field($logo, 'option');
                $res_data[$logo] = get_image_data($logo_id);
            }
            break;
        default:
            $logo_id = get_field($setting, 'option');
            $res_data[$setting] = get_image_data($logo_id);
            break;
    }
    if (!empty($res_data)) {
        return new WP_REST_Response($res_data, 200);
    } else {
        return new WP_REST_Response("No options found", 404);
    }
}
