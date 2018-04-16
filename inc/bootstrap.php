<?php # -*- coding: utf-8 -*-

namespace Inpsyde\Assets;

// Exit early in case multiple Composer autoloaders try to include this file.
if (function_exists(__NAMESPACE__.'\\'.'bootstrap')) {
    return;
}

function bootstrap(): bool
{
    // Prevent function is called more than once with same path as argument (which would mean load same file again)
    static $done;
    if ($done) {
        return false;
    }
    $done = true;

    (new AssetManager())->setup();

    return $done;
}

/*
 * This file is loaded by Composer autoload, and that may happen before `add_action` is available.
 * In that case, we "manually" add in global `$wp_filter` the function that bootstrap this package.
 */
if (! function_exists('add_action')) {
    global $wp_filter;
    is_array($wp_filter) or $wp_filter = [];
    isset($wp_filter['wp_loaded']) or $wp_filter['wp_loaded'] = [];
    isset($wp_filter['wp_loaded'][99]) or $wp_filter['wp_loaded'][99] = [];
    $wp_filter['wp_loaded'][99][__NAMESPACE__.'\\bootstrap'] = [
        'function' => __NAMESPACE__.'\\'.'bootstrap',
        'accepted_args' => 0,
    ];
} else {
    add_action('wp_loaded', __NAMESPACE__.'\\bootstrap', 99);
}
