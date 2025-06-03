<?php

// Agrega procedure al nombre de los archivos subidos
function agregar_prefijo_procedure($archivo, $prefijo = 'procedure-') {

    if (!empty($archivo['tmp_name'])) {
        // Obtener el nombre original del archivo
        $nombre_original = basename($archivo['name']);
        // Añadir el prefijo al nombre del archivo
        $nuevo_nombre = $prefijo . $nombre_original;
        // Actualizar el nombre del archivo
        $archivo['name'] = $nuevo_nombre;
        // Subir el archivo con el nuevo nombre
        $upload = wp_handle_upload($archivo, array('test_form' => false));
        return $upload;
    }
    return false;
}

// Procesamiento anticipado para evitar headers already sent
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['generate_credential']) && isset($_FILES['profile_pic'])) {
    $procedure_id = absint($_POST['procedure_id']);
    $uploaded = $_FILES['profile_pic'];

    if ($uploaded['error'] === UPLOAD_ERR_OK) {
        require_once ABSPATH . 'wp-admin/includes/file.php';

        $upload = agregar_prefijo_procedure($uploaded, 'profile-pic-');

        if (!isset($upload['error'])) {
            update_post_meta($procedure_id, 'Profile_Pic', esc_url_raw($upload['url']));
            // Realizar una redirección después de mostrar el mensaje
            $procedure_url = wc_get_account_endpoint_url('tramites');
            echo '<script>
                setTimeout(function(){
                    window.location.href="' . esc_url($procedure_url) . '";
                }, 2000); // 2000 milisegundos = 2 segundos
            </script>';
            exit;
        } else {
            $pho_upload_error = '⚠️ Error al subir la imagen: ' . esc_html($upload['error']);
        }
    }
}
?>

<div class="margin-bottom-40 underline">
    <h1 class="margin-bottom-40">Trámites</h1>
</div>

