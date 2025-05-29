<?php
/**
 * Template para el correo electrónico personalizado
 *
*/

// Email Header
if ( file_exists( dirname( __FILE__ ) . '/template-parts/email-header.php' ) ) {
    require_once dirname( __FILE__ ) . '/template-parts/email-header.php';
}
?>

<h3>Estado del Trámite : </h3>
<p><?php echo esc_html( $email_state ); ?></p>

<h3>Observaciones : </h3>
<p><?php echo wp_kses_post( $email_observations ); ?></p>

<?php
// Email Footer
if ( file_exists( dirname( __FILE__ ) . '/template-parts/email-footer.php' ) ) {
    require_once dirname( __FILE__ ) . '/template-parts/email-footer.php';
}
