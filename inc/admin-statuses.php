<?php

define('PHO_PROTECTED_STATUSES', ['incomplete', 'pending', 'member']);

add_action('admin_menu', 'pho_add_status_menu');
function pho_add_status_menu() {
    add_menu_page(
        __('Estado del Trámite', 'pho-procedures'),
        __('Estado del Trámite', 'pho-procedures'),
        'manage_options',
        'pho_procedure_statuses',
        'pho_render_statuses_page',
        'dashicons-feedback',
        21
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

        $existing = get_option('pho_custom_statuses', []);
        $new_statuses = [];

        foreach ($_POST['custom_statuses'] as $slug => $data) {
            $slug = sanitize_key($slug);
            $label = sanitize_text_field($data['label']);
            $icon = wp_kses_post($data['icon']);

            if (in_array($slug, PHO_PROTECTED_STATUSES)) {
                $existing[$slug]['label'] = $label;
                $existing[$slug]['icon'] = $icon;
            } else {
                $new_statuses[$slug] = ['label' => $label, 'icon' => $icon];
            }
        }

        update_option('pho_custom_statuses', array_merge($existing, $new_statuses));
        echo '<div class="updated"><p>Estados actualizados.</p></div>';
    }

    $statuses = pho_get_all_statuses();
    ?>
    <div class="wrap">
        <h1>Estados de los Trámite</h1>
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
                                       value="<?php echo esc_attr($data['label']); ?>">
                            </td>
                            <td>
                                <input type="text" name="custom_statuses[<?php echo esc_attr($slug); ?>][icon]"
                                       value="<?php echo esc_attr($data['icon']); ?>">
                            </td>
                            <td>
                                <?php if (!in_array($slug, PHO_PROTECTED_STATUSES)): ?>
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
                    <td><input type="text" id="new_status_label" placeholder="Etiqueta"></td>
                    <td><input type="text" id="new_status_icon" placeholder='Icono HTML ej. <i class="fas fa-star"></i>'></td>
                    <td><input type="text" id="new_status_slug" placeholder="Slug" disabled></td>
                    <td><button type="button" class="button" id="add_status_btn">Agregar</button></td>
                </tr>
            </table>

            <p><input type="submit" class="button button-primary" name="pho_save_statuses" value="Guardar cambios"></p>
        </form>
    </div>

    <script>
        const reserved = ['incomplete', 'pending', 'member'];

        function generateSlug(label) {
            return label
                .toLowerCase()
                .normalize("NFD").replace(/[\u0300-\u036f]/g, '')
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/^-+|-+$/g, '');
        }

        document.getElementById('new_status_label').addEventListener('input', function () {
            const slug = generateSlug(this.value);
            document.getElementById('new_status_slug').value = slug;
        });

        document.getElementById('add_status_btn').addEventListener('click', function () {
            const label = document.getElementById('new_status_label').value.trim();
            const icon = document.getElementById('new_status_icon').value.trim();
            const slug = document.getElementById('new_status_slug').value.trim();

            if (!label || !slug) {
                alert('Etiqueta es obligatoria');
                return;
            }

            if (reserved.includes(slug)) {
                alert(`El slug "${slug}" está reservado y no puede ser usado.`);
                return;
            }

            if (document.querySelector(`input[name="custom_statuses[${slug}][label]"]`)) {
                alert('Ya existe un estado con ese slug.');
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

            document.getElementById('new_status_label').value = '';
            document.getElementById('new_status_icon').value = '';
            document.getElementById('new_status_slug').value = '';
        });

        document.addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-status')) {
                e.target.closest('tr').remove();
            }
        });
    </script>
    <?php
}
