<?php

if (!function_exists('show_status_procedure')) {
    function show_status_procedure($status) {
        $icons = array(
            'Solicitud de permiso ante Cofepris' => '<i class="fas fa-file-import red"></i>',
            'En espera de Respuesta de Cofepris' => '<i class="fas fa-spinner fa-spin orange"></i>',
            'Presentación de demanda de amparo' => '<i class="fas fa-file yellow"></i>',
            'En espera de aprobación del Club' => '<i class="fas fa-hourglass blue-dark"></i>',
            'En espera de sentencia' => '<i class="fas fa-user-clock blue"></i>',
            'Resultado Final' => '<i class="fas fa-check-circle green"></i>',
            'Solicitud incompleta' => '<i class="fas fa-user-times red"></i>',
            'En proceso de afiliación a nuestra AC' => '<i class="fas fa-joint orange"></i>',
            'Ya eres asociado de Club de Thulio A.C. en Libro de Asociados' => '<i class="fas fa-cannabis green"></i>',
        );

        echo isset($icons[$status]) ? $icons[$status] : '<i class="fas fa-stop-circle inactive"></i>';
    }
}