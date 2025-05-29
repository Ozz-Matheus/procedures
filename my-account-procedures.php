<?php
// Consultar los Trámites del usuario actual
$current_user = wp_get_current_user();
$args = array(
    'post_type' => 'procedures',
    'author' => $current_user->ID,
);
$procedures = new WP_Query($args);

// Mostrar los Trámites
if ($procedures->have_posts()) {

    require_once dirname( __FILE__ ) . '/template-parts/content.php';

}