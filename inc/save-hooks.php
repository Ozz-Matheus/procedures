<?php
/**
 * Hook para reasignar el post_author al usuario final en trámites creados por admin o shop_manager
 */

add_action('save_post_procedures', 'pho_assign_procedure_to_user', 10, 3);

function pho_assign_procedure_to_user($post_id, $post, $update) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (wp_is_post_revision($post_id)) return;
    if (get_post_type($post_id) !== 'procedures') return;

    // Solo en admin
    if (!is_admin()) return;

    // Solo si lo guarda un administrador o shop manager
    $current_user = wp_get_current_user();
    if (!in_array('administrator', $current_user->roles) && !in_array('shop_manager', $current_user->roles)) {
        return;
    }

    // Se asume que el título del post es el correo del usuario destino
    $user_email = get_the_title($post_id);
    $user = get_user_by('email', $user_email);

    if ($user && (int) $post->post_author !== (int) $user->ID) {
        remove_action('save_post_procedures', 'pho_assign_procedure_to_user');

        wp_update_post([
            'ID' => $post_id,
            'post_author' => $user->ID,
        ]);

        add_action('save_post_procedures', 'pho_assign_procedure_to_user');
    }
}
