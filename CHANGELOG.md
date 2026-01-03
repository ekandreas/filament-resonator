# Changelog

All notable changes to `filament-resonator` will be documented in this file.

## [0.1.0-beta] - 2025-01-03

### Added

- Initial beta release
- **InboxResource** - Full email inbox management with list and view pages
- **FolderResource** - Manage email folders (system + custom)
- **SnippetResource** - Reply templates with shortcuts
- **SpamFilterResource** - Manage blocked email addresses
- **SyncEmails** action - Sync emails from Resend API
- **SendReply** action - Send replies with signatures and threading
- **CleanupOldMessages** action - Auto-cleanup old trash/spam
- **DetectSpam** job - AI-powered spam detection via Prism
- **EnrichContact** job - AI-powered contact info extraction
- **resonator:sync** command - CLI for email sync and cleanup
- English and Swedish translations
- GitHub Actions workflows for testing and releases

### Requirements

- PHP 8.4+
- Laravel 12.x
- Filament 4.x
