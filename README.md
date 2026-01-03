# Filament Resonator

A powerful email inbox plugin for [Filament 4](https://filamentphp.com) with [Resend](https://resend.com) integration and AI-powered features via [Prism](https://prism.echolabs.dev/).

![Filament](https://img.shields.io/badge/Filament-4.x-orange)
![Laravel](https://img.shields.io/badge/Laravel-12.x-red)
![PHP](https://img.shields.io/badge/PHP-8.4+-blue)
![License](https://img.shields.io/badge/License-MIT-green)

## Features

- 📧 **Full Email Inbox** - Send, receive, and manage emails directly in Filament
- 🧵 **Thread Grouping** - Automatic conversation threading via headers
- 📁 **Folder Management** - Inbox, Sent, Archive, Spam, Trash + custom folders
- 🤖 **AI Spam Detection** - Automatic spam classification using Prism + LLM
- 👤 **Contact Enrichment** - Extract contact info from emails via AI
- 📝 **Reply Snippets** - Pre-defined templates for quick responses
- 🔄 **Auto Sync** - Scheduled syncing via artisan command
- 🌍 **Multilingual** - English and Swedish included

## Architecture Overview

```mermaid
graph TB
    subgraph "Filament Panel"
        IR[InboxResource]
        FR[FolderResource]
        SR[SnippetResource]
        SFR[SpamFilterResource]
    end

    subgraph "Core"
        RP[ResonatorPlugin]
        RSP[ResonatorServiceProvider]
    end

    subgraph "Actions"
        SE[SyncEmails]
        SR2[SendReply]
        CO[CleanupOldMessages]
    end

    subgraph "Jobs"
        DS[DetectSpam]
        EC[EnrichContact]
    end

    subgraph "External Services"
        RESEND[(Resend API)]
        PRISM[Prism]
        LLM[(OpenAI / Anthropic / Ollama)]
    end

    RP --> IR
    RP --> FR
    RP --> SR
    RP --> SFR

    IR --> SE
    IR --> SR2
    SE --> RESEND
    SR2 --> RESEND
    CO --> RESEND

    DS --> PRISM
    EC --> PRISM
    PRISM --> LLM
```

## Technology Stack

```mermaid
graph LR
    subgraph "Frontend"
        F[Filament 4]
        B[Blade Views]
        L[Livewire]
    end

    subgraph "Backend"
        LA[Laravel 11/12]
        SP[Spatie Package Tools]
    end

    subgraph "Email"
        RE[Resend API]
        SA[Saloon HTTP]
    end

    subgraph "AI Layer"
        PR[Prism PHP]
        OA[OpenAI]
        AN[Anthropic]
        OL[Ollama]
    end

    F --> LA
    B --> F
    L --> F
    LA --> SP
    LA --> SA
    SA --> RE
    LA --> PR
    PR --> OA
    PR --> AN
    PR --> OL
```

## Data Model

```mermaid
erDiagram
    ResonatorFolder ||--o{ ResonatorThread : contains
    ResonatorThread ||--o{ ResonatorEmail : contains
    ResonatorThread }o--o{ ResonatorContact : has
    ResonatorEmail ||--o{ ResonatorAttachment : has
    User ||--o{ ResonatorThread : handles
    User ||--o{ ResonatorSpamFilter : creates

    ResonatorFolder {
        bigint id PK
        string name
        string slug UK
        string icon
        string color
        boolean is_system
        integer sort_order
    }

    ResonatorThread {
        bigint id PK
        bigint folder_id FK
        string subject
        string participant_email
        string participant_name
        boolean is_starred
        boolean is_read
        datetime last_message_at
        bigint handled_by FK
        datetime handled_at
    }

    ResonatorEmail {
        bigint id PK
        bigint thread_id FK
        string resend_id UK
        string message_id
        string in_reply_to
        boolean is_inbound
        string from_email
        string from_name
        json to
        string subject
        longtext html
        longtext text
        datetime sent_at
    }

    ResonatorAttachment {
        bigint id PK
        bigint email_id FK
        string resend_id
        string filename
        string content_type
        bigint size
    }

    ResonatorContact {
        bigint id PK
        string email UK
        string name
        string phone
        string company
        string unsubscribe_token UK
        datetime unsubscribed_at
    }

    ResonatorSnippet {
        bigint id PK
        string name
        string shortcut UK
        string subject
        longtext body
        integer sort_order
        boolean is_active
    }

    ResonatorSpamFilter {
        bigint id PK
        string email UK
        text reason
        bigint created_by FK
    }
```

## Email Sync Flow

```mermaid
sequenceDiagram
    participant Scheduler
    participant SyncEmails
    participant Resend
    participant Database
    participant DetectSpam
    participant Prism
    participant LLM

    Scheduler->>SyncEmails: resonator:sync
    SyncEmails->>Resend: GET /emails/receiving
    Resend-->>SyncEmails: List of emails

    loop For each email
        SyncEmails->>Resend: GET /emails/receiving/{id}
        Resend-->>SyncEmails: Email details

        SyncEmails->>SyncEmails: Check spam filter
        SyncEmails->>SyncEmails: Find/create thread
        SyncEmails->>Database: Create ResonatorEmail
        SyncEmails->>Database: Create ResonatorAttachment(s)

        SyncEmails->>DetectSpam: Dispatch job (5s delay)
    end

    DetectSpam->>Prism: Structured prompt
    Prism->>LLM: Analyze email content
    LLM-->>Prism: JSON response
    Prism-->>DetectSpam: {is_spam, reason}

    alt is_spam = true
        DetectSpam->>Database: Move thread to spam folder
    end

    SyncEmails-->>Scheduler: {synced, skipped, spam, errors}
```

## Reply Flow

```mermaid
sequenceDiagram
    participant User
    participant ViewThread
    participant SendReply
    participant Mail
    participant Resend
    participant Database

    User->>ViewThread: Click Reply
    User->>ViewThread: Select snippet (optional)
    User->>ViewThread: Write message
    User->>ViewThread: Submit

    ViewThread->>SendReply: execute(thread, body)
    SendReply->>SendReply: Build signature
    SendReply->>SendReply: Set In-Reply-To headers
    SendReply->>Mail: Send via Laravel Mail
    Mail->>Resend: POST /emails
    Resend-->>Mail: {id, status}

    SendReply->>Database: Create ResonatorEmail (is_inbound=false)
    SendReply->>Database: Move thread to Sent folder
    SendReply->>Database: Mark as handled

    SendReply-->>ViewThread: Success
    ViewThread-->>User: Notification + Redirect
```

## Installation

### Step 1: Install via Composer

```bash
composer require ekandreas/filament-resonator
```

### Step 2: Publish and run migrations

```bash
php artisan vendor:publish --tag="resonator-migrations"
php artisan migrate
```

### Step 3: Publish config (optional)

```bash
php artisan vendor:publish --tag="resonator-config"
```

### Step 4: Configure Prism

Resonator uses [Prism](https://prism.echolabs.dev/) for AI features. Configure your preferred provider in `config/prism.php`:

```php
// config/prism.php
return [
    'providers' => [
        'openai' => [
            'api_key' => env('OPENAI_API_KEY'),
        ],
        // Or use Anthropic, Ollama, etc.
    ],
];
```

### Step 5: Register the plugin

In your Filament Panel Provider:

```php
use EkAndreas\Resonator\ResonatorPlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->plugins([
            ResonatorPlugin::make(),
        ]);
}
```

## Configuration

### Environment Variables

```env
# Required - Resend
RESEND_KEY=re_xxxxxxxxxxxx

# Required for AI features - Choose one provider
OPENAI_API_KEY=sk-xxxxxxxxxxxx
# or
ANTHROPIC_API_KEY=sk-ant-xxxxxxxxxxxx

# Optional - Mail settings
RESONATOR_FROM_ADDRESS=hello@example.com
RESONATOR_FROM_NAME="My App"

# Optional - AI settings
RESONATOR_AI_ENABLED=true
RESONATOR_AI_PROVIDER=openai
RESONATOR_AI_MODEL=gpt-4o-mini

# Optional - Features
RESONATOR_SPAM_DETECTION=true
RESONATOR_CONTACT_ENRICHMENT=true
RESONATOR_CLEANUP_ENABLED=true
RESONATOR_CLEANUP_DAYS=30
```

### Config File

```php
// config/resonator.php

return [
    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'mail' => [
        'from_address' => env('RESONATOR_FROM_ADDRESS'),
        'from_name' => env('RESONATOR_FROM_NAME'),
    ],

    'ai' => [
        'enabled' => env('RESONATOR_AI_ENABLED', true),
        'provider' => env('RESONATOR_AI_PROVIDER', 'openai'),
        'model' => env('RESONATOR_AI_MODEL', 'gpt-4o-mini'),
    ],

    'spam_detection' => [
        'enabled' => env('RESONATOR_SPAM_DETECTION', true),
        'delay_seconds' => 5,
    ],

    'contact_enrichment' => [
        'enabled' => env('RESONATOR_CONTACT_ENRICHMENT', true),
        'max_emails_to_analyze' => 3,
        'max_text_length' => 3000,
    ],

    'navigation' => [
        'group' => 'Resonator',
        'sort' => 100,
    ],

    'pagination' => [
        'default' => 25,
        'options' => [25, 50, 100],
    ],
];
```

## Prism AI Integration

Resonator leverages Prism's structured output feature for reliable AI responses:

```mermaid
graph TD
    subgraph "Prism Structured Output"
        A[Email Content] --> B[System Prompt]
        B --> C[ObjectSchema]
        C --> D{Provider}
        D --> E[OpenAI]
        D --> F[Anthropic]
        D --> G[Ollama]
        E --> H[JSON Response]
        F --> H
        G --> H
        H --> I[Validated Data]
    end
```

### Spam Detection Schema

```php
$schema = new ObjectSchema(
    name: 'spam_detection',
    properties: [
        new BooleanSchema('is_spam'),
        new StringSchema('reason'),
    ],
    requiredFields: ['is_spam']
);
```

### Contact Enrichment Schema

```php
$schema = new ObjectSchema(
    name: 'contact_info',
    properties: [
        new StringSchema('name'),
        new StringSchema('phone'),
        new StringSchema('company'),
    ]
);
```

### Supported Prism Providers

| Provider | Model Examples | Config |
|----------|---------------|--------|
| OpenAI | `gpt-4o-mini`, `gpt-4o` | `OPENAI_API_KEY` |
| Anthropic | `claude-3-haiku`, `claude-3-sonnet` | `ANTHROPIC_API_KEY` |
| Ollama | `llama3`, `mistral` | Local installation |

## Plugin Options

```php
ResonatorPlugin::make()
    ->folders(true)      // Enable folder management
    ->snippets(true)     // Enable reply snippets
    ->spamFilters(true)  // Enable spam filter management
```

## Scheduled Syncing

Add to your `routes/console.php` or scheduler:

```php
use Illuminate\Support\Facades\Schedule;

Schedule::command('resonator:sync')->everyFiveMinutes();
```

Or in `app/Console/Kernel.php`:

```php
protected function schedule(Schedule $schedule): void
{
    $schedule->command('resonator:sync')->everyFiveMinutes();
}
```

## Command Reference

```bash
# Sync emails and cleanup old messages
php artisan resonator:sync

# Sync without cleanup
php artisan resonator:sync --no-cleanup

# Custom cleanup period
php artisan resonator:sync --cleanup-days=60
```

## Navigation Structure

```mermaid
graph LR
    subgraph "Resonator Menu Group"
        A[📥 Inbox] --> A1[View Threads]
        A --> A2[Compose]
        A --> A3[Sync]

        B[📁 Folders] --> B1[Manage Folders]

        C[📝 Snippets] --> C1[Manage Templates]

        D[🛡️ Spam Filters] --> D1[Manage Blocked]
    end
```

## System Folders

The following folders are created automatically:

| Folder | Slug | Icon | Purpose |
|--------|------|------|---------|
| Inbox | `inbox` | 📥 | Incoming messages |
| Sent | `sent` | 📤 | Outgoing messages |
| Archive | `archive` | 📦 | Archived threads |
| Spam | `spam` | 🛡️ | Spam messages |
| Trash | `trash` | 🗑️ | Deleted messages |

## AI Features

### Spam Detection Flow

```mermaid
flowchart TD
    A[New Email Received] --> B{Is Inbound?}
    B -->|No| END[Skip]
    B -->|Yes| C[Queue DetectSpam Job]
    C --> D[Wait 5 seconds]
    D --> E[Build Prompt]
    E --> F[Prism Structured Call]
    F --> G[LLM Analysis]
    G --> H{AI Response}
    H -->|is_spam: true| I[Move to Spam Folder]
    H -->|is_spam: false| J[Keep in Inbox]
    I --> K[Log Result]
    J --> K
```

### Contact Enrichment

```mermaid
flowchart TD
    A[Thread Created] --> B[Queue EnrichContact Job]
    B --> C{Contact Complete?}
    C -->|Yes| END[Skip]
    C -->|No| D[Collect Last 3 Emails]
    D --> E[Limit Text to 3000 chars]
    E --> F[Prism Structured Call]
    F --> G[LLM Extraction]
    G --> H[Extract: name, phone, company]
    H --> I{Found New Data?}
    I -->|Yes| J[Update Contact]
    I -->|No| END2[Skip]
```

## Thread Matching Logic

```mermaid
flowchart TD
    A[New Email] --> B{Has In-Reply-To?}
    B -->|Yes| C[Find by Message-ID]
    C --> D{Found?}
    D -->|Yes| E[Add to Existing Thread]
    D -->|No| F{Match Subject + Sender?}
    B -->|No| F
    F -->|Yes| G[Within 30 days?]
    G -->|Yes| E
    G -->|No| H[Create New Thread]
    F -->|No| H
```

## Translations

Resonator includes English and Swedish translations. To add more languages:

```bash
php artisan vendor:publish --tag="resonator-translations"
```

Then create your translation file in `lang/vendor/resonator/{locale}/resonator.php`.

## Extending

### Custom Folder Types

```php
use EkAndreas\Resonator\Models\ResonatorFolder;

ResonatorFolder::create([
    'name' => 'VIP',
    'slug' => 'vip',
    'icon' => 'heroicon-o-star',
    'color' => 'warning',
    'is_system' => false,
]);
```

### Custom Snippets

```php
use EkAndreas\Resonator\Models\ResonatorSnippet;

ResonatorSnippet::create([
    'name' => 'Welcome Message',
    'shortcut' => 'welcome',
    'subject' => 'Welcome to Our Service',
    'body' => '<p>Thank you for reaching out...</p>',
    'is_active' => true,
]);
```

### Listening to Events

```php
// In a service provider
use EkAndreas\Resonator\Models\ResonatorEmail;

ResonatorEmail::created(function ($email) {
    if ($email->is_inbound) {
        // Notify team, trigger webhooks, etc.
    }
});
```

## File Structure

```
src/
├── Actions/
│   ├── CleanupOldMessages.php
│   ├── SendReply.php
│   └── SyncEmails.php
├── Commands/
│   └── SyncInboxCommand.php
├── Filament/
│   └── Resources/
│       ├── FolderResource.php
│       ├── InboxResource.php
│       ├── SnippetResource.php
│       └── SpamFilterResource.php
├── Http/
│   └── Integrations/
│       └── Resend/
│           ├── ResendConnector.php
│           └── Requests/
├── Jobs/
│   ├── DetectSpam.php
│   └── EnrichContact.php
├── Models/
│   ├── ResonatorAttachment.php
│   ├── ResonatorContact.php
│   ├── ResonatorEmail.php
│   ├── ResonatorFolder.php
│   ├── ResonatorSnippet.php
│   ├── ResonatorSpamFilter.php
│   └── ResonatorThread.php
├── ResonatorPlugin.php
└── ResonatorServiceProvider.php
```

## Testing

```bash
composer test
```

## Requirements

- PHP 8.4+
- Laravel 12.x
- Filament 4.x
- Resend account with API key
- LLM API key (OpenAI, Anthropic, or local Ollama)

## Dependencies

| Package | Version | Description |
|---------|---------|-------------|
| `php` | ^8.4 | PHP 8.4 or higher |
| `filament/filament` | ^4.0 | Filament admin panel (includes Livewire 3) |
| `illuminate/contracts` | ^12.0 | Laravel 12 contracts |
| `prism-php/prism` | ^1.0 | AI integration layer |
| `saloonphp/saloon` | ^3.0 | HTTP client for Resend API |
| `spatie/laravel-package-tools` | ^1.16 | Package development utilities |

```mermaid
graph TD
    A[filament-resonator] --> B[filament/filament ^4.0]
    A --> C[spatie/laravel-package-tools ^1.16]
    A --> D[saloonphp/saloon ^3.0]
    A --> E[prism-php/prism ^1.0]

    B --> F[Livewire 3]
    B --> G[Laravel 12]

    E --> H[OpenAI]
    E --> I[Anthropic]
    E --> J[Ollama]
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email security@elseif.se instead of using the issue tracker.

## Credits

- [Andreas Ek](https://github.com/ekandreas)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
