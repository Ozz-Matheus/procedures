<?php

function pho_add_custom_capabilities_for_non_admins() {
    $roles = array('author', 'editor');

    foreach ($roles as $role_name) {
        $role = get_role($role_name);
        if ($role) {
            $role->add_cap('publish_posts');
            $role->add_cap('read');
        }
    }

    // Asegurar que el rol 'club_member' existe
    if (!get_role('club_member')) {
        add_role(
            'club_member',
            __('Miembro del Club', 'pho-procedures'),
            array(
                'read' => true,
                'publish_posts' => false,
                'edit_posts' => false,
            )
        );
    }
}
add_action('init', 'pho_add_custom_capabilities_for_non_admins');

function pho_add_club_member_role() {

    if (!current_user_can('manage_options')) {
        wp_send_json_error('Sin permisos suficientes.');
    }

    $procedure_id = absint($_POST['procedure_id'] ?? 0);

    $user_email = sanitize_email($_POST['user_email'] ?? '');
    $procedureStatus = sanitize_text_field($_POST['procedure_status'] ?? '');

    if (empty($user_email) || $procedure_id === 0) {
        wp_send_json_error('Faltan datos.');
    }

    $statuses = pho_get_all_statuses();
    $label_required = $statuses['member']['label'] ?? 'Miembro del club';

    if ($procedureStatus !== 'member') {
        wp_send_json_error("El estado del trámite debe ser \"$label_required\" para poder asignar el rol.");
    }

    $user = get_user_by('email', $user_email);

    if (!$user) {
        wp_send_json_error('Usuario no encontrado.');
    }

    if (in_array('club_member', (array) $user->roles)) {
        $user->remove_role('club_member');
        delete_post_meta($procedure_id, 'Member_Number');
        wp_send_json_success('❌ Rol eliminado y número de miembro eliminado.');
    } else {
        $user->add_role('club_member');
        $telefono = get_post_meta($procedure_id, 'Phone', true);
        $member_number = pho_get_member_number_from_phone($user->ID, $telefono);
        update_post_meta($procedure_id, 'Member_Number', $member_number);
        wp_send_json_success('✅ Rol agregado y número de miembro asignado.');
    }

    $telefono = get_post_meta($procedure_id, 'Phone', true);
    $member_number = pho_get_member_number_from_phone($user->ID, $telefono);
    update_post_meta($procedure_id, 'Member_Number', $member_number);

    wp_send_json_success('✅ Rol de Miembro del club y número de miembro asignado.');
}
add_action('wp_ajax_pho_add_club_member_role', 'pho_add_club_member_role');

