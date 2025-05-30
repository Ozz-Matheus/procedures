<?php

add_action('cmb2_admin_init', 'pho_fields_procedure');

function pho_fields_procedure() {
    $prefix = 'pho_procedure_';

    $box = new_cmb2_box(array(
        'id' => $prefix . 'metabox',
        'title' => esc_html__('TRÁMITES', 'cmb2'),
        'object_types' => array('procedures'),
        'context' => 'normal',
        'priority' => 'high',
        'show_names' => true,
    ));

    $box->add_field(array(
        'desc' => esc_html__('Sección de Trámites', 'cmb2'),
        'id' => $prefix . 'title',
        'type' => 'title',
    ));

    $box->add_field(array(
        'name' => esc_html__('FORMULARIO', 'cmb2'),
        'desc' => esc_html__('Desactivar el formulario de captación para el trámite.', 'cmb2'),
        'id' => 'Deactivate',
        'type' => 'checkbox',
    ));

    $box->add_field(array(
        'name' => esc_html__('ESTADO', 'cmb2'),
        'desc' => esc_html__('Estado del trámite', 'cmb2'),
        'id' => 'Status',
        'type' => 'select',
        'show_option_none' => true,
        'options' => array(
            'Solicitud de permiso ante Cofepris' => __('Solicitud de permiso ante Cofepris', 'cmb2'),
            'En espera de Respuesta de Cofepris' => __('En espera de Respuesta de Cofepris', 'cmb2'),
            'Presentación de demanda de amparo' => __('Presentación de demanda de amparo', 'cmb2'),
            'En espera de aprobación del Club' => __('En espera de aprobación del Club', 'cmb2'),
            'En espera de sentencia' => __('En espera de sentencia', 'cmb2'),
            'Resultado Final' => __('Resultado Final', 'cmb2'),
            'Solicitud incompleta' => __('Solicitud incompleta', 'cmb2'),
            'En proceso de afiliación a nuestra AC' => __('En proceso de afiliación a nuestra AC', 'cmb2'),
            'Ya eres asociado de Club de Thulio A.C. en Libro de Asociados' => __('Ya eres asociado de Club de Thulio A.C. en Libro de Asociados', 'cmb2'),
        ),
    ));

    $fields = [
        ['NOMBRES', 'Nombre completo acá.', 'Names'],
        ['TELÉFONO', 'Teléfono de WhatsApp.', 'Phone'],
        ['CURP', 'Clave Única de Registro de Población', 'Curp'],
        ['RFC', 'Registro Federal de Contribuyentes', 'Rfc'],
        ['RED SOCIAL', 'Red social', 'Social'],
        ['PERFIL SOCIAL', 'Perfil social', 'Perfil'],
    ];

    foreach ($fields as [$name, $desc, $id]) {
        $box->add_field(array(
            'name' => esc_html__($name, 'cmb2'),
            'desc' => esc_html__($desc, 'cmb2'),
            'id' => $id,
            'type' => 'text',
        ));
    }

    $uploads = [
        ['INE PARTE FRONTAL', 'Ine_file_front'],
        ['INE PARTE RESPALDO', 'Ine_file_back'],
        ['CURP', 'Curp_file'],
        ['RFC', 'Rfc_file'],
        ['AUTORIZACIÓN SANITARIA', 'Authorization'],
    ];

    foreach ($uploads as [$desc, $id]) {
        $box->add_field(array(
            'name' => esc_html__('CARGUE LA IMAGEN O INGRESE UNA URL DEL ' . $desc, 'cmb2'),
            'desc' => esc_html__('Cargue la imagen o ingrese una URL del ' . $desc, 'cmb2'),
            'id' => $id,
            'type' => 'file',
            'text' => array('add_upload_file_text' => 'Agregar Archivo'),
            'query_args' => array('type' => in_array($id, ['Authorization']) ? 'application/pdf' : 'image'),
        ));
    }

    $box->add_field(array(
        'name' => esc_html__('OBSERVACIONES', 'cmb2'),
        'desc' => esc_html__('Observaciones', 'cmb2'),
        'id' => 'Observations',
        'type' => 'wysiwyg',
        'options' => array('wpautop' => true),
    ));

    $box->add_field(array(
        'name' => 'Enviar notificación',
        'desc' => esc_html__('Envia una notificación del estado por correo al usuario.', 'cmb2'),
        'id' => 'pho_send_notification',
        'type' => 'title',
        'classes' => 'pho-send-notification',
    ));
}
