<?php

function pho_plugin_activate() {
    // Asegurar endpoints y CPT
    pho_register_custom_post_type_procedures();
    pho_register_my_account_endpoint();
    flush_rewrite_rules();

    // Asegurar roles y capacidades personalizados
    pho_add_custom_capabilities_for_non_admins();
}
