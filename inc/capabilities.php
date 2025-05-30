<?php

function pho_add_custom_capabilities_for_non_admins() {
    $roles = array('subscriber', 'author', 'editor');

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