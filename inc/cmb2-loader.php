<?php

function pho_load_cmb2_library() {
    $cmb2_path = plugin_dir_path(__FILE__) . '../cmb2/init.php';
    if ( file_exists($cmb2_path) ) {
        require_once $cmb2_path;
    }
}
add_action('init', 'pho_load_cmb2_library', 0);

