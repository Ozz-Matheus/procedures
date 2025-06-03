<?php

function pho_enqueue_assets() {
    wp_enqueue_style(
        'pho-procedures-plugin',
        plugin_dir_url(__FILE__) . '../inc/css/pho-procedures-style.css',
        array(),
        '1.6'
    );
}
add_action('wp_enqueue_scripts', 'pho_enqueue_assets');

function pho_enqueue_admin_assets($hook) {

    global $post;

    if ($hook === 'post.php' || $hook === 'post-new.php') {
        if (isset($post->post_type) && $post->post_type === 'procedures') {
            wp_enqueue_style(
                'pho-admin-style',
                plugin_dir_url(__FILE__) . '../inc/css/pho-admin-style.css',
                array(),
                '1.0'
            );
        }
    }
}
add_action('admin_enqueue_scripts', 'pho_enqueue_admin_assets');