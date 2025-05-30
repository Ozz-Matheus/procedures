<?php

function pho_add_custom_tab_to_my_account($tabs) {
    unset($tabs['downloads']);
    $tabs['tramites'] = __('Mis Trámites', 'pho-procedures');
    return $tabs;
}
add_filter('woocommerce_account_menu_items', 'pho_add_custom_tab_to_my_account');

function pho_register_my_account_endpoint() {
    add_rewrite_endpoint('tramites', EP_ROOT | EP_PAGES);
}
add_action('init', 'pho_register_my_account_endpoint');

function pho_reorder_my_account_menu($items) {
    $items['dashboard'] = __('Ajustes', 'woocommerce');

    if (isset($items['tramites'])) {
        $mis_tramites = $items['tramites'];
        unset($items['tramites']);
        $items = array('tramites' => $mis_tramites) + $items;
    }
    return $items;
}
add_filter('woocommerce_account_menu_items', 'pho_reorder_my_account_menu');