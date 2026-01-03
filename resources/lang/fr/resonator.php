<?php

declare(strict_types=1);

return [
    // Navigation
    'navigation' => [
        'group' => 'Resonator',
        'inbox' => 'Boîte de réception',
        'folders' => 'Dossiers',
        'snippets' => 'Modèles',
        'spam_filters' => 'Filtres anti-spam',
    ],

    // Folders
    'folders' => [
        'inbox' => 'Boîte de réception',
        'sent' => 'Envoyés',
        'archive' => 'Archives',
        'spam' => 'Spam',
        'trash' => 'Corbeille',
    ],

    // Labels
    'labels' => [
        'from' => 'De',
        'to' => 'À',
        'cc' => 'CC',
        'bcc' => 'CCI',
        'subject' => 'Objet',
        'body' => 'Corps',
        'message' => 'Message',
        'attachments' => 'Pièces jointes',
        'snippet' => 'Modèle',
        'folder' => 'Dossier',
        'owner' => 'Propriétaire',
        'date' => 'Date',
        'name' => 'Nom',
        'email' => 'E-mail',
        'phone' => 'Téléphone',
        'company' => 'Entreprise',
        'reason' => 'Raison',
        'shortcut' => 'Raccourci',
        'icon' => 'Icône',
        'color' => 'Couleur',
        'sort_order' => 'Ordre de tri',
        'active' => 'Actif',
        'system' => 'Système',
        'custom' => 'Personnalisé',
        'slug' => 'Slug',
    ],

    // Actions
    'actions' => [
        'sync' => 'Synchroniser les e-mails',
        'syncing' => 'Synchronisation...',
        'compose' => 'Rédiger',
        'reply' => 'Répondre',
        'send' => 'Envoyer',
        'archive' => 'Archiver',
        'move_to_trash' => 'Mettre à la corbeille',
        'move_to_spam' => 'Marquer comme spam',
        'move_to_folder' => 'Déplacer vers le dossier',
        'go_to_folder' => 'Aller à...',
        'mark_read' => 'Marquer comme lu',
        'mark_unread' => 'Marquer comme non lu',
        'toggle_star' => 'Basculer l\'étoile',
        'change_owner' => 'Changer le propriétaire',
        'create' => 'Créer',
        'edit' => 'Modifier',
        'delete' => 'Supprimer',
        'save' => 'Enregistrer',
        'cancel' => 'Annuler',
    ],

    // Filters
    'filters' => [
        'folder' => 'Dossier',
        'read_status' => 'Statut de lecture',
        'unread_only' => 'Non lus uniquement',
        'read_only' => 'Lus uniquement',
        'starred_only' => 'Favoris uniquement',
    ],

    // Messages/Notifications
    'messages' => [
        'sync_success' => ':synced nouveaux e-mails synchronisés. :skipped déjà synchronisés. :spam déplacés vers spam.',
        'sync_error' => 'Erreur de synchronisation : :error',
        'email_sent' => 'E-mail envoyé avec succès',
        'email_send_error' => 'Échec de l\'envoi de l\'e-mail : :error',
        'moved_to_folder' => 'Déplacé vers :folder',
        'archived' => 'Archivé avec succès',
        'moved_to_trash' => 'Déplacé vers la corbeille',
        'moved_to_spam' => 'Marqué comme spam',
        'marked_read' => 'Marqué comme lu',
        'marked_unread' => 'Marqué comme non lu',
        'owner_changed' => 'Propriétaire modifié',
        'added_to_spam_list' => 'Ajouté à la liste de spam',
        'removed_from_spam_list' => 'Retiré de la liste de spam',
    ],

    // Confirmations
    'confirmations' => [
        'delete_title' => 'Supprimer ?',
        'delete_description' => 'Êtes-vous sûr de vouloir supprimer ceci ?',
        'spam_title' => 'Marquer comme spam ?',
        'spam_description' => 'Le message sera déplacé vers le spam et l\'expéditeur sera ajouté à la liste des filtres anti-spam.',
        'trash_title' => 'Mettre à la corbeille ?',
        'trash_description' => 'Êtes-vous sûr de vouloir mettre ceci à la corbeille ?',
    ],

    // Placeholders
    'placeholders' => [
        'select_recipients' => 'Sélectionner les destinataires...',
        'enter_subject' => 'Saisir l\'objet...',
        'enter_message' => 'Saisir votre message...',
        'select_snippet' => 'Sélectionner un modèle...',
        'select_folder' => 'Sélectionner un dossier...',
        'enter_email' => 'Saisir l\'adresse e-mail...',
        'enter_reason' => 'Saisir la raison du blocage...',
        'enter_name' => 'Saisir le nom...',
        'enter_shortcut' => 'Saisir le raccourci...',
    ],

    // Helpers
    'helpers' => [
        'shortcut' => 'Raccourci optionnel pour insérer rapidement ce modèle',
        'slug' => 'Identifiant unique pour le dossier',
    ],

    // Thread View
    'thread' => [
        'messages_count' => ':count message|:count messages',
        'inbound' => 'Reçu',
        'outbound' => 'Envoyé',
        'attachments' => 'Pièces jointes',
        'download' => 'Télécharger',
    ],

    // Spam Detection (AI prompt)
    'spam_detection' => [
        'prompt' => <<<'PROMPT'
Vous êtes un détecteur de spam pour une boîte de réception. Classifiez un e-mail comme spam s'il s'agit de :
- Newsletters ou résumés hebdomadaires
- Marketing ou publicité
- Messages automatisés d'entreprises
- Envois en masse non personnellement adressés
- Offres ou campagnes
- Notifications système de services (confirmations, notifications)

PAS du spam s'il s'agit de :
- Une demande personnelle
- Une question d'une personne privée ou d'une organisation
- Une réponse à une conversation précédente
- Une demande de prix ou de disponibilité
- Une communication directe
PROMPT,
    ],

    // Contact Enrichment (AI prompt)
    'contact_enrichment' => [
        'prompt' => 'Extraire les informations de contact du contenu de l\'e-mail suivant. Recherchez le nom de l\'expéditeur, son numéro de téléphone et le nom de son entreprise.',
    ],

    // Signature
    'signature' => [
        'regards' => 'Cordialement',
    ],

    // Empty States
    'empty' => [
        'inbox' => 'Aucun message',
        'inbox_description' => 'Votre boîte de réception est vide.',
        'folder' => 'Aucun message dans ce dossier',
    ],

    // Misc
    'misc' => [
        'unread' => 'non lu',
        'starred' => 'Favoris',
        'handled_by' => 'Géré par',
        'select_all' => 'Tout sélectionner',
    ],
];
