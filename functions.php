<?php

if (!defined('ABSPATH'))
    exit;

define('THEME_DIR', get_stylesheet_directory_uri());
define('THEME_DIR_PATH', get_stylesheet_directory());

// support menu
add_theme_support('menus');

// disable for posts
add_filter('use_block_editor_for_post', '__return_false', 10);

// disable for post types
add_filter('use_block_editor_for_post_type', '__return_false', 10);

// require

require_once THEME_DIR_PATH . '/includes/theme-actions.php';
require_once THEME_DIR_PATH . '/includes/theme-filters.php';
require_once THEME_DIR_PATH . '/includes/theme-api.php';

// Admin Functions


function get_site_logo_data()
{
    $logo_id = get_theme_mod('custom_logo');
    return get_image_data($logo_id);
}

function get_image_data($image_id)
{
    $image_data = [];

    if (!$image_id)
        return [];
    // Get the attachment post
    $attachment = get_post($image_id);

    // Prepare dynamic image attributes
    $image_data['url'] = wp_get_attachment_url($image_id); // Get the image URL
    $image_data['alt'] = get_post_meta($image_id, '_wp_attachment_image_alt', true); // Get alt text
    $image_data['title'] = $attachment->post_title; // Get title from post title
    $image_data['class'] = 'main-site-logo'; // Optionally set a dynamic class

    // Return the image data
    return $image_data;
}