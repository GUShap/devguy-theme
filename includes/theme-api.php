<?php

if (!defined('ABSPATH'))
    exit;

// add endpoint
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

function get_site_logo_data()
{
    $logo_id = get_theme_mod('custom_logo');
    $image_data = [];

    if (!$logo_id)
        return [];
    // Get the attachment post
    $attachment = get_post($logo_id);

    // Check if the attachment exists
    // if ($attachment)
    //     return [];
    // Prepare dynamic image attributes
    $image_data['url'] = wp_get_attachment_url($logo_id); // Get the image URL
    $image_data['alt'] = get_post_meta($logo_id, '_wp_attachment_image_alt', true); // Get alt text
    $image_data['title'] = $attachment->post_title; // Get title from post title
    $image_data['class'] = 'main-site-logo'; // Optionally set a dynamic class

    // Return the image data
    return $image_data;
}