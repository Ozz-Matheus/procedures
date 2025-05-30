<?php

function pho_redirect_single_procedures() {
    if (is_singular('procedures')) {
        wp_redirect(home_url());
        exit;
    }
}
add_action('template_redirect', 'pho_redirect_single_procedures');