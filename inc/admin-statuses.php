<?php

add_action('admin_menu', 'pho_add_status_menu');
function pho_add_status_menu() {
    add_menu_page(
        __('Estado del Trámite', 'pho-procedures'),
        __('Estado del Trámite', 'pho-procedures'),
        'manage_options',
        'pho_procedure_statuses',
        'pho_render_statuses_page',
        'dashicons-feedback',
        30
    );
}

function pho_get_default_statuses() {
    return [
        'incomplete' => [
            'label' => 'Solicitud incompleta',
            'icon'  => '<i class="fas fa-user-times red"></i>'
        ],
        'pending' => [
            'label' => 'En espera de aprobación',
            'icon'  => '<i class="fas fa-hourglass blue-dark"></i>'
        ],
        'member' => [
            'label' => 'Ya eres asociado',
            'icon'  => '<i class="fas fa-cannabis green"></i>'
        ]
    ];
}

function pho_get_all_statuses() {
    $custom = get_option('pho_custom_statuses', []);
    return array_merge(pho_get_default_statuses(), $custom);
}

function pho_render_statuses_page() {
    if (isset($_POST['pho_save_statuses'])) {
        check_admin_referer('pho_save_statuses');

        $new_statuses = [];
        foreach ($_POST['custom_statuses'] as $slug => $data) {
            $slug = sanitize_key($slug);
            $label = sanitize_text_field($data['label']);
            $icon = wp_kses_post($data['icon']);

            if (!in_array($slug, ['incomplete', 'member'])) {
                $new_statuses[$slug] = ['label' => $label, 'icon' => $icon];
            }
        }
        update_option('pho_custom_statuses', $new_statuses);
        echo '<div class="updated"><p>Estados actualizados.</p></div>';
    }

    $statuses = pho_get_all_statuses();

    ?>
    <div class="wrap">
        <h1>Estado del Trámite</h1>
        <form method="post">
            <?php wp_nonce_field('pho_save_statuses'); ?>

            <table class="widefat fixed">
                <thead>
                    <tr>
                        <th>Slug</th>
                        <th>Etiqueta</th>
                        <th>Icono (HTML)</th>
                        <th>Eliminar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($statuses as $slug => $data): ?>
                        <tr>
                            <td><code><?php echo esc_html($slug); ?></code></td>
                            <td>
                                <input type="text" name="custom_statuses[<?php echo esc_attr($slug); ?>][label]"
                                       value="<?php echo esc_attr($data['label']); ?>"
                                       <?php echo in_array($slug, ['incomplete', 'pending', 'member']) ? 'readonly' : ''; ?>>
                            </td>
                            <td>
                                <input type="text" name="custom_statuses[<?php echo esc_attr($slug); ?>][icon]"
                                       value="<?php echo esc_attr($data['icon']); ?>"
                                       <?php echo in_array($slug, ['incomplete', 'pending', 'member']) ? 'readonly' : ''; ?>>
                            </td>
                            <td>
                                <?php if (!in_array($slug, ['incomplete', 'pending', 'member'])): ?>
                                    <button type="button" class="button remove-status" data-slug="<?php echo esc_attr($slug); ?>">Eliminar</button>
                                <?php else: ?>
                                    <em>No permitido</em>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <h2>Agregar nuevo estado</h2>
            <table>
                <tr>
                    <td><input type="text" id="new_status_slug" placeholder="Slug (sin espacios)"></td>
                    <td><input type="text" id="new_status_label" placeholder="Etiqueta"></td>
                    <td><input type="text" id="new_status_icon" placeholder='Icono HTML ej. <i class="fas fa-star"></i>'></td>
                    <td><button type="button" class="button" id="add_status_btn">Agregar</button></td>
                </tr>
            </table>

            <p><input type="submit" class="button button-primary" name="pho_save_statuses" value="Guardar cambios"></p>
        </form>
    </div>

    <script>
        document.getElementById('add_status_btn').addEventListener('click', function () {
            const slug = document.getElementById('new_status_slug').value.trim();
            const label = document.getElementById('new_status_label').value.trim();
            const icon = document.getElementById('new_status_icon').value.trim();

            if (!slug || !label) {
                alert('Slug y etiqueta son obligatorios');
                return;
            }

            const table = document.querySelector('tbody');
            const row = document.createElement('tr');
            row.innerHTML = `
                <td><code>${slug}</code></td>
                <td><input type="text" name="custom_statuses[${slug}][label]" value="${label}"></td>
                <td><input type="text" name="custom_statuses[${slug}][icon]" value="${icon}"></td>
                <td><button type="button" class="button remove-status" data-slug="${slug}">Eliminar</button></td>
            `;
            table.appendChild(row);
        });

        document.addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-status')) {
                e.target.closest('tr').remove();
            }
        });
    </script>
    <?php
}
