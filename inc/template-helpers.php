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
