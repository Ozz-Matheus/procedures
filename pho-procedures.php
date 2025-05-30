<?php
/**
 * Plugin Name: PHO Trámites
 * Description: Trámites es un complemento que agrega funciones de trámite a la vista de usuario.
 * Version: 1.4
 * Author: Orlando Montesinos Quintana
 * Text Domain: pho-procedures
 * Author URI: https://phoenixdev.mx/
 */

// Cargar módulos requeridos por el hook de activación
$inc_path = plugin_dir_path(__FILE__) . 'inc/';
require_once $inc_path . 'capabilities.php';
require_once $inc_path . 'cpt-procedures.php';
require_once $inc_path . 'endpoints.php';
require_once $inc_path . 'activator.php';

// Hook de activación
register_activation_hook(__FILE__, 'pho_plugin_activate');

// Cargar el plugin solo si WooCommerce está activo
add_action('plugins_loaded', 'pho_tramites_init_plugin');
function pho_tramites_init_plugin() {
    if ( ! class_exists('WooCommerce') ) return;

    $inc_path = plugin_dir_path(__FILE__) . 'inc/';

    require_once $inc_path . 'assets.php';
    require_once $inc_path . 'cmb2-loader.php';
    require_once $inc_path . 'forms.php';
    require_once $inc_path . 'email.php';
    require_once $inc_path . 'metaboxes.php';
    require_once $inc_path . 'notifications.php';
    require_once $inc_path . 'redirects.php';
    require_once $inc_path . 'admin-statuses.php';
    require_once $inc_path . 'template-helpers.php';

}
