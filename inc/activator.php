<?php

function pho_plugin_activate() {
    // Registrar CPT y endpoint personalizado
    pho_register_custom_post_type_procedures();
    pho_register_my_account_endpoint();
    flush_rewrite_rules();

    // Registrar rol y capacidades
    pho_register_club_member_role();
    pho_grant_procedure_caps_to_admins_and_managers();
}
