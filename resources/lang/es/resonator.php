<?php

declare(strict_types=1);

return [
    // Navigation
    'navigation' => [
        'group' => 'Resonator',
        'inbox' => 'Bandeja de entrada',
        'folders' => 'Carpetas',
        'snippets' => 'Plantillas',
        'spam_filters' => 'Filtros de spam',
    ],

    // Folders
    'folders' => [
        'inbox' => 'Bandeja de entrada',
        'sent' => 'Enviados',
        'archive' => 'Archivo',
        'spam' => 'Spam',
        'trash' => 'Papelera',
    ],

    // Labels
    'labels' => [
        'from' => 'De',
        'to' => 'Para',
        'cc' => 'CC',
        'bcc' => 'CCO',
        'subject' => 'Asunto',
        'body' => 'Cuerpo',
        'message' => 'Mensaje',
        'attachments' => 'Adjuntos',
        'snippet' => 'Plantilla',
        'folder' => 'Carpeta',
        'owner' => 'Propietario',
        'date' => 'Fecha',
        'name' => 'Nombre',
        'email' => 'Correo electrónico',
        'phone' => 'Teléfono',
        'company' => 'Empresa',
        'reason' => 'Razón',
        'shortcut' => 'Atajo',
        'icon' => 'Icono',
        'color' => 'Color',
        'sort_order' => 'Orden',
        'active' => 'Activo',
        'system' => 'Sistema',
        'custom' => 'Personalizado',
        'slug' => 'Slug',
    ],

    // Actions
    'actions' => [
        'sync' => 'Sincronizar correos',
        'syncing' => 'Sincronizando...',
        'compose' => 'Redactar',
        'reply' => 'Responder',
        'send' => 'Enviar',
        'archive' => 'Archivar',
        'move_to_trash' => 'Mover a papelera',
        'move_to_spam' => 'Marcar como spam',
        'move_to_folder' => 'Mover a carpeta',
        'go_to_folder' => 'Ir a...',
        'mark_read' => 'Marcar como leído',
        'mark_unread' => 'Marcar como no leído',
        'toggle_star' => 'Alternar estrella',
        'change_owner' => 'Cambiar propietario',
        'create' => 'Crear',
        'edit' => 'Editar',
        'delete' => 'Eliminar',
        'save' => 'Guardar',
        'cancel' => 'Cancelar',
    ],

    // Filters
    'filters' => [
        'folder' => 'Carpeta',
        'read_status' => 'Estado de lectura',
        'unread_only' => 'Solo no leídos',
        'read_only' => 'Solo leídos',
        'starred_only' => 'Solo destacados',
    ],

    // Messages/Notifications
    'messages' => [
        'sync_success' => ':synced correos nuevos sincronizados. :skipped ya sincronizados. :spam movidos a spam.',
        'sync_error' => 'Error al sincronizar: :error',
        'email_sent' => 'Correo enviado correctamente',
        'email_send_error' => 'Error al enviar correo: :error',
        'moved_to_folder' => 'Movido a :folder',
        'archived' => 'Archivado correctamente',
        'moved_to_trash' => 'Movido a papelera',
        'moved_to_spam' => 'Marcado como spam',
        'marked_read' => 'Marcado como leído',
        'marked_unread' => 'Marcado como no leído',
        'owner_changed' => 'Propietario cambiado',
        'added_to_spam_list' => 'Añadido a la lista de spam',
        'removed_from_spam_list' => 'Eliminado de la lista de spam',
    ],

    // Confirmations
    'confirmations' => [
        'delete_title' => '¿Eliminar?',
        'delete_description' => '¿Está seguro de que desea eliminar esto?',
        'spam_title' => '¿Marcar como spam?',
        'spam_description' => 'El mensaje se moverá a spam y el remitente se añadirá a la lista de filtros de spam.',
        'trash_title' => '¿Mover a papelera?',
        'trash_description' => '¿Está seguro de que desea mover esto a la papelera?',
    ],

    // Placeholders
    'placeholders' => [
        'select_recipients' => 'Seleccionar destinatarios...',
        'enter_subject' => 'Introducir asunto...',
        'enter_message' => 'Introducir mensaje...',
        'select_snippet' => 'Seleccionar plantilla...',
        'select_folder' => 'Seleccionar carpeta...',
        'enter_email' => 'Introducir correo electrónico...',
        'enter_reason' => 'Introducir razón del bloqueo...',
        'enter_name' => 'Introducir nombre...',
        'enter_shortcut' => 'Introducir atajo...',
    ],

    // Helpers
    'helpers' => [
        'shortcut' => 'Atajo opcional para insertar rápidamente esta plantilla',
        'slug' => 'Identificador único para la carpeta',
    ],

    // Thread View
    'thread' => [
        'messages_count' => ':count mensaje|:count mensajes',
        'inbound' => 'Recibido',
        'outbound' => 'Enviado',
        'attachments' => 'Adjuntos',
        'download' => 'Descargar',
    ],

    // Spam Detection (AI prompt)
    'spam_detection' => [
        'prompt' => <<<'PROMPT'
Eres un detector de spam para una bandeja de entrada de correo electrónico. Clasifica un correo como spam si es:
- Boletines o resúmenes semanales
- Marketing o publicidad
- Mensajes automatizados de empresas
- Envíos masivos no dirigidos personalmente
- Ofertas o campañas
- Notificaciones del sistema de servicios (confirmaciones, notificaciones)

NO es spam si es:
- Una consulta personal
- Una pregunta de una persona privada u organización
- Una respuesta a una conversación anterior
- Una consulta sobre precio o disponibilidad
- Comunicación directa
PROMPT,
    ],

    // Contact Enrichment (AI prompt)
    'contact_enrichment' => [
        'prompt' => 'Extrae la información de contacto del siguiente contenido del correo electrónico. Busca el nombre del remitente, número de teléfono y nombre de la empresa.',
    ],

    // Signature
    'signature' => [
        'regards' => 'Atentamente',
    ],

    // Empty States
    'empty' => [
        'inbox' => 'Sin mensajes',
        'inbox_description' => 'Su bandeja de entrada está vacía.',
        'folder' => 'No hay mensajes en esta carpeta',
    ],

    // Misc
    'misc' => [
        'unread' => 'no leído',
        'starred' => 'Destacado',
        'handled_by' => 'Gestionado por',
        'select_all' => 'Seleccionar todo',
    ],
];
