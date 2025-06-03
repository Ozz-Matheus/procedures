<?php

function show_status_procedure($status_slug) {
    if (!function_exists('pho_get_all_statuses')) {
        echo '<i class="fas fa-stop-circle inactive"></i>';
        return;
    }

    $statuses = pho_get_all_statuses();

    if (isset($statuses[$status_slug])) {
        echo $statuses[$status_slug]['icon'];
    } else {
        echo '<i class="fas fa-stop-circle inactive"></i>';
    }
}

// Genera el número de miembro: prefijo + ID + últimos 4 del teléfono
function pho_get_member_number_from_phone($user_id, $raw_phone) {
    $prefix = get_option('pho_member_prefix', 'PHO');
    $last4 = substr(preg_replace('/\D/', '', $raw_phone), -4);
    return $prefix . $user_id . $last4;
}

// Genera la URL para el QR
function pho_get_qr_url() {
    $base = trailingslashit(get_option('pho_qr_url_base', home_url()));
    return $base;
}

// Devuelve imagen QR (usando Google Chart API)
function pho_generate_qr_image_url($user_id) {
    return 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . urlencode(pho_get_qr_url());
}

// Devuelve la fecha de publicación del trámite
function pho_get_affiliation_date($post_id) {
    $post = get_post($post_id);
    return $post ? date_i18n('d/m/Y', strtotime($post->post_date)) : '';
}

