<?php
// Añadir página de configuración
add_action('admin_menu', function () {
    add_options_page(
        'Credencial PHO',
        'Credencial PHO',
        'manage_options',
        'pho_credential_settings',
        'pho_render_credential_settings_page',
        23
    );
});

// Renderizar formulario
function pho_render_credential_settings_page() {
    ?>
    <div class="wrap">
        <h1>Configuración de la Credencial Digital</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('pho_credential_settings');
            do_settings_sections('pho_credential_settings');
            ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Prefijo del Número de Miembro</th>
                    <td><input type="text" name="pho_member_prefix" value="<?php echo esc_attr(get_option('pho_member_prefix', 'PHO')); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Número de WhatsApp</th>
                    <td><input type="tel" name="pho_whatsapp_number" value="<?php echo esc_attr(get_option('pho_whatsapp_number', '5215555555555')); ?>" /></td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

// Registrar ajustes
add_action('admin_init', function () {
    register_setting('pho_credential_settings', 'pho_member_prefix');
    register_setting('pho_credential_settings', 'pho_whatsapp_number');
});
