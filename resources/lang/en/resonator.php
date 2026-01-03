<?php

declare(strict_types=1);

return [
    // Navigation
    'navigation' => [
        'group' => 'Resonator',
        'inbox' => 'Inbox',
        'folders' => 'Folders',
        'snippets' => 'Snippets',
        'spam_filters' => 'Spam Filters',
    ],

    // Folders
    'folders' => [
        'inbox' => 'Inbox',
        'sent' => 'Sent',
        'archive' => 'Archive',
        'spam' => 'Spam',
        'trash' => 'Trash',
    ],

    // Labels
    'labels' => [
        'from' => 'From',
        'to' => 'To',
        'cc' => 'CC',
        'bcc' => 'BCC',
        'subject' => 'Subject',
        'body' => 'Body',
        'message' => 'Message',
        'attachments' => 'Attachments',
        'snippet' => 'Snippet',
        'folder' => 'Folder',
        'owner' => 'Owner',
        'date' => 'Date',
        'name' => 'Name',
        'email' => 'Email',
        'phone' => 'Phone',
        'company' => 'Company',
        'reason' => 'Reason',
        'shortcut' => 'Shortcut',
        'icon' => 'Icon',
        'color' => 'Color',
        'sort_order' => 'Sort Order',
        'active' => 'Active',
        'system' => 'System',
        'custom' => 'Custom',
        'slug' => 'Slug',
    ],

    // Actions
    'actions' => [
        'sync' => 'Sync Emails',
        'syncing' => 'Syncing...',
        'compose' => 'Compose',
        'reply' => 'Reply',
        'send' => 'Send',
        'archive' => 'Archive',
        'move_to_trash' => 'Move to Trash',
        'move_to_spam' => 'Mark as Spam',
        'move_to_folder' => 'Move to Folder',
        'go_to_folder' => 'Go to...',
        'mark_read' => 'Mark as Read',
        'mark_unread' => 'Mark as Unread',
        'toggle_star' => 'Toggle Star',
        'change_owner' => 'Change Owner',
        'create' => 'Create',
        'edit' => 'Edit',
        'delete' => 'Delete',
        'save' => 'Save',
        'cancel' => 'Cancel',
    ],

    // Filters
    'filters' => [
        'folder' => 'Folder',
        'read_status' => 'Read Status',
        'unread_only' => 'Unread Only',
        'read_only' => 'Read Only',
        'starred_only' => 'Starred Only',
    ],

    // Messages/Notifications
    'messages' => [
        'sync_success' => ':synced new emails synced. :skipped already synced. :spam moved to spam.',
        'sync_error' => 'Error syncing emails: :error',
        'email_sent' => 'Email sent successfully',
        'email_send_error' => 'Failed to send email: :error',
        'moved_to_folder' => 'Moved to :folder',
        'archived' => 'Archived successfully',
        'moved_to_trash' => 'Moved to trash',
        'moved_to_spam' => 'Marked as spam',
        'marked_read' => 'Marked as read',
        'marked_unread' => 'Marked as unread',
        'owner_changed' => 'Owner changed',
        'added_to_spam_list' => 'Added to spam list',
        'removed_from_spam_list' => 'Removed from spam list',
    ],

    // Confirmations
    'confirmations' => [
        'delete_title' => 'Delete?',
        'delete_description' => 'Are you sure you want to delete this?',
        'spam_title' => 'Mark as Spam?',
        'spam_description' => 'This will move the message to spam and add the sender to the spam filter list.',
        'trash_title' => 'Move to Trash?',
        'trash_description' => 'Are you sure you want to move this to trash?',
    ],

    // Placeholders
    'placeholders' => [
        'select_recipients' => 'Select recipients...',
        'enter_subject' => 'Enter subject...',
        'enter_message' => 'Enter your message...',
        'select_snippet' => 'Select a snippet...',
        'select_folder' => 'Select folder...',
        'enter_email' => 'Enter email address...',
        'enter_reason' => 'Enter reason for blocking...',
        'enter_name' => 'Enter name...',
        'enter_shortcut' => 'Enter shortcut...',
    ],

    // Helpers
    'helpers' => [
        'shortcut' => 'Optional shortcut to quickly insert this snippet',
        'slug' => 'Unique identifier for the folder',
    ],

    // Thread View
    'thread' => [
        'messages_count' => ':count message|:count messages',
        'inbound' => 'Received',
        'outbound' => 'Sent',
        'attachments' => 'Attachments',
        'download' => 'Download',
    ],

    // Spam Detection (AI prompt)
    'spam_detection' => [
        'prompt' => <<<'PROMPT'
You are a spam detector for an email inbox. Classify an email as spam if it is:
- Newsletters or weekly digests
- Marketing or advertising
- Automated messages from companies
- Mass mailings that are not personally addressed
- Offers or campaigns
- System notifications from services (confirmations, notifications)

NOT spam if it is:
- A personal inquiry
- A question from a private person or organization
- A reply to a previous conversation
- An inquiry about price or availability
- Direct communication
PROMPT,
    ],

    // Contact Enrichment (AI prompt)
    'contact_enrichment' => [
        'prompt' => 'Extract contact information from the following email content. Look for the sender\'s name, phone number, and company name.',
    ],

    // Signature
    'signature' => [
        'regards' => 'Best regards',
    ],

    // Empty States
    'empty' => [
        'inbox' => 'No messages',
        'inbox_description' => 'Your inbox is empty.',
        'folder' => 'No messages in this folder',
    ],

    // Misc
    'misc' => [
        'unread' => 'unread',
        'starred' => 'Starred',
        'handled_by' => 'Handled by',
        'select_all' => 'Select all',
    ],
];
