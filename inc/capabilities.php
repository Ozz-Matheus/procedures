<?php
/**
 * Capabilities y Roles personalizados para PHO Procedures
 */

// Asegurar rol 'club_member'
function pho_register_club_member_role() {
    $role = get_role('club_member');

    if (!$role) {
        add_role(
            'club_member',
            __('Miembro del Club', 'pho-procedures'),
            [
                'read' => true,
                'publish_posts' => false,
                'edit_posts' => false,
            ]
        );
    } else {
        $role->add_cap('read');
        $role->remove_cap('publish_posts');
        $role->remove_cap('edit_posts');
    }
}
add_action('init', 'pho_register_club_member_role');

// Otorgar capacidades del CPT 'procedures' solo a admin y shop manager
function pho_grant_procedure_caps_to_admins_and_managers() {
    $roles = ['administrator', 'shop_manager'];
    $caps = [
        'edit_procedure',
        'read_procedure',
        'delete_procedure',
        'edit_procedures',
        'edit_others_procedures',
        'publish_procedures',
        'read_private_procedures',
    ];

    foreach ($roles as $role_name) {
        $role = get_role($role_name);
        if ($role) {
            foreach ($caps as $cap) {
                $role->add_cap($cap);
            }
        }
    }
}
add_action('init', 'pho_grant_procedure_caps_to_admins_and_managers');

// Acción AJAX para asignar/remover el rol club_member
function pho_add_club_member_role() {
    if (!current_user_can('manage_woocommerce')) {
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
}
add_action('wp_ajax_pho_add_club_member_role', 'pho_add_club_member_role');
