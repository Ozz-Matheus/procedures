<?php

function pho_get_admin_email() {
    return get_option('admin_email');
}

function pho_get_site_name() {
    return get_option('blogname');
}

function pho_change_mail_from($email) {
    return pho_get_admin_email();
}
add_filter('wp_mail_from', 'pho_change_mail_from');

function pho_change_mail_from_name($name) {
    return pho_get_site_name();
}
add_filter('wp_mail_from_name', 'pho_change_mail_from_name');

function pho_send_email_notification() {
    if (isset($_POST['user_email'])) {
        $email = sanitize_email($_POST['user_email']);
        $email_state_label = sanitize_text_field($_POST['procedure_status_label']);
        $email_observations = wp_kses_post($_POST['procedure_observations']);

        $subject = 'Estado del Trámite : ' . $email_state_label;
        $template_path = plugin_dir_path(__FILE__) . '../notification-procedures.php';

        $from_address = WC()->mailer()->get_from_address();

        if (file_exists($template_path)) {
            ob_start();
            include($template_path);
            $message = ob_get_clean();

            $headers = array('Content-Type: text/html; charset=UTF-8');
            $sent = wp_mail($email, $subject, $message, $headers, '-f' . $from_address);

            if ($sent) {
                wp_send_json_success('Notificación enviada con éxito.');
            } else {
                wp_send_json_error('Error al enviar la notificación por correo electrónico.');
            }
        } else {
            wp_send_json_error('Plantilla no encontrada.');
        }
    } else {
        wp_send_json_error('Falta el correo electrónico.');
    }
}
add_action('wp_ajax_pho_send_email_notification', 'pho_send_email_notification');
add_action('wp_ajax_nopriv_pho_send_email_notification', 'pho_send_email_notification');
