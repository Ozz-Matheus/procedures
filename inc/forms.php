<?php

function pho_add_new_tramites() {
    $current_user = wp_get_current_user();
    $procedure = get_posts(array(
        'post_type' => 'procedures',
        'author' => $current_user->ID,
        'posts_per_page' => 1,
    ));

    if (empty($procedure)) {
        $template = plugin_dir_path(__FILE__) . '../template-parts/content-form.php';
        if (file_exists($template)) {
            require_once $template;
        }
    }
}
add_action('woocommerce_account_tramites_endpoint', 'pho_add_new_tramites');

function pho_process_new_tramite() {

    if (isset($_POST['new_procedure'])) {

        $user_email = sanitize_email($_POST['user_email']);
        $user_name = mb_strtoupper(sanitize_text_field($_POST['user_name']), 'UTF-8');
        $telefono = sanitize_text_field($_POST['telefono']);
        // $social = sanitize_text_field($_POST['social']); // 📌
        // $perfil = sanitize_text_field($_POST['perfil']); // 📌

        $default_status = 'incomplete';

        $procedure_id = wp_insert_post(array(
            'post_title'   => $user_email,
            'post_status'  => 'publish',
            'post_type'    => 'procedures',
        ));

        if ($procedure_id) {

            update_post_meta($procedure_id, 'Names', $user_name);
            update_post_meta($procedure_id, 'Status', $default_status);
            update_post_meta($procedure_id, 'Phone', $telefono);
            // update_post_meta($procedure_id, 'Social', $social); // 📌
            // update_post_meta($procedure_id, 'Perfil', $perfil); // 📌

            echo '<div class="alert alert-success"><span>A continuación carga tus documentos</span></div>';

            $procedure_url = wc_get_account_endpoint_url('tramites');

            echo '<script>
                setTimeout(function(){
                    window.location.href="' . esc_url($procedure_url) . '";
                }, 2000);
            </script>';
            exit;
        } else {
            echo '<div class="alert alert-error"><span>¡Error al iniciar el trámite!</span></div>';
        }
    }
}
add_action('woocommerce_account_tramites_endpoint', 'pho_process_new_tramite');

function pho_tramites_endpoint_content() {

    $template = plugin_dir_path(__FILE__) . '../my-account-procedures.php';
    if (file_exists($template)) {
        include $template;
    } else {
        echo 'No hay trámites para mostrar. <br />';
    }
}
add_action('woocommerce_account_tramites_endpoint', 'pho_tramites_endpoint_content');
