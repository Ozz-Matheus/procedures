<?php

if (!isset($procedure_id)) return;

$current_user = wp_get_current_user();
$user_id = $current_user->ID;

// Metadatos del trámite actual
$nombre = get_post_meta($procedure_id, 'Names', true);
$foto = get_post_meta($procedure_id, 'Profile_pic', true);
$logo = get_option( 'woocommerce_email_header_image' );
$qr_url = pho_generate_qr_image_url($user_id);
$phone = get_post_meta($procedure_id, 'Phone', true);
$member_number = pho_get_member_number_from_phone($user_id, $phone);
$fecha_afiliacion = pho_get_affiliation_date($procedure_id);

?>
<div class="wpb_column vc_column_container vc_col-sm-12 margin-top-40">
    <div class="pho-wallet-card margin-bottom-40" style="max-width: 360px; border-radius: 16px; border: 1px solid #ccc; padding: 20px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); font-family: sans-serif; margin-top: 40px;     background-color: #ffffff;">
        <?php if ($logo): ?>
            <div style="text-align:center;">
                <img src="<?php echo esc_url($logo); ?>" style="height: 60px; margin-bottom: 20px;" alt="Logo">
            </div>
        <?php else: ?>
            <div style="text-align:center;">
                <P><?php echo esc_html(get_bloginfo('name')); ?></P>
            </div>
        <?php endif; ?>

        <div style="text-align: center; margin-top: 20px; margin-bottom: 20px;">
            <?php if ($foto): ?>
                <img src="<?php echo esc_url($foto); ?>" style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover;">
            <?php else: ?>
                <div style="width: 80px; height: 80px; border-radius: 50%; background-color: #ccc; display: inline-block;"></div>
            <?php endif; ?>
            <h5 style="margin: 10px 0 0;"><?php echo esc_html($nombre); ?></h5>
            <small style="display: block; margin: 10px 0;"><strong>N° miembro:</strong> <?php echo esc_html($member_number); ?></small>
        </div>

        <div style="text-align:center; margin-bottom: 20px;">
            <img src="<?php echo esc_url($qr_url); ?>" style="width: 180px; height: 180px;" alt="QR">
        </div>

        <div style="text-align:center;">
            <strong>Afiliado desde:</strong><br>
            <span><?php echo esc_html($fecha_afiliacion); ?></span>
        </div>
    </div>
</div>