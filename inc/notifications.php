<?php

function pho_enqueue_admin_notification_script($hook) {
    global $post;


    if ($hook === 'post.php' && isset($post) && $post->post_type === 'procedures') {

        $procedure_status_slug = get_post_meta($post->ID, 'Status', true);
        $procedure_status_label = pho_get_all_statuses()[$procedure_status_slug]['label'] ?? ucfirst($procedure_status_slug);

        wp_enqueue_script(
            'pho-admin-notification',
            plugin_dir_url(__FILE__) . '../inc/js/admin-notification.js',
            array('jquery'),
            '1.2',
            true
        );

        wp_localize_script('pho-admin-notification', 'phoNotificationData', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'procedure_id' => $post->ID,
            'user_email' => get_the_title($post->ID),
            'procedure_status_slug' => $procedure_status_slug,
            'procedure_status_label' => $procedure_status_label,
            'procedure_observations' => wpautop(get_post_meta($post->ID, 'Observations', true)),
        ));
    }
}
add_action('admin_enqueue_scripts', 'pho_enqueue_admin_notification_script');
