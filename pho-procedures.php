<?php
/*
Plugin Name: PHO Trámites
Plugin URI: https://orlandomontesinos.com/#tramites
Description: Trámites es un complemento que agrega funciones de trámite a la vista de usuario.
Version: 1.3
Author: Orlando Montesinos Quintana
Author URI: https://orlandomontesinos.com/
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: pho-procedures
*/


// Verificar si WooCommerce está activado
function if_active_woocommerce() {
    if ( class_exists( 'WooCommerce' ) ) {

        // Estilos CSS de  PHO Trámites
        function pho_procedures_register_scripts(){

            wp_enqueue_style( 'pho-procedures-plugin', plugin_dir_url( __FILE__ ) . 'inc/css/pho-procedures-style.css', array(), '1.6' );

        }
        add_action( 'wp_enqueue_scripts', 'pho_procedures_register_scripts' );

        // Cargar CMB2
        if ( file_exists( dirname( __FILE__ ) . '/cmb2/init.php' ) ) {
            require_once dirname( __FILE__ ) . '/cmb2/init.php';
        }


        // Agregar pestaña personalizada a la vista "Mi cuenta"
        function add_custom_tab_custom_my_account($my_account_tabs) {
            unset($my_account_tabs['downloads']);
            $my_account_tabs['tramites'] = __('Mis Trámites', 'pho-procedures');
            return $my_account_tabs;
        }

        add_filter('woocommerce_account_menu_items', 'add_custom_tab_custom_my_account');

        // Nuevas variables para la pagina de mi cuenta.
         function my_account_new_endpoints() {
            add_rewrite_endpoint( 'tramites', EP_ROOT | EP_PAGES );
        }

        add_action( 'init', 'my_account_new_endpoints' );

        // Modificar los elementos del menú de la cuenta del usuario
        function my_account_menu_order($items) {
            // Renombrar el elemento "dashboard" a "Ajustes"
            $items['dashboard'] = __('Ajustes', 'woocommerce');

            // Obtener el elemento "Mis Trámites"
            $mis_tramites = $items['tramites'];

            // Eliminar el elemento "Mis Trámites" del array
            unset($items['tramites']);

            // Insertar el elemento "Mis Trámites" al inicio del array
            $nuevo_items = array('tramites' => $mis_tramites) + $items;

            return $nuevo_items;
        }

        add_filter('woocommerce_account_menu_items', 'my_account_menu_order');

        // Register custom post type "procedures"
        function register_custom_post_type_procedures() {
            register_post_type('procedures', array(
                'label' => 'Trámites',
                'public' => true,
                'rewrite' => array('slug' => 'procedure'),
                'show_in_menu' => true,
                'menu_position'       => 20,
                'menu_icon' => 'dashicons-clipboard', // Menu icon
                'supports' => array('title', 'revisions'),
                'labels' => array(
                    'singular_name' => 'Trámite',
                    'add_new' => 'Agregar Nuevo',
                    'add_new_item' => 'Agregar Nuevo Trámite',
                    'edit_item' => 'Editar Trámite',
                    'view_item' => 'Ver Trámite'
                ),
                'capability_type'     => 'post'
            ));
        }

        add_action('init', 'register_custom_post_type_procedures');

        // Permite a todos los usuarios ver las entradas existentes
        function customize_capabilities_for_view($caps, $cap, $user_id, $args) {
            if ('post' === $cap && 'procedures' === $args[0]) {
                $caps = array('read_post');
            }
            return $caps;
        }

        add_filter('map_meta_cap', 'customize_capabilities_for_view', 10, 4);

        // Asigna la capacidad de crear nuevas entradas
        function add_custom_capabilities_for_non_admins() {
            $roles = array('subscriber', 'author', 'editor'); // Agrega aquí los roles que desees
            foreach ($roles as $role_name) {
                $role = get_role($role_name);
                $role->add_cap('publish_posts');
                $role->add_cap('read');
            }
        }

        add_action('init', 'add_custom_capabilities_for_non_admins');


        // Redirrecionar para ocultar la vista Single Procedures
        function verify_roles_show_single_procedures() {
            // Verificar si estamos en la vista única de "procedures"
            if (is_singular('procedures')) {
                wp_redirect(home_url());
                exit;
            }
        }

        add_action('template_redirect', 'verify_roles_show_single_procedures');



        // Cargar custom fields
        if ( file_exists( dirname( __FILE__ ) . '/inc/custom-fields.php' ) ) {
            require_once dirname( __FILE__ ) . '/inc/custom-fields.php';
        }


        // Agregar columna personalizada para mostrar el estado del procedimiento
        function pho_add_procedure_status_column($columns) {
            $columns['procedure_name'] = __('Nombre Completo', 'pho-procedures');
            $columns['procedure_status'] = __('Estado del Trámite', 'pho-procedures');
            return $columns;
        }
        add_filter('manage_procedures_posts_columns', 'pho_add_procedure_status_column');

        // Mostrar el estado del procedimiento en la columna personalizada
        function pho_display_procedure_status_column($column, $post_id) {

            if ($column == 'procedure_name') {
                // Obtener el Nombre completo del procedimiento
                $names = get_post_meta($post_id, 'Names', true);
                echo $names; // Mostrar el Nombre completo del procedimiento
            }

            if ($column == 'procedure_status') {
                // Obtener el estado del procedimiento
                $status = get_post_meta($post_id, 'Status', true);
                echo $status; // Mostrar el estado del procedimiento
            }

        }
        add_action('manage_procedures_posts_custom_column', 'pho_display_procedure_status_column', 10, 2);




        // Agregar formulario de creación de Trámites en la página de la cuenta del usuario si no hay Trámites
        function add_new_tramites() {
            $current_user = wp_get_current_user();
            $procedure = get_posts(array(
                'post_type' => 'procedures',
                'author' => $current_user->ID,
                'posts_per_page' => 1, // Obtener solo 1 Trámite
            ));
            // Mostrar el formulario solo si el usuario no tiene Trámite
            if (empty($procedure)) {

                 if ( file_exists( dirname( __FILE__ ) . '/template-parts/extra/content-thulio.php' ) ) {
                    require_once dirname( __FILE__ ) . '/template-parts/extra/content-thulio.php';
                }
            }
        }

        add_action('woocommerce_account_tramites_endpoint', 'add_new_tramites');


        // Procesar la creación de un nuevo Trámite
        function process_new_tramite() {
            if (isset($_POST['new_procedure'])) {
                // Obtener datos del formulario
                $user_email = sanitize_text_field($_POST['user_email']);

                $user_name = mb_strtoupper(sanitize_text_field($_POST['user_name']), 'UTF-8');

                $telefono = sanitize_text_field($_POST['telefono']);

                $social = sanitize_text_field($_POST['social']);

                $perfil = sanitize_text_field($_POST['perfil']);


                // Estado por defecto
                $default_status = 'Solicitud incompleta';

                // Crear nuevo Trámite con estado por defecto
                $new_procedure = array(
                    'post_title'   => $user_email,
                    'post_status'  => 'publish',
                    'post_type'    => 'procedures', // Asegúrate de que coincida con el nombre de tu Custom Post Type
                );
                $procedure_id = wp_insert_post($new_procedure);

                // Asignar el estado por defecto al nuevo trámite
                if ($procedure_id) {

                    update_post_meta($procedure_id, 'Names', $user_name);
                    update_post_meta($procedure_id, 'Status', $default_status);
                    update_post_meta($procedure_id, 'Phone', $telefono);

                    update_post_meta($procedure_id, 'Social', $social);
                    update_post_meta($procedure_id, 'Perfil', $perfil);

                    echo '<div class="alert alert-success"><span>A continuación carga tus documentos</span></div>';
                    // Realizar una redirección después de mostrar el mensaje
                    $procedure_url = wc_get_account_endpoint_url('tramites');
                    echo '<script>
                        setTimeout(function(){
                            window.location.href="' . esc_url($procedure_url) . '";
                        }, 2000); // 2000 milisegundos = 2 segundos
                    </script>';
                    exit; // Importante para evitar ejecución adicional del código
                } else {
                    echo '<div class="alert alert-error"><span>¡Error al iniciar el trámite!</span></div>';
                }
            }
        }

        add_action('woocommerce_account_tramites_endpoint', 'process_new_tramite');


        // Recuperando el contenido de la nueva variable
        function tramites_endpoint_content() {

            // Ruta completa al archivo my-account-procedures.php
            $template_path = dirname( __FILE__ ) . '/my-account-procedures.php';

            // Verifica si el archivo existe y lo incluye
            if (file_exists($template_path)) {
                include $template_path;
            } else {
                echo 'No hay trámites para mostrar. <br />';
            }

        }// END FUNCTION

        add_action( 'woocommerce_account_tramites_endpoint', 'tramites_endpoint_content' );


        // Estados con sus iconos

        if ( ! function_exists( 'show_status_procedure' ) ) :

        function show_status_procedure($Status) {

            if ($Status == 'Solicitud de permiso ante Cofepris') {

                echo '<i class="fas fa-file-import red"></i>';

            }elseif ($Status == 'En espera de Respuesta de Cofepris') {

                echo '<i class="fas fa-spinner fa-spin orange"></i>';

            }elseif ($Status == 'Presentación de demanda de amparo') {

                echo '<i class="fas fa-file yellow"></i>';

           }elseif ($Status == 'En espera de aprobación del Club') {

                echo '<i class="fas fa-hourglass blue-dark"></i>';

           }elseif ($Status == 'En espera de sentencia') {

                echo '<i class="fas fa-user-clock blue"></i>';

           }elseif ($Status == 'Resultado Final') {

                echo '<i class="fas fa-check-circle green"></i>';

           }elseif ($Status == 'Solicitud incompleta') {

                echo '<i class="fas fa-user-times red"></i>';

           }elseif ($Status == 'En proceso de afiliación a nuestra AC') {

                echo '<i class="fas fa-joint orange"></i>';

           }elseif ($Status == 'Ya eres asociado de Club de Thulio A.C. en Libro de Asociados') {

                echo '<i class="fas fa-cannabis green"></i>';


            } else {

                echo '<i class="fas fa-stop-circle inactive"></i>';
            }

        }

        endif;

        // Obtener dinámicamente la dirección de correo electrónico del administrador
        function get_admin_email() {
            return get_option('admin_email');
        }

        // Cambiar la dirección del remitente de los correos electrónicos usando la dirección del administrador
        function change_wp_mail_from($email) {
            return get_admin_email();
        }
        add_filter('wp_mail_from', 'change_wp_mail_from');


        // Obtener dinámicamente el nombre del sitio
        function get_site_name() {
            return get_option('blogname');
        }

        // Cambiar el nombre del remitente de los correos electrónicos usando el nombre del sitio
        function change_wp_mail_from_name($name) {
            return get_site_name();
        }
        add_filter('wp_mail_from_name', 'change_wp_mail_from_name');



        //Notificación de la finalización del tramite
        function pho_send_email_notification() {
            if (isset($_POST['user_email'])) {
                $email = sanitize_email($_POST['user_email']);
                $email_state = sanitize_text_field($_POST['procedure_status']);

                if($email_state == 'Ya eres asociado de Club de Thulio A.C. en Libro de Asociados'){
                    // Verificar si el usuario tiene el rol 'club_member'
                    $user = get_user_by( 'email', $email );
                    if ( ! in_array( 'club_member', (array) $user->roles ) ) {
                        // Si el usuario no tiene el rol 'club_member', asignarlo
                       $user->add_role( 'club_member' );
                    }
                }

                $email_observations = $_POST['procedure_observations'];
                $subject = 'Estado del Trámite : ' . $email_state;
                $woocommerce_template_path = dirname(__FILE__) . '/notification-procedures.php';

                // Obtener la dirección de remitente de WooCommerce
                $woocommerce_from_address = WC()->mailer()->get_from_address();

                if (file_exists($woocommerce_template_path)) {
                    ob_start();
                    // Incluye el template y captura su contenido en una variable
                    include($woocommerce_template_path);
                    $message = ob_get_clean();

                    $headers = array('Content-Type: text/html; charset=UTF-8');

                    // Enviar el correo electrónico utilizando la dirección de remitente de WooCommerce
                    $sent = wp_mail($email, $subject, $message, $headers, '-f' . $woocommerce_from_address);

                    if ($sent) {
                        wp_send_json_success('Notificación enviada con éxito.');
                    } else {
                        wp_send_json_error('Error al enviar la notificación por correo electrónico.');
                    }
                } else {
                    wp_send_json_error('Plantilla no encontrada.');
                }
            } else {
                wp_send_json_error('Falta el correo electrónico.');
            }
        }

        add_action('wp_ajax_pho_send_email_notification', 'pho_send_email_notification');
        add_action('wp_ajax_nopriv_pho_send_email_notification', 'pho_send_email_notification');




        // Agregar el botón de notificación por correo electrónico
        function pho_add_email_notification_button() {
            global $post;

            if ($post && $post->post_type === 'procedures') {
                $user_email = get_the_title($post->ID); // Obtén el correo electrónico del campo 'title'
                $procedure_status = get_post_meta($post->ID, 'Status', true); // Obtén el estado del trámite
                $procedure_observations = wpautop( get_post_meta(get_the_ID(), 'Observations', true) ); // Obtén las observaciones del trámite

                // Script para manejar la lógica de envío de correo electrónico mediante AJAX
                ?>
                <script type="text/javascript">
                    jQuery(document).ready(function($) {
                        $('#pho-send-notification').click(function(e) {
                            e.preventDefault();

                            // Realiza la solicitud AJAX para enviar la notificación
                            $.ajax({
                                type: 'POST',
                                url: ajaxurl, // URL de AJAX de WordPress
                                data: {
                                    'action': 'pho_send_email_notification', // Nombre de la acción AJAX
                                    'user_email': '<?php echo $user_email; ?>', // Correo electrónico del usuario
                                    'procedure_status': '<?php echo $procedure_status; ?>', // Estado del trámite
                                    'procedure_observations': `<?php echo $procedure_observations; ?>` // Observaciones del trámite
                                },
                                success: function(response) {
                                    // Muestra la respuesta de la solicitud AJAX (puede ser un mensaje de éxito o error)
                                    alert(response.data);
                                },
                                error: function(error) {
                                    // Maneja los errores si hay problemas con la solicitud AJAX
                                    console.log(error);
                                }
                            });
                        });

                        $('.pho-send-notification #pho-send-notification').addClass('button button-secondary').css("padding", "0 8px");
                    });
                </script>
                <?php
            }
        }

        add_action('admin_footer', 'pho_add_email_notification_button');

    } //END
}// END FUNCTION
add_action('plugins_loaded', 'if_active_woocommerce');

  





























