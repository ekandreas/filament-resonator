<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Resend Configuration
    |--------------------------------------------------------------------------
    |
    | The API key for the Resend service. This is required for sending and
    | receiving emails through the inbox.
    |
    */
    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Mail Configuration
    |--------------------------------------------------------------------------
    |
    | Default from address and name for outgoing emails.
    |
    */
    'mail' => [
        'from_address' => env('RESONATOR_FROM_ADDRESS', env('MAIL_FROM_ADDRESS')),
        'from_name' => env('RESONATOR_FROM_NAME', env('MAIL_FROM_NAME')),
    ],

    /*
    |--------------------------------------------------------------------------
    | AI Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for AI-powered features using Prism.
    |
    */
    'ai' => [
        'enabled' => env('RESONATOR_AI_ENABLED', true),
        'provider' => env('RESONATOR_AI_PROVIDER', 'openai'),
        'model' => env('RESONATOR_AI_MODEL', 'gpt-4o-mini'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Spam Detection
    |--------------------------------------------------------------------------
    |
    | Enable or disable automatic spam detection for incoming emails.
    |
    */
    'spam_detection' => [
        'enabled' => env('RESONATOR_SPAM_DETECTION', true),
        'delay_seconds' => 5,
    ],

    /*
    |--------------------------------------------------------------------------
    | Contact Enrichment
    |--------------------------------------------------------------------------
    |
    | Enable or disable automatic contact enrichment from email content.
    |
    */
    'contact_enrichment' => [
        'enabled' => env('RESONATOR_CONTACT_ENRICHMENT', true),
        'max_emails_to_analyze' => 3,
        'max_text_length' => 3000,
    ],

    /*
    |--------------------------------------------------------------------------
    | Cleanup Configuration
    |--------------------------------------------------------------------------
    |
    | Settings for automatic cleanup of old messages in trash and spam.
    |
    */
    'cleanup' => [
        'enabled' => env('RESONATOR_CLEANUP_ENABLED', true),
        'days' => env('RESONATOR_CLEANUP_DAYS', 30),
    ],

    /*
    |--------------------------------------------------------------------------
    | Navigation
    |--------------------------------------------------------------------------
    |
    | Configure the navigation group and sorting for the inbox menu items.
    |
    */
    'navigation' => [
        'group' => 'Resonator',
        'sort' => 100,
    ],

    /*
    |--------------------------------------------------------------------------
    | Thread Matching
    |--------------------------------------------------------------------------
    |
    | Settings for grouping emails into threads.
    |
    */
    'threading' => [
        // Number of days to look back when matching threads by subject
        'subject_match_days' => 30,
    ],

    /*
    |--------------------------------------------------------------------------
    | Pagination
    |--------------------------------------------------------------------------
    |
    | Default pagination settings for the inbox list.
    |
    */
    'pagination' => [
        'default' => 25,
        'options' => [25, 50, 100],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Model
    |--------------------------------------------------------------------------
    |
    | The user model class to use for relationships (handler, created_by, etc.)
    |
    */
    'user_model' => \App\Models\User::class,
];
