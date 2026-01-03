# Claude Code Project Context

## Project Overview

**Filament Resonator** is a Filament 4 plugin for email inbox management with Resend integration and AI-powered features via Prism.

- **Repository**: `ekandreas/filament-resonator`
- **Namespace**: `EkAndreas\Resonator`
- **Package**: `ekandreas/filament-resonator`

## Technical Stack

| Component | Version | Notes |
|-----------|---------|-------|
| PHP | ^8.4 | Minimum required |
| Laravel | ^12.0 | Only Laravel 12 supported |
| Filament | ^4.0 | Admin panel framework |
| Prism PHP | ^1.0 | AI integration (OpenAI, Anthropic, Ollama) |
| Saloon | ^3.0 | HTTP client for Resend API |
| Pest | ^4.0 | Testing framework |

## Key Architectural Decisions

### Model Naming Convention
All models use `Resonator` prefix to avoid collisions with host application models:
- `ResonatorFolder`
- `ResonatorThread`
- `ResonatorEmail`
- `ResonatorAttachment`
- `ResonatorContact`
- `ResonatorSnippet`
- `ResonatorSpamFilter`

### Database Tables
All tables use `resonator_` prefix:
- `resonator_folders`
- `resonator_threads`
- `resonator_emails`
- `resonator_attachments`
- `resonator_contacts`
- `resonator_snippets`
- `resonator_spam_filters`

### Config Namespace
Configuration is at `config/resonator.php`.

### Translation Key Structure
All translations use `resonator::resonator.{section}.{key}` pattern.

## Directory Structure

```
src/
├── Actions/           # Business logic actions
│   ├── CleanupOldMessages.php
│   ├── SendReply.php
│   └── SyncEmails.php
├── Commands/          # Artisan commands
│   └── SyncInboxCommand.php
├── Filament/
│   └── Resources/     # Filament resources
│       ├── FolderResource.php
│       ├── InboxResource.php
│       ├── SnippetResource.php
│       └── SpamFilterResource.php
├── Http/
│   └── Integrations/
│       └── Resend/    # Saloon connector
├── Jobs/              # Queued jobs
│   ├── DetectSpam.php
│   └── EnrichContact.php
├── Models/            # Eloquent models
├── ResonatorPlugin.php
└── ResonatorServiceProvider.php

resources/
└── lang/              # Translations (en, sv, de, fr, es, nb, fi, da, pt)

database/
└── migrations/        # 7 migration files

tests/
├── Feature/           # Feature tests
├── Unit/              # Unit tests
└── Fixtures/          # Mock responses (ResendMock, PrismMock)
```

## External Services

### Resend API
- Used for sending/receiving emails
- Connector: `src/Http/Integrations/Resend/ResendConnector.php`
- Requires `RESEND_KEY` env variable

### Prism AI
- Used for spam detection and contact enrichment
- Supports multiple providers: OpenAI, Anthropic, Ollama
- Configured via `config/prism.php`

## Testing Strategy

- Use Pest 4 with Laravel plugin
- Mock external services using fixtures in `tests/Fixtures/`
- `ResendMock` for Resend API responses
- `PrismMock` for AI responses
- Run tests: `composer test`

## System Folders

5 system folders created by migration seeder:
1. Inbox (`inbox`)
2. Sent (`sent`)
3. Archive (`archive`)
4. Spam (`spam`)
5. Trash (`trash`)

## AI Features

### Spam Detection
- Job: `DetectSpam`
- Runs 5 seconds after email sync
- Uses Prism structured output with `is_spam` boolean

### Contact Enrichment
- Job: `EnrichContact`
- Extracts name, phone, company from email content
- Analyzes up to 3 recent emails, max 3000 chars

## Common Tasks

### Adding a new translation
1. Copy `resources/lang/en/resonator.php` to new locale folder
2. Translate all strings
3. Update README translation table

### Adding a new model
1. Create model in `src/Models/` with `Resonator` prefix
2. Create migration in `database/migrations/` with `resonator_` table prefix
3. Add relationship methods to related models

### Adding a new Filament resource
1. Create resource in `src/Filament/Resources/`
2. Register in `ResonatorPlugin.php` with feature flag if needed
3. Add translation keys

## GitHub Workflow

- CI runs on push to main and PRs
- Tests PHP 8.4 + Laravel 12 matrix
- Releases created via GitHub releases (tags trigger release workflow)

## Development Commands

```bash
composer test          # Run tests
composer analyse       # Run PHPStan
composer format        # Run Pint formatter
```
