<?php

function pho_register_custom_post_type_procedures() {
    register_post_type('procedures', array(
        'label' => 'Trámites',
        'public' => true,
        'rewrite' => array('slug' => 'procedure'),
        'show_in_menu' => true,
        'menu_position' => 20,
        'menu_icon' => 'dashicons-clipboard',
        'supports' => array('title', 'revisions'),
        'labels' => array(
            'singular_name' => 'Trámite',
            'add_new' => 'Agregar Nuevo',
            'add_new_item' => 'Agregar Nuevo Trámite',
            'edit_item' => 'Editar Trámite',
            'view_item' => 'Ver Trámite'
        ),
        'capabilities' => array(
            'edit_post'           => 'edit_procedure',
            'read_post'           => 'read_procedure',
            'delete_post'         => 'delete_procedure',
            'edit_posts'          => 'edit_procedures',
            'edit_others_posts'   => 'edit_others_procedures',
            'publish_posts'       => 'publish_procedures',
            'read_private_posts'  => 'read_private_procedures',
        ),
        'map_meta_cap' => true,
    ));
}
add_action('init', 'pho_register_custom_post_type_procedures');

function pho_add_procedure_status_column($columns) {
    $columns['procedure_name'] = __('Nombre Completo', 'pho-procedures');
    $columns['procedure_number'] = __('Número de Miembro', 'pho-procedures');
    $columns['procedure_phone'] = __('Whatsapp', 'pho-procedures');
    $columns['procedure_curp'] = __('CURP o Pasaporte', 'pho-procedures');
    $columns['procedure_rfc'] = __('RFC', 'pho-procedures');
    $columns['procedure_status'] = __('Estado del Trámite', 'pho-procedures');
    return $columns;
}
add_filter('manage_procedures_posts_columns', 'pho_add_procedure_status_column');

function pho_display_procedure_status_column($column, $post_id) {

    if ($column === 'procedure_name') {

        echo esc_html(get_post_meta($post_id, 'Names', true));
    }

    if ($column === 'procedure_number') {

        echo esc_html(get_post_meta($post_id, 'Member_Number', true));
    }

    if ($column === 'procedure_phone') {

        echo esc_html(get_post_meta($post_id, 'Phone', true));
    }

    if ($column === 'procedure_curp') {

        echo esc_html(get_post_meta($post_id, 'Curp', true));
    }

    if ($column === 'procedure_rfc') {

        echo esc_html(get_post_meta($post_id, 'Rfc', true));
    }

    if ($column === 'procedure_status') {

        $slug = get_post_meta($post_id, 'Status', true);
        $statuses = pho_get_all_statuses();
        $label = isset($statuses[$slug]) ? $statuses[$slug]['label'] : ucfirst($slug);

        echo esc_html($label);
    }
}
add_action('manage_procedures_posts_custom_column', 'pho_display_procedure_status_column', 10, 2);