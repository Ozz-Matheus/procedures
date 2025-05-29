<?php
/* CUSTOM FIELDS */

/*
* Procedure
*/
add_action( 'cmb2_admin_init', 'pho_fields_procedure' );
/**
 * Hook in and add a metabox to demonstrate repeatable grouped fields
 */

function pho_fields_procedure(){
    
    $prefix = 'pho_procedure_';

    /*
     * Repeatable Field Groups
    */
    $pho_procedure = new_cmb2_box( array(
        'id'           => $prefix . 'metabox',
        'title'        => esc_html__( 'TRÁMITES', 'cmb2' ),
        'object_types' => array( 'procedures' ),
        'context' 	=> 'normal',
        'priority'	=> 'high',
        'show_names'   => 'true',
    ) );

    /* SECCIÓN MASTHEAD */
    $pho_procedure->add_field( array(
        'desc' => esc_html__( 'Sección de Trámites', 'cmb2' ),
        'id'   => $prefix . 'title',
        'type' => 'title',
    ) );
    $pho_procedure->add_field( array(
        'name' => esc_html__( 'FORMULARIO', 'cmb2' ),
        'desc' => esc_html__( 'Desactivar el formulario de captación para el trámite.', 'cmb2' ),
        'id'   => 'Deactivate',
        'type' => 'checkbox',
    ) );
    $pho_procedure->add_field( array(
        'name' => esc_html__( 'ESTADO', 'cmb2' ),
        'desc' => esc_html__( 'Estado del tramite', 'cmb2' ),
        'id'   => 'Status',
        'type' => 'select',
        'show_option_none' => true,
        'options'          => array(
            'Solicitud de permiso ante Cofepris'     => __( 'Solicitud de permiso ante Cofepris', 'cmb2' ),
            'En espera de Respuesta de Cofepris'     => __( 'En espera de Respuesta de Cofepris', 'cmb2' ),
            'Presentación de demanda de amparo'     => __( 'Presentación de demanda de amparo', 'cmb2' ),
            'En espera de aprobación del Club'     => __( 'En espera de aprobación del Club', 'cmb2' ),
            'En espera de sentencia'     => __( 'En espera de sentencia', 'cmb2' ),
            'Resultado Final'     => __( 'Resultado Final', 'cmb2' ),
            'Solicitud incompleta'     => __( 'Solicitud incompleta', 'cmb2' ),
            'En proceso de afiliación a nuestra AC'     => __( 'En proceso de afiliación a nuestra AC', 'cmb2' ),
            'Ya eres asociado de Club de Thulio A.C. en Libro de Asociados'     => __( 'Ya eres asociado de Club de Thulio A.C. en Libro de Asociados', 'cmb2' ),

        ),

    ) );
    $pho_procedure->add_field( array(
        'name' => esc_html__( 'NOMBRES', 'cmb2' ),
        'desc' => esc_html__( 'Nombre completo acá.', 'cmb2' ),
        'id'   => 'Names',
        'type' => 'text',
    ) );
    $pho_procedure->add_field( array(
        'name' => esc_html__( 'TELÉFONO', 'cmb2' ),
        'desc' => esc_html__( 'Teléfono de WhatsApp.', 'cmb2' ),
        'id'   => 'Phone',
        'type' => 'text',
    ) );
    $pho_procedure->add_field( array(
        'name' => esc_html__( 'CARGUE LA IMAGEN O INGRESE UNA URL DEL INE PARTE FRONTAL', 'cmb2' ),
        'desc' => esc_html__( 'Cargue la imagen o ingrese una URL del Ine parte frontal', 'cmb2' ),
        'id'   => 'Ine_file_front',
        'type' => 'file',
        'text'    => array(
            'add_upload_file_text' => 'Agregar Archivo' // Change upload button text. Default: "Add or Upload File"
        ),
        'query_args' => array( 'type' => 'image' ),
    ) );
    $pho_procedure->add_field( array(
        'name' => esc_html__( 'CARGUE LA IMAGEN O INGRESE UNA URL DEL INE PARTE RESPALDO', 'cmb2' ),
        'desc' => esc_html__( 'Cargue la imagen o ingrese una URL del Ine parte respaldo', 'cmb2' ),
        'id'   => 'Ine_file_back',
        'type' => 'file',
        'text'    => array(
            'add_upload_file_text' => 'Agregar Archivo' // Change upload button text. Default: "Add or Upload File"
        ),
        'query_args' => array( 'type' => 'image' ),
    ) );
    $pho_procedure->add_field( array(
        'name' => esc_html__( 'CURP', 'cmb2' ),
        'desc' => esc_html__( 'Clave Única de Registro de Población', 'cmb2' ),
        'id'   => 'Curp',
        'type' => 'text',
    ) );
    $pho_procedure->add_field( array(
        'name' => esc_html__( 'CARGUE LA IMAGEN O INGRESE UNA URL DEL CURP', 'cmb2' ),
        'desc' => esc_html__( 'Cargue la imagen o ingrese una URL del Curp', 'cmb2' ),
        'id'   => 'Curp_file',
        'type' => 'file',
        'text'    => array(
            'add_upload_file_text' => 'Agregar Archivo' // Change upload button text. Default: "Add or Upload File"
        ),
        'query_args' => array( 'type' => 'image', 'application/pdf' ),
    ) );
    $pho_procedure->add_field( array(
        'name' => esc_html__( 'RFC', 'cmb2' ),
        'desc' => esc_html__( 'Registro Federal de Contribuyentes', 'cmb2' ),
        'id'   => 'Rfc',
        'type' => 'text',
    ) );
    $pho_procedure->add_field( array(
        'name' => esc_html__( 'CARGUE LA IMAGEN O INGRESE UNA URL DEL RFC', 'cmb2' ),
        'desc' => esc_html__( 'Cargue la imagen o ingrese una URL del Rfc', 'cmb2' ),
        'id'   => 'Rfc_file',
        'type' => 'file',
        'text'    => array(
            'add_upload_file_text' => 'Agregar Archivo' // Change upload button text. Default: "Add or Upload File"
        ),
        'query_args' => array( 'type' => 'image' ),
    ) );
    $pho_procedure->add_field( array(
        'name' => esc_html__( 'RED SOCIAL', 'cmb2' ),
        'desc' => esc_html__( 'Red social', 'cmb2' ),
        'id'   => 'Social',
        'type' => 'text',
    ) );
    $pho_procedure->add_field( array(
        'name' => esc_html__( 'PERFIL SOCIAL', 'cmb2' ),
        'desc' => esc_html__( 'Perfil social', 'cmb2' ),
        'id'   => 'Perfil',
        'type' => 'text',
    ) );
    $pho_procedure->add_field( array(
        'name' => esc_html__( 'OBSERVACIONES', 'cmb2' ),
        'desc' => esc_html__( 'Observaciones', 'cmb2' ),
        'id'   => 'Observations',
        'type'    => 'wysiwyg',
        'options' => array(
            'wpautop' => true,
        ),
    ) );
    $pho_procedure->add_field( array(
        'name' => esc_html__( 'CARGUE AUTORIZACIÓN SANITARIA', 'cmb2' ),
        'desc' => esc_html__( 'Cargue Autorización Sanitaria COFEPRIS', 'cmb2' ),
        'id'   => 'Authorization',
        'type' => 'file',
        'text'    => array(
            'add_upload_file_text' => 'Agregar Archivo' // Change upload button text. Default: "Add or Upload File"
        ),
        'query_args' => array( 'type' => 'application/pdf' ),
    ) );
    /* SECCIÓN MASTHEAD */

    /* SECCIÓN BOTÓN DE NOTIFICACIÓN POR CORREO */

    $pho_procedure->add_field(array(
        'name' => 'Enviar notificación',
        'desc' => esc_html__( 'Envia una notificación de el estado por correo al usuario.', 'cmb2' ),
        'id'   => 'pho_send_notification',
        'type' => 'title',
        'classes' => 'pho-send-notification',
    ));

    /* SECCIÓN BOTÓN DE NOTIFICACIÓN POR CORREO */


} //END


