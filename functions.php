<?php

if (!defined('ABSPATH'))
    exit;

define('THEME_DIR', get_stylesheet_directory_uri());

// support menu
add_theme_support('menus');

// disable for posts
add_filter('use_block_editor_for_post', '__return_false', 10);

// disable for post types
add_filter('use_block_editor_for_post_type', '__return_false', 10);

// require

require_once THEME_DIR . '/includes/theme-actions.php';