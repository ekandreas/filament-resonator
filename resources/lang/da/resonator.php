<?php

declare(strict_types=1);

return [
    // Navigation
    'navigation' => [
        'group' => 'Resonator',
        'inbox' => 'Indbakke',
        'folders' => 'Mapper',
        'snippets' => 'Skabeloner',
        'spam_filters' => 'Spamfiltre',
    ],

    // Folders
    'folders' => [
        'inbox' => 'Indbakke',
        'sent' => 'Sendt',
        'archive' => 'Arkiv',
        'spam' => 'Spam',
        'trash' => 'Papirkurv',
    ],

    // Labels
    'labels' => [
        'from' => 'Fra',
        'to' => 'Til',
        'cc' => 'Kopi',
        'bcc' => 'Blindkopi',
        'subject' => 'Emne',
        'body' => 'Indhold',
        'message' => 'Besked',
        'attachments' => 'Vedhæftninger',
        'snippet' => 'Skabelon',
        'folder' => 'Mappe',
        'owner' => 'Ejer',
        'date' => 'Dato',
        'name' => 'Navn',
        'email' => 'E-mail',
        'phone' => 'Telefon',
        'company' => 'Virksomhed',
        'reason' => 'Årsag',
        'shortcut' => 'Genvej',
        'icon' => 'Ikon',
        'color' => 'Farve',
        'sort_order' => 'Sortering',
        'active' => 'Aktiv',
        'system' => 'System',
        'custom' => 'Tilpasset',
        'slug' => 'Slug',
    ],

    // Actions
    'actions' => [
        'sync' => 'Synkroniser e-mails',
        'syncing' => 'Synkroniserer...',
        'compose' => 'Skriv',
        'reply' => 'Svar',
        'send' => 'Send',
        'archive' => 'Arkiver',
        'move_to_trash' => 'Flyt til papirkurv',
        'move_to_spam' => 'Marker som spam',
        'move_to_folder' => 'Flyt til mappe',
        'go_to_folder' => 'Gå til...',
        'mark_read' => 'Marker som læst',
        'mark_unread' => 'Marker som ulæst',
        'toggle_star' => 'Slå stjerne til/fra',
        'change_owner' => 'Skift ejer',
        'create' => 'Opret',
        'edit' => 'Rediger',
        'delete' => 'Slet',
        'save' => 'Gem',
        'cancel' => 'Annuller',
    ],

    // Filters
    'filters' => [
        'folder' => 'Mappe',
        'read_status' => 'Læsestatus',
        'unread_only' => 'Kun ulæste',
        'read_only' => 'Kun læste',
        'starred_only' => 'Kun stjernemarkerede',
    ],

    // Messages/Notifications
    'messages' => [
        'sync_success' => ':synced nye e-mails synkroniseret. :skipped allerede synkroniseret. :spam flyttet til spam.',
        'sync_error' => 'Fejl ved synkronisering: :error',
        'email_sent' => 'E-mail sendt',
        'email_send_error' => 'Kunne ikke sende e-mail: :error',
        'moved_to_folder' => 'Flyttet til :folder',
        'archived' => 'Arkiveret',
        'moved_to_trash' => 'Flyttet til papirkurv',
        'moved_to_spam' => 'Markeret som spam',
        'marked_read' => 'Markeret som læst',
        'marked_unread' => 'Markeret som ulæst',
        'owner_changed' => 'Ejer ændret',
        'added_to_spam_list' => 'Tilføjet til spamliste',
        'removed_from_spam_list' => 'Fjernet fra spamliste',
    ],

    // Confirmations
    'confirmations' => [
        'delete_title' => 'Slet?',
        'delete_description' => 'Er du sikker på, at du vil slette dette?',
        'spam_title' => 'Marker som spam?',
        'spam_description' => 'Beskeden flyttes til spam, og afsenderen tilføjes til spamfilterlisten.',
        'trash_title' => 'Flyt til papirkurv?',
        'trash_description' => 'Er du sikker på, at du vil flytte dette til papirkurven?',
    ],

    // Placeholders
    'placeholders' => [
        'select_recipients' => 'Vælg modtagere...',
        'enter_subject' => 'Indtast emne...',
        'enter_message' => 'Indtast besked...',
        'select_snippet' => 'Vælg skabelon...',
        'select_folder' => 'Vælg mappe...',
        'enter_email' => 'Indtast e-mailadresse...',
        'enter_reason' => 'Indtast årsag til blokering...',
        'enter_name' => 'Indtast navn...',
        'enter_shortcut' => 'Indtast genvej...',
    ],

    // Helpers
    'helpers' => [
        'shortcut' => 'Valgfri genvej til hurtigt at indsætte denne skabelon',
        'slug' => 'Unik identifikator for mappen',
    ],

    // Thread View
    'thread' => [
        'messages_count' => ':count besked|:count beskeder',
        'inbound' => 'Modtaget',
        'outbound' => 'Sendt',
        'attachments' => 'Vedhæftninger',
        'download' => 'Download',
    ],

    // Spam Detection (AI prompt)
    'spam_detection' => [
        'prompt' => <<<'PROMPT'
Du er en spam-detektor for en e-mail-indbakke. Klassificer en e-mail som spam, hvis den er:
- Nyhedsbreve eller ugentlige opsummeringer
- Markedsføring eller reklame
- Automatiske beskeder fra virksomheder
- Masseudsendelser, der ikke er personligt adresseret
- Tilbud eller kampagner
- Systemnotifikationer fra tjenester (bekræftelser, notifikationer)

IKKE spam, hvis det er:
- En personlig henvendelse
- Et spørgsmål fra en privatperson eller organisation
- Et svar på en tidligere samtale
- En forespørgsel om pris eller tilgængelighed
- Direkte kommunikation
PROMPT,
    ],

    // Contact Enrichment (AI prompt)
    'contact_enrichment' => [
        'prompt' => 'Udtræk kontaktoplysninger fra følgende e-mail-indhold. Se efter afsenderens navn, telefonnummer og virksomhedsnavn.',
    ],

    // Signature
    'signature' => [
        'regards' => 'Med venlig hilsen',
    ],

    // Empty States
    'empty' => [
        'inbox' => 'Ingen beskeder',
        'inbox_description' => 'Din indbakke er tom.',
        'folder' => 'Ingen beskeder i denne mappe',
    ],

    // Misc
    'misc' => [
        'unread' => 'ulæst',
        'starred' => 'Stjernemarkeret',
        'handled_by' => 'Håndteret af',
        'select_all' => 'Vælg alle',
    ],
];
