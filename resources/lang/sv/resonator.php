<?php

declare(strict_types=1);

return [
    // Navigation
    'navigation' => [
        'group' => 'Resonator',
        'inbox' => 'Inkorg',
        'folders' => 'Mappar',
        'snippets' => 'Snippets',
        'spam_filters' => 'Spamfilter',
    ],

    // Folders
    'folders' => [
        'inbox' => 'Inkorg',
        'sent' => 'Skickat',
        'archive' => 'Arkiv',
        'spam' => 'Spam',
        'trash' => 'Papperskorg',
    ],

    // Labels
    'labels' => [
        'from' => 'Från',
        'to' => 'Till',
        'cc' => 'Kopia',
        'bcc' => 'Dold kopia',
        'subject' => 'Ämne',
        'body' => 'Innehåll',
        'message' => 'Meddelande',
        'attachments' => 'Bilagor',
        'snippet' => 'Snippet',
        'folder' => 'Mapp',
        'owner' => 'Ägare',
        'date' => 'Datum',
        'name' => 'Namn',
        'email' => 'E-post',
        'phone' => 'Telefon',
        'company' => 'Företag',
        'reason' => 'Anledning',
        'shortcut' => 'Genväg',
        'icon' => 'Ikon',
        'color' => 'Färg',
        'sort_order' => 'Sorteringsordning',
        'active' => 'Aktiv',
        'system' => 'System',
        'custom' => 'Anpassad',
        'slug' => 'Slug',
    ],

    // Actions
    'actions' => [
        'sync' => 'Synka e-post',
        'syncing' => 'Synkar...',
        'compose' => 'Skriv meddelande',
        'reply' => 'Svara',
        'send' => 'Skicka',
        'archive' => 'Arkivera',
        'move_to_trash' => 'Flytta till papperskorg',
        'move_to_spam' => 'Markera som spam',
        'move_to_folder' => 'Flytta till mapp',
        'go_to_folder' => 'Gå till...',
        'mark_read' => 'Markera som läst',
        'mark_unread' => 'Markera som oläst',
        'toggle_star' => 'Stjärnmarkera',
        'change_owner' => 'Ändra ägare',
        'create' => 'Skapa',
        'edit' => 'Redigera',
        'delete' => 'Ta bort',
        'save' => 'Spara',
        'cancel' => 'Avbryt',
    ],

    // Filters
    'filters' => [
        'folder' => 'Mapp',
        'read_status' => 'Lässtatus',
        'unread_only' => 'Endast olästa',
        'read_only' => 'Endast lästa',
        'starred_only' => 'Endast stjärnmärkta',
    ],

    // Messages/Notifications
    'messages' => [
        'sync_success' => ':synced nya e-post synkade. :skipped redan synkade. :spam flyttade till spam.',
        'sync_error' => 'Fel vid synkning: :error',
        'email_sent' => 'E-post skickad',
        'email_send_error' => 'Kunde inte skicka e-post: :error',
        'moved_to_folder' => 'Flyttad till :folder',
        'archived' => 'Arkiverad',
        'moved_to_trash' => 'Flyttad till papperskorg',
        'moved_to_spam' => 'Markerad som spam',
        'marked_read' => 'Markerad som läst',
        'marked_unread' => 'Markerad som oläst',
        'owner_changed' => 'Ägare ändrad',
        'added_to_spam_list' => 'Tillagd i spamlistan',
        'removed_from_spam_list' => 'Borttagen från spamlistan',
    ],

    // Confirmations
    'confirmations' => [
        'delete_title' => 'Ta bort?',
        'delete_description' => 'Är du säker på att du vill ta bort detta?',
        'spam_title' => 'Markera som spam?',
        'spam_description' => 'Detta flyttar meddelandet till spam och lägger till avsändaren i spamfiltret.',
        'trash_title' => 'Flytta till papperskorg?',
        'trash_description' => 'Är du säker på att du vill flytta detta till papperskorgen?',
    ],

    // Placeholders
    'placeholders' => [
        'select_recipients' => 'Välj mottagare...',
        'enter_subject' => 'Ange ämne...',
        'enter_message' => 'Skriv ditt meddelande...',
        'select_snippet' => 'Välj en snippet...',
        'select_folder' => 'Välj mapp...',
        'enter_email' => 'Ange e-postadress...',
        'enter_reason' => 'Ange anledning till blockering...',
        'enter_name' => 'Ange namn...',
        'enter_shortcut' => 'Ange genväg...',
    ],

    // Helpers
    'helpers' => [
        'shortcut' => 'Valfri genväg för att snabbt infoga denna snippet',
        'slug' => 'Unik identifierare för mappen',
    ],

    // Thread View
    'thread' => [
        'messages_count' => ':count meddelande|:count meddelanden',
        'inbound' => 'Mottaget',
        'outbound' => 'Skickat',
        'attachments' => 'Bilagor',
        'download' => 'Ladda ner',
    ],

    // Spam Detection (AI prompt)
    'spam_detection' => [
        'prompt' => <<<'PROMPT'
Du är en spam-detektor för en inkorg. Klassificera e-post som spam om det är:
- Nyhetsbrev eller veckobrev
- Marknadsföring eller reklam
- Automatiska utskick från företag
- Massutskick som inte är personligt riktade
- Erbjudanden eller kampanjer
- Systemmeddelanden från tjänster (bekräftelser, notiser)

INTE spam om det är:
- En personlig förfrågan
- En fråga från en privatperson eller organisation
- Svar på tidigare konversation
- Förfrågan om pris eller tillgänglighet
- Direkt kommunikation
PROMPT,
    ],

    // Contact Enrichment (AI prompt)
    'contact_enrichment' => [
        'prompt' => 'Extrahera kontaktinformation från följande e-postinnehåll. Leta efter avsändarens namn, telefonnummer och företagsnamn.',
    ],

    // Signature
    'signature' => [
        'regards' => 'Med vänliga hälsningar',
    ],

    // Empty States
    'empty' => [
        'inbox' => 'Inga meddelanden',
        'inbox_description' => 'Din inkorg är tom.',
        'folder' => 'Inga meddelanden i denna mapp',
    ],

    // Misc
    'misc' => [
        'unread' => 'olästa',
        'starred' => 'Stjärnmärkt',
        'handled_by' => 'Hanteras av',
        'select_all' => 'Markera alla',
    ],
];
