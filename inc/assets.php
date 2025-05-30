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
