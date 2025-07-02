<?php
/**
 * Plugin Name: PHO Procedures
 * Description: PHO Procedures es un plugin para WordPress que permite a los usuarios gestionar solicitudes personalizadas tipo “trámite” desde su cuenta WooCommerce.
 * Version: 1.4
 * Author: Phoenix Dev
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

    require_once $inc_path . 'cmb2-loader.php';        // Primero la librería base
    require_once $inc_path . 'template-helpers.php';   // Funciones auxiliares usadas en muchos módulos
    require_once $inc_path . 'assets.php';             // CSS/JS, puede depender de helpers
    require_once $inc_path . 'forms.php';              // Lógica de formulario
    require_once $inc_path . 'save-hooks.php';         // Hook para asignar autor correcto
    require_once $inc_path . 'email.php';              // Envío de correos
    require_once $inc_path . 'metaboxes.php';          // CMB2: campos personalizados
    require_once $inc_path . 'notifications.php';      // Notificaciones JS en admin
    require_once $inc_path . 'redirects.php';          // Redirección single
    require_once $inc_path . 'admin-statuses.php';     // Estados de trámite
    require_once $inc_path . 'settings.php';           // Configuración de credencial
}
