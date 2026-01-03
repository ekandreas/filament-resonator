<?php

declare(strict_types=1);

return [
    // Navigation
    'navigation' => [
        'group' => 'Resonator',
        'inbox' => 'Posteingang',
        'folders' => 'Ordner',
        'snippets' => 'Textbausteine',
        'spam_filters' => 'Spamfilter',
    ],

    // Folders
    'folders' => [
        'inbox' => 'Posteingang',
        'sent' => 'Gesendet',
        'archive' => 'Archiv',
        'spam' => 'Spam',
        'trash' => 'Papierkorb',
    ],

    // Labels
    'labels' => [
        'from' => 'Von',
        'to' => 'An',
        'cc' => 'CC',
        'bcc' => 'BCC',
        'subject' => 'Betreff',
        'body' => 'Inhalt',
        'message' => 'Nachricht',
        'attachments' => 'Anhänge',
        'snippet' => 'Textbaustein',
        'folder' => 'Ordner',
        'owner' => 'Besitzer',
        'date' => 'Datum',
        'name' => 'Name',
        'email' => 'E-Mail',
        'phone' => 'Telefon',
        'company' => 'Firma',
        'reason' => 'Grund',
        'shortcut' => 'Kurzbefehl',
        'icon' => 'Symbol',
        'color' => 'Farbe',
        'sort_order' => 'Sortierung',
        'active' => 'Aktiv',
        'system' => 'System',
        'custom' => 'Benutzerdefiniert',
        'slug' => 'Slug',
    ],

    // Actions
    'actions' => [
        'sync' => 'E-Mails synchronisieren',
        'syncing' => 'Synchronisiere...',
        'compose' => 'Verfassen',
        'reply' => 'Antworten',
        'send' => 'Senden',
        'archive' => 'Archivieren',
        'move_to_trash' => 'In Papierkorb verschieben',
        'move_to_spam' => 'Als Spam markieren',
        'move_to_folder' => 'In Ordner verschieben',
        'go_to_folder' => 'Gehe zu...',
        'mark_read' => 'Als gelesen markieren',
        'mark_unread' => 'Als ungelesen markieren',
        'toggle_star' => 'Stern umschalten',
        'change_owner' => 'Besitzer ändern',
        'create' => 'Erstellen',
        'edit' => 'Bearbeiten',
        'delete' => 'Löschen',
        'save' => 'Speichern',
        'cancel' => 'Abbrechen',
    ],

    // Filters
    'filters' => [
        'folder' => 'Ordner',
        'read_status' => 'Lesestatus',
        'unread_only' => 'Nur ungelesene',
        'read_only' => 'Nur gelesene',
        'starred_only' => 'Nur markierte',
    ],

    // Messages/Notifications
    'messages' => [
        'sync_success' => ':synced neue E-Mails synchronisiert. :skipped bereits synchronisiert. :spam als Spam markiert.',
        'sync_error' => 'Fehler beim Synchronisieren: :error',
        'email_sent' => 'E-Mail erfolgreich gesendet',
        'email_send_error' => 'E-Mail konnte nicht gesendet werden: :error',
        'moved_to_folder' => 'Verschoben nach :folder',
        'archived' => 'Erfolgreich archiviert',
        'moved_to_trash' => 'In Papierkorb verschoben',
        'moved_to_spam' => 'Als Spam markiert',
        'marked_read' => 'Als gelesen markiert',
        'marked_unread' => 'Als ungelesen markiert',
        'owner_changed' => 'Besitzer geändert',
        'added_to_spam_list' => 'Zur Spamliste hinzugefügt',
        'removed_from_spam_list' => 'Von Spamliste entfernt',
    ],

    // Confirmations
    'confirmations' => [
        'delete_title' => 'Löschen?',
        'delete_description' => 'Sind Sie sicher, dass Sie dies löschen möchten?',
        'spam_title' => 'Als Spam markieren?',
        'spam_description' => 'Die Nachricht wird in den Spam-Ordner verschoben und der Absender zur Spamliste hinzugefügt.',
        'trash_title' => 'In Papierkorb verschieben?',
        'trash_description' => 'Sind Sie sicher, dass Sie dies in den Papierkorb verschieben möchten?',
    ],

    // Placeholders
    'placeholders' => [
        'select_recipients' => 'Empfänger auswählen...',
        'enter_subject' => 'Betreff eingeben...',
        'enter_message' => 'Nachricht eingeben...',
        'select_snippet' => 'Textbaustein auswählen...',
        'select_folder' => 'Ordner auswählen...',
        'enter_email' => 'E-Mail-Adresse eingeben...',
        'enter_reason' => 'Grund für Sperrung eingeben...',
        'enter_name' => 'Name eingeben...',
        'enter_shortcut' => 'Kurzbefehl eingeben...',
    ],

    // Helpers
    'helpers' => [
        'shortcut' => 'Optionaler Kurzbefehl zum schnellen Einfügen dieses Textbausteins',
        'slug' => 'Eindeutige Kennung für den Ordner',
    ],

    // Thread View
    'thread' => [
        'messages_count' => ':count Nachricht|:count Nachrichten',
        'inbound' => 'Empfangen',
        'outbound' => 'Gesendet',
        'attachments' => 'Anhänge',
        'download' => 'Herunterladen',
    ],

    // Spam Detection (AI prompt)
    'spam_detection' => [
        'prompt' => <<<'PROMPT'
Du bist ein Spam-Detektor für einen E-Mail-Posteingang. Klassifiziere eine E-Mail als Spam, wenn sie:
- Newsletter oder wöchentliche Zusammenfassungen
- Marketing oder Werbung
- Automatische Nachrichten von Unternehmen
- Massenmails, die nicht persönlich adressiert sind
- Angebote oder Kampagnen
- Systembenachrichtigungen von Diensten (Bestätigungen, Benachrichtigungen)

KEIN Spam, wenn es:
- Eine persönliche Anfrage
- Eine Frage von einer Privatperson oder Organisation
- Eine Antwort auf eine frühere Unterhaltung
- Eine Anfrage zu Preis oder Verfügbarkeit
- Direkte Kommunikation
PROMPT,
    ],

    // Contact Enrichment (AI prompt)
    'contact_enrichment' => [
        'prompt' => 'Extrahiere Kontaktinformationen aus dem folgenden E-Mail-Inhalt. Suche nach dem Namen, der Telefonnummer und dem Firmennamen des Absenders.',
    ],

    // Signature
    'signature' => [
        'regards' => 'Mit freundlichen Grüßen',
    ],

    // Empty States
    'empty' => [
        'inbox' => 'Keine Nachrichten',
        'inbox_description' => 'Ihr Posteingang ist leer.',
        'folder' => 'Keine Nachrichten in diesem Ordner',
    ],

    // Misc
    'misc' => [
        'unread' => 'ungelesen',
        'starred' => 'Markiert',
        'handled_by' => 'Bearbeitet von',
        'select_all' => 'Alle auswählen',
    ],
];
