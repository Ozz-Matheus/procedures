<?php

function pho_enqueue_admin_notification_script($hook) {
    global $post;

    if ($hook === 'post.php' && isset($post) && $post->post_type === 'procedures') {
        wp_enqueue_script(
            'pho-admin-notification',
            plugin_dir_url(__FILE__) . '../inc/js/admin-notification.js',
            array('jquery'),
            '1.0',
            true
        );

        wp_localize_script('pho-admin-notification', 'phoNotificationData', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'user_email' => get_the_title($post->ID),
            'procedure_status' => get_post_meta($post->ID, 'Status', true),
            'procedure_observations' => wpautop(get_post_meta($post->ID, 'Observations', true)),
        ));
    }
}
add_action('admin_enqueue_scripts', 'pho_enqueue_admin_notification_script');
