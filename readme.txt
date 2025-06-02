# PHO Procedures

PHO Procedures es un plugin para WordPress que permite a los usuarios gestionar solicitudes personalizadas tipo “trámite” desde su cuenta WooCommerce.

## 🧩 Funcionalidad
- Registro de trámites como custom post type (`procedures`)
- Endpoint personalizado en `/mi-cuenta/tramites/`
- Formulario de afiliación con carga de archivos
- Metacampos personalizados vía CMB2
- Gestión de estado del trámite
- Notificación vía email con estado y observaciones

---

## 📁 Estructura del Plugin

```bash
pho-procedures/
├── pho-procedures.php           # Archivo principal del plugin
├── inc/                         # Módulos funcionales
│   ├── activator.php            # Activación, rewrite flush y roles
│   ├── assets.php               # Carga de CSS
│   ├── capabilities.php         # Roles y capacidades personalizadas
│   ├── cmb2-loader.php          # Carga de librería CMB2
│   ├── cpt-procedures.php       # Registro del CPT 'procedures'
│   ├── email.php                # Notificaciones por correo
│   ├── endpoints.php            # Integración con WooCommerce My Account
│   ├── forms.php                # Creación y gestión de trámites
│   ├── metaboxes.php            # Metacampos con CMB2
│   ├── notifications.php        # Botón y lógica JS en admin
│   ├── redirects.php            # Redirección si acceden a single
│   ├── template-helpers.php    # Helpers visuales (íconos de estado)
│   ├── js/
│   │   └── admin-notification.js
│   └── css/
│       └── pho-procedures-style.css
├── template-parts/
│   ├── content.php              # Plantilla de vista de trámites
│   ├── email-header.php         # Encabezado de correo
│   ├── email-footer.php         # Footer de correo
│   └── extra/
│       └── content-header.php   # Plantilla del formulario inicial
├── my-account-procedures.php    # Controlador de vista de trámites
├── notification-procedures.php  # Plantilla HTML del correo
└── readme.txt
```

---

## ⚙️ Requisitos
- WordPress 5.8+
- WooCommerce activo
- CMB2 embebido en `/cmb2/`

---

## 🚀 Instalación
1. Clona el repositorio o sube el zip del plugin a WordPress.
2. Asegúrate de que WooCommerce esté activo.
3. Activa el plugin desde el panel.
4. Visita `/mi-cuenta/tramites/` para comenzar a usarlo.

---

## 🛡️ Seguridad recomendada
- Agrega `wp_nonce_field()` a formularios para evitar CSRF
- Usa `sanitize_*`, `wp_kses_post()` para manejar datos de usuario

---

## 🛠 Extensibilidad
Puedes extender el plugin agregando:
- Nuevos campos CMB2 en `metaboxes.php`
- Nuevos endpoints WooCommerce en `endpoints.php`
- Acciones personalizadas en `forms.php`

---

## 👨‍💻 Autor
**Phoenix Dev**
[https://phoenixdev.mx](https://phoenixdev.mx)