<?php
    while ($procedures->have_posts()) {
        $procedures->the_post();

        // Obtén el ID del Trámite del usuario actual
        $procedure_id = get_the_ID(); // función para obtener el ID del Trámite del usuario

        $status_slug = get_post_meta(get_the_ID(), 'Status', true);
        $status_label = pho_get_all_statuses()[$status_slug]['label'] ?? ucfirst($status_slug);

        // Migración silenciosa
        if ($status_slug !== 'member' && $status_slug === 'Ya eres asociado de Club de Thulio A.C. en Libro de Asociados') {
            update_post_meta(get_the_ID(), 'Status', 'member');
            $status_slug = 'member';
        }

        $status_label = pho_get_all_statuses()[$status_slug]['label'] ?? ucfirst($status_slug);

        $foto_perfil = get_post_meta($procedure_id, 'Profile_Pic', true);
?> 

<div class="margin-top-40 margin-bottom-40">
    <h2 class="h4"><?php esc_html_e(get_post_meta(get_the_ID(), 'Names', true)); ?></h2>
</div>

<div class="box row margin-bottom-40 underline">
    <div class="col-12 col-md-4">
        <h3><?php show_status_procedure($status_slug); ?> | Estado</h3>
        <p><?php echo esc_html($status_label); ?></p>
    </div>
    <div class="col-12 col-md-4">
        <h3>Correo Electrónico</h3>
        <p> <?php the_title(); ?> </p>
    </div>
    <div class="col-12 col-md-4">
        <h3>Nombre Completo</h3>
        <p> <?php esc_html_e(get_post_meta(get_the_ID(), 'Names', true)); ?> </p>
    </div>
    <div class="col-12 col-md-4">
        <h3>Teléfono</h3>
        <p> <?php esc_html_e(get_post_meta(get_the_ID(), 'Phone', true)); ?> </p>
    </div>
    <div class="col-12 col-md-4">
        <h3>CURP</h3>
        <p> <?php esc_html_e(get_post_meta(get_the_ID(), 'Curp', true)); ?> </p>
    </div>
    <div class="col-12 col-md-4">
        <h3>RFC</h3>
        <p> <?php esc_html_e(get_post_meta(get_the_ID(), 'Rfc', true)); ?> </p>
    </div>
    <div class="col-12">
        <h3>Observaciones</h3>
        <p> <?php echo wpautop( get_post_meta(get_the_ID(), 'Observations', true) ); ?> </p>
    </div>
</div>
   
<?php

// Verifica si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_procedure'])) {

    $observations = 'Proceso en revisión, esto puede tardar de 24 a 48 horas.';

    // Actualiza los campos del Trámite
    $nombres = mb_strtoupper(sanitize_text_field($_POST['nombres']), 'UTF-8');
    $telefono = sanitize_text_field($_POST['telefono']);
    $curp = strtoupper(sanitize_text_field($_POST['curp']));
    $rfc = strtoupper(sanitize_text_field($_POST['rfc']));

    // Manejo de archivos utilizando la nueva función

    // Archivo INE Frente
    $upload_ine_frente = isset($_FILES['ine_file_front']) ? agregar_prefijo_procedure($_FILES['ine_file_front']) : false;
    if (!empty($upload_ine_frente['url'])) {
        update_post_meta($procedure_id, 'Ine_file_front', $upload_ine_frente['url']);
    }

    // Archivo INE Respaldo
    $upload_ine_respaldo = isset($_FILES['ine_file_back']) ? agregar_prefijo_procedure($_FILES['ine_file_back']) : false;
    if (!empty($upload_ine_respaldo['url'])) {
        update_post_meta($procedure_id, 'Ine_file_back', $upload_ine_respaldo['url']);
    }

    // Archivo CURP
    $upload_curp_archivo = isset($_FILES['curp_file']) ? agregar_prefijo_procedure($_FILES['curp_file']) : false;
    if (!empty($upload_curp_archivo['url'])) {
        update_post_meta($procedure_id, 'Curp_file', $upload_curp_archivo['url']);
    }

    // Archivo RFC
    $upload_rfc_archivo = isset($_FILES['rfc_file']) ? agregar_prefijo_procedure($_FILES['rfc_file']) : false;
    if (!empty($upload_rfc_archivo['url'])) {
        update_post_meta($procedure_id, 'Rfc_file', $upload_rfc_archivo['url']);
    }

    // Archivo de Autorización
    $upload_autorizacion_archivo = isset($_FILES['autorizacion']) ? agregar_prefijo_procedure($_FILES['autorizacion']) : false;
    if (!empty($upload_autorizacion_archivo['url'])) {
        update_post_meta($procedure_id, 'Authorization', $upload_autorizacion_archivo['url']);
    }

    $status = get_post_meta(get_the_ID(), 'Status', true);

    if ($status == 'incomplete') {
        $status = "pending";
    }

    update_post_meta($procedure_id, 'Names', $nombres);
    update_post_meta($procedure_id, 'Phone', $telefono);
    update_post_meta($procedure_id, 'Curp', $curp);
    update_post_meta($procedure_id, 'Rfc', $rfc);
    update_post_meta($procedure_id, 'Observations', $observations);
    update_post_meta($procedure_id, 'Status', $status);

    echo '<div class="alert alert-success"><span>¡Cambios guardados correctamente!</span></div>';

    // Realizar una redirección después de mostrar el mensaje
    $procedure_url = wc_get_account_endpoint_url('tramites');
    echo '<script>
        setTimeout(function(){
            window.location.href="' . esc_url($procedure_url) . '";
        }, 2000); // 2000 milisegundos = 2 segundos
    </script>';
    exit; // Importante para evitar ejecución adicional del código
}

// Obtiene los detalles del Trámite
$nombres_actual = get_post_meta($procedure_id, 'Names', true);
$telefono_actual = get_post_meta($procedure_id, 'Phone', true);
$ine_archivo_frente_actual = get_post_meta($procedure_id, 'Ine_file_front', true);
$ine_archivo_respaldo_actual = get_post_meta($procedure_id, 'Ine_file_back', true);
$curp_actual = get_post_meta($procedure_id, 'Curp', true);
$curp_archivo_actual = get_post_meta($procedure_id, 'Curp_file', true);
$rfc_actual = get_post_meta($procedure_id, 'Rfc', true);
$rfc_archivo_actual = get_post_meta($procedure_id, 'Rfc_file', true);
$autorizacion_archivo_actual = get_post_meta($procedure_id, 'Authorization', true);
?>
  
