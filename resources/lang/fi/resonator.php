<?php

declare(strict_types=1);

return [
    // Navigation
    'navigation' => [
        'group' => 'Resonator',
        'inbox' => 'Saapuneet',
        'folders' => 'Kansiot',
        'snippets' => 'Mallit',
        'spam_filters' => 'Roskapostisuodattimet',
    ],

    // Folders
    'folders' => [
        'inbox' => 'Saapuneet',
        'sent' => 'Lähetetyt',
        'archive' => 'Arkisto',
        'spam' => 'Roskaposti',
        'trash' => 'Roskakori',
    ],

    // Labels
    'labels' => [
        'from' => 'Lähettäjä',
        'to' => 'Vastaanottaja',
        'cc' => 'Kopio',
        'bcc' => 'Piilokopio',
        'subject' => 'Aihe',
        'body' => 'Sisältö',
        'message' => 'Viesti',
        'attachments' => 'Liitteet',
        'snippet' => 'Malli',
        'folder' => 'Kansio',
        'owner' => 'Omistaja',
        'date' => 'Päivämäärä',
        'name' => 'Nimi',
        'email' => 'Sähköposti',
        'phone' => 'Puhelin',
        'company' => 'Yritys',
        'reason' => 'Syy',
        'shortcut' => 'Pikakuvake',
        'icon' => 'Kuvake',
        'color' => 'Väri',
        'sort_order' => 'Järjestys',
        'active' => 'Aktiivinen',
        'system' => 'Järjestelmä',
        'custom' => 'Mukautettu',
        'slug' => 'Tunniste',
    ],

    // Actions
    'actions' => [
        'sync' => 'Synkronoi sähköpostit',
        'syncing' => 'Synkronoidaan...',
        'compose' => 'Kirjoita',
        'reply' => 'Vastaa',
        'send' => 'Lähetä',
        'archive' => 'Arkistoi',
        'move_to_trash' => 'Siirrä roskakoriin',
        'move_to_spam' => 'Merkitse roskapostiksi',
        'move_to_folder' => 'Siirrä kansioon',
        'go_to_folder' => 'Siirry...',
        'mark_read' => 'Merkitse luetuksi',
        'mark_unread' => 'Merkitse lukemattomaksi',
        'toggle_star' => 'Vaihda tähtimerkintä',
        'change_owner' => 'Vaihda omistaja',
        'create' => 'Luo',
        'edit' => 'Muokkaa',
        'delete' => 'Poista',
        'save' => 'Tallenna',
        'cancel' => 'Peruuta',
    ],

    // Filters
    'filters' => [
        'folder' => 'Kansio',
        'read_status' => 'Lukutila',
        'unread_only' => 'Vain lukemattomat',
        'read_only' => 'Vain luetut',
        'starred_only' => 'Vain tähdellä merkityt',
    ],

    // Messages/Notifications
    'messages' => [
        'sync_success' => ':synced uutta sähköpostia synkronoitu. :skipped jo synkronoitu. :spam siirretty roskapostiin.',
        'sync_error' => 'Synkronointivirhe: :error',
        'email_sent' => 'Sähköposti lähetetty',
        'email_send_error' => 'Sähköpostin lähetys epäonnistui: :error',
        'moved_to_folder' => 'Siirretty kansioon :folder',
        'archived' => 'Arkistoitu',
        'moved_to_trash' => 'Siirretty roskakoriin',
        'moved_to_spam' => 'Merkitty roskapostiksi',
        'marked_read' => 'Merkitty luetuksi',
        'marked_unread' => 'Merkitty lukemattomaksi',
        'owner_changed' => 'Omistaja vaihdettu',
        'added_to_spam_list' => 'Lisätty roskapostilistalle',
        'removed_from_spam_list' => 'Poistettu roskapostilistalta',
    ],

    // Confirmations
    'confirmations' => [
        'delete_title' => 'Poista?',
        'delete_description' => 'Haluatko varmasti poistaa tämän?',
        'spam_title' => 'Merkitse roskapostiksi?',
        'spam_description' => 'Viesti siirretään roskapostiin ja lähettäjä lisätään roskapostisuodattimeen.',
        'trash_title' => 'Siirrä roskakoriin?',
        'trash_description' => 'Haluatko varmasti siirtää tämän roskakoriin?',
    ],

    // Placeholders
    'placeholders' => [
        'select_recipients' => 'Valitse vastaanottajat...',
        'enter_subject' => 'Kirjoita aihe...',
        'enter_message' => 'Kirjoita viesti...',
        'select_snippet' => 'Valitse malli...',
        'select_folder' => 'Valitse kansio...',
        'enter_email' => 'Kirjoita sähköpostiosoite...',
        'enter_reason' => 'Kirjoita eston syy...',
        'enter_name' => 'Kirjoita nimi...',
        'enter_shortcut' => 'Kirjoita pikakuvake...',
    ],

    // Helpers
    'helpers' => [
        'shortcut' => 'Valinnainen pikakuvake tämän mallin nopeaan lisäämiseen',
        'slug' => 'Kansion yksilöivä tunniste',
    ],

    // Thread View
    'thread' => [
        'messages_count' => ':count viesti|:count viestiä',
        'inbound' => 'Saapunut',
        'outbound' => 'Lähetetty',
        'attachments' => 'Liitteet',
        'download' => 'Lataa',
    ],

    // Spam Detection (AI prompt)
    'spam_detection' => [
        'prompt' => <<<'PROMPT'
Olet roskapostin tunnistaja sähköpostilaatikolle. Luokittele sähköposti roskapostiksi, jos se on:
- Uutiskirjeitä tai viikoittaisia yhteenvetoja
- Markkinointia tai mainontaa
- Automatisoituja viestejä yrityksiltä
- Massapostituksia, jotka eivät ole henkilökohtaisesti osoitettuja
- Tarjouksia tai kampanjoita
- Järjestelmäilmoituksia palveluilta (vahvistukset, ilmoitukset)

EI roskapostia, jos se on:
- Henkilökohtainen yhteydenotto
- Kysymys yksityishenkilöltä tai organisaatiolta
- Vastaus aiempaan keskusteluun
- Hintatiedustelu tai saatavuuskysely
- Suora viestintä
PROMPT,
    ],

    // Contact Enrichment (AI prompt)
    'contact_enrichment' => [
        'prompt' => 'Poimi yhteystiedot seuraavasta sähköpostin sisällöstä. Etsi lähettäjän nimi, puhelinnumero ja yrityksen nimi.',
    ],

    // Signature
    'signature' => [
        'regards' => 'Ystävällisin terveisin',
    ],

    // Empty States
    'empty' => [
        'inbox' => 'Ei viestejä',
        'inbox_description' => 'Saapuneet-kansiosi on tyhjä.',
        'folder' => 'Tässä kansiossa ei ole viestejä',
    ],

    // Misc
    'misc' => [
        'unread' => 'lukematon',
        'starred' => 'Tähdellä merkitty',
        'handled_by' => 'Käsittelijä',
        'select_all' => 'Valitse kaikki',
    ],
];
