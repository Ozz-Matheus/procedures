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
        'capability_type' => 'post'
    ));
}
add_action('init', 'pho_register_custom_post_type_procedures');

function pho_add_procedure_status_column($columns) {
    $columns['procedure_name'] = __('Nombre Completo', 'pho-procedures');
    $columns['procedure_status'] = __('Estado del Trámite', 'pho-procedures');
    return $columns;
}
add_filter('manage_procedures_posts_columns', 'pho_add_procedure_status_column');

function pho_display_procedure_status_column($column, $post_id) {

    if ($column === 'procedure_name') {

        echo esc_html(get_post_meta($post_id, 'Names', true));
    }

    if ($column === 'procedure_status') {

        $slug = get_post_meta($post_id, 'Status', true);
        $statuses = pho_get_all_statuses();
        $label = isset($statuses[$slug]) ? $statuses[$slug]['label'] : ucfirst($slug);

        echo esc_html($label);
    }
}
add_action('manage_procedures_posts_custom_column', 'pho_display_procedure_status_column', 10, 2);