<?php if(get_post_meta(get_the_ID(), 'Deactivate', true) == NULL): ?>
    <div class="margin-bottom-40 underline">
        <form method="post" enctype="multipart/form-data">
            <label class="required" for="nombres"><strong>NOMBRE COMPLETO:</strong></label>
            <input class="margin-bottom-40" type="text" name="nombres" id="nombres" value="<?php echo esc_attr($nombres_actual); ?>" required>
            <label for="telefono"><strong>TELÉFONO CON WHATSAPP:</strong></label>
            <input class="margin-bottom-40" type="tel"  name="telefono" id="telefono" value="<?php echo esc_attr($telefono_actual); ?>" required>
            <label class="required"  for="ine"><strong>INE:</strong></label>
            <div class="margin-bottom-20"></div>
            <?php if(!$ine_archivo_frente_actual AND !$ine_archivo_respaldo_actual): ?>
            <div class="margin-bottom-20">
                <div class="row">
                   <div class="col-md-6 p-r">
                        <label class="required" for="ine_file_front"><strong> AGREGAR IMAGEN DE TU INE FRENTE:</strong></label>
                        <input type="file" name="ine_file_front" id="ine_file_front" accept="image/*,.pdf" required>
                    </div>
                    <div class="col-md-6 p-l">
                        <label class="required" for="ine_file_back"><strong> AGREGAR IMAGEN DE TU INE RESPALDO:</strong></label>
                        <input type="file" name="ine_file_back" id="ine_file_back" accept="image/*,.pdf" required>
                    </div>
                </div>
                <strong>Por favor tener en cuenta que el fondo sea blanco y que el documento tenga la mejor resolución posible.</strong>
            </div>
            <?php endif; ?>
            <div class="margin-bottom-40">
            <?php if($ine_archivo_frente_actual): ?>
                <?php if(strtolower(pathinfo($ine_archivo_frente_actual, PATHINFO_EXTENSION)) == 'pdf'): ?>
                    <a class="btn btn-link margin-right-20" target="_blank" href="<?php echo esc_url($ine_archivo_frente_actual); ?>">Ver INE Frente (PDF)</a>
                <?php else: ?>
                    <img class="img-small" src="<?php echo esc_url($ine_archivo_frente_actual); ?>" alt="Vista del INE Frente">
                <?php endif; ?>
            <?php endif; ?>
            <?php if($ine_archivo_respaldo_actual): ?>
                <?php if(strtolower(pathinfo($ine_archivo_respaldo_actual, PATHINFO_EXTENSION)) == 'pdf'): ?>
                    <a class="btn btn-link margin-left-20" target="_blank" href="<?php echo esc_url($ine_archivo_respaldo_actual); ?>">Ver INE Respaldo (PDF)</a>
                <?php else: ?>
                    <img class="img-small" src="<?php echo esc_url($ine_archivo_respaldo_actual); ?>" alt="Vista del INE Respaldo">
                <?php endif; ?>
            <?php endif; ?>
            </div>
            <label class="required" for="curp"><strong>CURP O NÚMERO DE PASAPORTE:</strong></label>
            <input class="margin-bottom-20" type="text" name="curp" id="curp" value="<?php echo esc_attr($curp_actual); ?>" required>
            <?php if(!$curp_archivo_actual): ?>
            <div class="margin-bottom-20">
                <label class="required" for="curp_file"><strong> AGREGAR IMAGEN DE TU CURP O NÚMERO DE PASAPORTE:</strong></label>
                <input type="file" name="curp_file" id="curp_file" accept="image/*,.pdf" required>
                <strong>Consulta y descarga en línea tu Clave Única de Registro de Población (CURP) <a class="button-small" href="https://www.gob.mx/curp/" target="_blank"> aquí</a> .</strong>
            </div>
            <?php endif; ?>
            <div class="margin-bottom-40">
            <?php if($curp_archivo_actual): ?>
                <?php if(strtolower(pathinfo($curp_archivo_actual, PATHINFO_EXTENSION)) == 'pdf'): ?>
                    <a class="btn btn-link" target="_blank" href="<?php echo esc_url($curp_archivo_actual); ?>">Ver CURP (PDF)</a>
                <?php else: ?>
                    <img class="img-small" src="<?php echo esc_url($curp_archivo_actual); ?>" alt="Vista del CURP">
                <?php endif; ?>
            <?php endif; ?>
            </div>
            <label for="rfc"><strong>RFC:</strong></label>
            <input class="margin-bottom-20" type="text" name="rfc" id="rfc" value="<?php echo esc_attr($rfc_actual); ?>">
            <?php if(!$rfc_archivo_actual): ?>
            <div class="margin-bottom-20">
                <label for="rfc_file"><strong> AGREGAR IMAGEN DE TU RFC:</strong></label>
                <input type="file" name="rfc_file" id="rfc_file" accept="image/*,.pdf" >
            </div>
            <?php endif; ?>
            <div class="margin-bottom-40">
            <?php if($rfc_archivo_actual): ?>
                <?php if(strtolower(pathinfo($rfc_archivo_actual, PATHINFO_EXTENSION)) == 'pdf'): ?>
                    <a class="btn btn-link" target="_blank" href="<?php echo esc_url($rfc_archivo_actual); ?>">Ver RFC (PDF)</a>
                <?php else: ?>
                    <img class="img-small" src="<?php echo esc_url($rfc_archivo_actual); ?>" alt="Vista del RFC">
                <?php endif; ?>
            <?php endif; ?>
            </div>
            <label for="autorizacion"><strong>AUTORIZACIÓN SANITARIA COFEPRIS:</strong></label>
            <div class="margin-bottom-20"></div>
            <?php if(!$autorizacion_archivo_actual): ?>
            <div class="margin-bottom-20">
                <label for="autorizacion"><strong> AGREGAR PDF:</strong></label>
                <input type="file" name="autorizacion" id="autorizacion" accept=".pdf" >
                <strong>En caso de contar con Autorización Sanitaria de  COFEPRIS, por favor sube tu copia digital aquí.</strong>
            </div>
            <?php endif; ?>
            <div class="margin-bottom-40">
            <?php if($autorizacion_archivo_actual): ?>
                <?php if(strtolower(pathinfo($autorizacion_archivo_actual, PATHINFO_EXTENSION)) == 'pdf'): ?>
                    <a class="btn btn-link" target="_blank" href="<?php echo esc_url($autorizacion_archivo_actual); ?>">Ver Autorización Sanitaria (PDF)</a>
                <?php else: ?>
                    <img class="img-small" src="<?php echo esc_url($autorizacion_archivo_actual); ?>" alt="Ver Autorización Sanitaria">
                <?php endif; ?>
            <?php endif; ?>
            </div>
            <div class="margin-bottom-40">
                <!-- Submit -->
                <input type="submit" name="save_procedure" value="Actualizar Cambios">
            <div>
        </form>
        <script>
        function validateFileSizes() {
            const maxSize = 1000 * 1024; // 1000 KB en bytes
            const fileInputs = document.querySelectorAll('input[type="file"]');

            for (let i = 0; i < fileInputs.length; i++) {
                const fileInput = fileInputs[i];
                const files = fileInput.files;

                for (let j = 0; j < files.length; j++) {
                    const file = files[j];
                    if (file.size > maxSize) {
                        alert(`El archivo "${file.name}" supera el tamaño máximo permitido de 1 MB.`);
                        return false; // Impide que el formulario se envíe
                    }
                }
            }

            // Si todos los archivos cumplen con el tamaño, permite el envío del formulario
            return true;
        }
        </script>
    </div>
<?php endif; ?>
<?php

    if(get_post_meta(get_the_ID(), 'Status', true) == 'member'):

        // Si aún no tiene foto, mostrar formulario
        if (!$foto_perfil):
        ?>
            <form method="post" enctype="multipart/form-data">
                <input type="hidden" name="procedure_id" value="<?php echo esc_attr($procedure_id); ?>">
                <label for="profile_pic"><strong>Sube tu foto para la credencial (PNG/JPG, cuadrada):</strong></label><br>
                <input type="file" name="profile_pic" id="profile_pic" accept="image/*" required><br><br>
                <input type="submit" name="generate_credential" value="Generar Credencial" class="button">
            </form>
        <?php

        else:

            $credentialCard = plugin_dir_path(__FILE__) . '../template-parts/content-credential-card.php';

            if (file_exists($credentialCard)) {
                require_once $credentialCard;
            }

        endif;

        $templateFooter = plugin_dir_path(__FILE__) . '../template-parts/extra/content-footer.php';

        if (file_exists($templateFooter)) {
            require_once $templateFooter;
        }

    endif;
?>
<?php
    } wp_reset_postdata();
?>

