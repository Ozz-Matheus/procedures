<?php

function pho_plugin_activate() {
    // Asegurar endpoints y CPT
    pho_register_custom_post_type_procedures();
    pho_register_my_account_endpoint();
    flush_rewrite_rules();

    // Asegurar roles y capacidades personalizados
    pho_add_custom_capabilities_for_non_admins();

    $admin = get_role('administrator');
    if ($admin) {
        $admin->add_cap('edit_procedure');
        $admin->add_cap('read_procedure');
        $admin->add_cap('delete_procedure');
        $admin->add_cap('edit_procedures');
        $admin->add_cap('edit_others_procedures');
        $admin->add_cap('publish_procedures');
        $admin->add_cap('read_private_procedures');
    }
}
