<?php

declare(strict_types=1);

return [
    // Navigation
    'navigation' => [
        'group' => 'Resonator',
        'inbox' => 'Innboks',
        'folders' => 'Mapper',
        'snippets' => 'Maler',
        'spam_filters' => 'Spamfiltre',
    ],

    // Folders
    'folders' => [
        'inbox' => 'Innboks',
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
        'body' => 'Innhold',
        'message' => 'Melding',
        'attachments' => 'Vedlegg',
        'snippet' => 'Mal',
        'folder' => 'Mappe',
        'owner' => 'Eier',
        'date' => 'Dato',
        'name' => 'Navn',
        'email' => 'E-post',
        'phone' => 'Telefon',
        'company' => 'Firma',
        'reason' => 'Grunn',
        'shortcut' => 'Snarvei',
        'icon' => 'Ikon',
        'color' => 'Farge',
        'sort_order' => 'Sortering',
        'active' => 'Aktiv',
        'system' => 'System',
        'custom' => 'Tilpasset',
        'slug' => 'Slug',
    ],

    // Actions
    'actions' => [
        'sync' => 'Synkroniser e-post',
        'syncing' => 'Synkroniserer...',
        'compose' => 'Skriv',
        'reply' => 'Svar',
        'send' => 'Send',
        'archive' => 'Arkiver',
        'move_to_trash' => 'Flytt til papirkurv',
        'move_to_spam' => 'Merk som spam',
        'move_to_folder' => 'Flytt til mappe',
        'go_to_folder' => 'Gå til...',
        'mark_read' => 'Merk som lest',
        'mark_unread' => 'Merk som ulest',
        'toggle_star' => 'Slå av/på stjerne',
        'change_owner' => 'Endre eier',
        'create' => 'Opprett',
        'edit' => 'Rediger',
        'delete' => 'Slett',
        'save' => 'Lagre',
        'cancel' => 'Avbryt',
    ],

    // Filters
    'filters' => [
        'folder' => 'Mappe',
        'read_status' => 'Lesestatus',
        'unread_only' => 'Kun uleste',
        'read_only' => 'Kun leste',
        'starred_only' => 'Kun stjernemerkede',
    ],

    // Messages/Notifications
    'messages' => [
        'sync_success' => ':synced nye e-poster synkronisert. :skipped allerede synkronisert. :spam flyttet til spam.',
        'sync_error' => 'Feil ved synkronisering: :error',
        'email_sent' => 'E-post sendt',
        'email_send_error' => 'Kunne ikke sende e-post: :error',
        'moved_to_folder' => 'Flyttet til :folder',
        'archived' => 'Arkivert',
        'moved_to_trash' => 'Flyttet til papirkurv',
        'moved_to_spam' => 'Merket som spam',
        'marked_read' => 'Merket som lest',
        'marked_unread' => 'Merket som ulest',
        'owner_changed' => 'Eier endret',
        'added_to_spam_list' => 'Lagt til spamliste',
        'removed_from_spam_list' => 'Fjernet fra spamliste',
    ],

    // Confirmations
    'confirmations' => [
        'delete_title' => 'Slette?',
        'delete_description' => 'Er du sikker på at du vil slette dette?',
        'spam_title' => 'Merk som spam?',
        'spam_description' => 'Meldingen flyttes til spam og avsenderen legges til i spamfilterlisten.',
        'trash_title' => 'Flytt til papirkurv?',
        'trash_description' => 'Er du sikker på at du vil flytte dette til papirkurven?',
    ],

    // Placeholders
    'placeholders' => [
        'select_recipients' => 'Velg mottakere...',
        'enter_subject' => 'Skriv emne...',
        'enter_message' => 'Skriv melding...',
        'select_snippet' => 'Velg mal...',
        'select_folder' => 'Velg mappe...',
        'enter_email' => 'Skriv e-postadresse...',
        'enter_reason' => 'Skriv grunn for blokkering...',
        'enter_name' => 'Skriv navn...',
        'enter_shortcut' => 'Skriv snarvei...',
    ],

    // Helpers
    'helpers' => [
        'shortcut' => 'Valgfri snarvei for raskt å sette inn denne malen',
        'slug' => 'Unik identifikator for mappen',
    ],

    // Thread View
    'thread' => [
        'messages_count' => ':count melding|:count meldinger',
        'inbound' => 'Mottatt',
        'outbound' => 'Sendt',
        'attachments' => 'Vedlegg',
        'download' => 'Last ned',
    ],

    // Spam Detection (AI prompt)
    'spam_detection' => [
        'prompt' => <<<'PROMPT'
Du er en spam-detektor for en e-postinnboks. Klassifiser en e-post som spam hvis den er:
- Nyhetsbrev eller ukentlige oppsummeringer
- Markedsføring eller reklame
- Automatiske meldinger fra bedrifter
- Masseutsendelser som ikke er personlig adressert
- Tilbud eller kampanjer
- Systemvarsler fra tjenester (bekreftelser, varsler)

IKKE spam hvis det er:
- En personlig henvendelse
- Et spørsmål fra en privatperson eller organisasjon
- Et svar på en tidligere samtale
- En forespørsel om pris eller tilgjengelighet
- Direkte kommunikasjon
PROMPT,
    ],

    // Contact Enrichment (AI prompt)
    'contact_enrichment' => [
        'prompt' => 'Trekk ut kontaktinformasjon fra følgende e-postinnhold. Se etter avsenderens navn, telefonnummer og firmanavn.',
    ],

    // Signature
    'signature' => [
        'regards' => 'Med vennlig hilsen',
    ],

    // Empty States
    'empty' => [
        'inbox' => 'Ingen meldinger',
        'inbox_description' => 'Innboksen din er tom.',
        'folder' => 'Ingen meldinger i denne mappen',
    ],

    // Misc
    'misc' => [
        'unread' => 'ulest',
        'starred' => 'Stjernemerket',
        'handled_by' => 'Håndtert av',
        'select_all' => 'Velg alle',
    ],
];
