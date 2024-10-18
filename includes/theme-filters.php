<?php

if (!defined('ABSPATH'))
    exit;

// support SVG type
function allow_svg_upload($mime_types)
{
    // Add SVG to allowed mime types
    $mime_types['svg'] = 'image/svg+xml';
    return $mime_types;
}
add_filter('upload_mimes', 'allow_svg_upload');
