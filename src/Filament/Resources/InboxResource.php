<?php

declare(strict_types=1);

namespace EkAndreas\Resonator\Filament\Resources;

use EkAndreas\Resonator\Actions\SyncEmails;
use EkAndreas\Resonator\Filament\Resources\InboxResource\Pages;
use EkAndreas\Resonator\Models\ResonatorFolder;
use EkAndreas\Resonator\Models\ResonatorSpamFilter;
use EkAndreas\Resonator\Models\ResonatorThread;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class InboxResource extends Resource
{
    protected static ?string $model = ResonatorThread::class;

    protected static ?string $navigationIcon = 'heroicon-o-inbox';

    protected static ?int $navigationSort = 1;

    public static function getNavigationGroup(): ?string
    {
        return config('resonator.navigation.group', 'Resonator');
    }

    public static function getNavigationLabel(): string
    {
        return __('resonator::resonator.navigation.inbox');
    }

    public static function getModelLabel(): string
    {
        return __('resonator::resonator.navigation.inbox');
    }

    public static function getPluralModelLabel(): string
    {
        return __('resonator::resonator.navigation.inbox');
    }

    public static function getNavigationBadge(): ?string
    {
        $inbox = ResonatorFolder::inbox();

        if (! $inbox) {
            return null;
        }

        $count = $inbox->unreadCount();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'danger';
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\IconColumn::make('is_starred')
                    ->icon(fn (bool $state): string => $state ? 'heroicon-s-star' : 'heroicon-o-star')
                    ->color(fn (bool $state): string => $state ? 'warning' : 'gray')
                    ->action(fn (ResonatorThread $record) => $record->toggleStar())
                    ->label(''),

                Tables\Columns\TextColumn::make('participant_name')
                    ->label(__('resonator::resonator.labels.from'))
                    ->description(fn (ResonatorThread $record) => $record->participant_email)
                    ->weight(fn (ResonatorThread $record) => $record->is_read ? null : 'bold')
                    ->searchable(['participant_name', 'participant_email']),

                Tables\Columns\TextColumn::make('subject')
                    ->label(__('resonator::resonator.labels.subject'))
                    ->description(fn (ResonatorThread $record) => $record->latestEmail?->preview)
                    ->weight(fn (ResonatorThread $record) => $record->is_read ? null : 'bold')
                    ->searchable()
                    ->limit(50),

                Tables\Columns\TextColumn::make('last_message_at')
                    ->label(__('resonator::resonator.labels.date'))
                    ->dateTime('j M H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('emails_count')
                    ->label('')
                    ->counts('emails')
                    ->formatStateUsing(fn ($state) => "({$state})"),

                Tables\Columns\TextColumn::make('handler.name')
                    ->label(__('resonator::resonator.labels.owner'))
                    ->badge()
                    ->color('success')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('folder.name')
                    ->label(__('resonator::resonator.labels.folder'))
                    ->badge()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('folder_id')
                    ->label(__('resonator::resonator.filters.folder'))
                    ->relationship('folder', 'name'),

                Tables\Filters\SelectFilter::make('is_read')
                    ->label(__('resonator::resonator.filters.read_status'))
                    ->options([
                        '0' => __('resonator::resonator.filters.unread_only'),
                        '1' => __('resonator::resonator.filters.read_only'),
                    ]),

                Tables\Filters\SelectFilter::make('is_starred')
                    ->label(__('resonator::resonator.filters.starred_only'))
                    ->options([
                        '1' => __('resonator::resonator.filters.starred_only'),
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('move_to_folder')
                        ->label(__('resonator::resonator.actions.move_to_folder'))
                        ->icon('heroicon-o-folder')
                        ->form([
                            \Filament\Forms\Components\Select::make('folder_id')
                                ->label(__('resonator::resonator.labels.folder'))
                                ->options(ResonatorFolder::pluck('name', 'id'))
                                ->required(),
                        ])
                        ->action(function (array $data, $records) {
                            $folder = ResonatorFolder::find($data['folder_id']);
                            if ($folder) {
                                foreach ($records as $record) {
                                    $record->moveToFolder($folder);
                                }
                            }
                        }),

                    Tables\Actions\BulkAction::make('mark_read')
                        ->label(__('resonator::resonator.actions.mark_read'))
                        ->icon('heroicon-o-envelope-open')
                        ->action(fn ($records) => $records->each->markAsRead()),

                    Tables\Actions\BulkAction::make('mark_unread')
                        ->label(__('resonator::resonator.actions.mark_unread'))
                        ->icon('heroicon-o-envelope')
                        ->action(fn ($records) => $records->each->markAsUnread()),

                    Tables\Actions\BulkAction::make('archive')
                        ->label(__('resonator::resonator.actions.archive'))
                        ->icon('heroicon-o-archive-box')
                        ->action(fn ($records) => $records->each->archive()),

                    Tables\Actions\BulkAction::make('spam')
                        ->label(__('resonator::resonator.actions.move_to_spam'))
                        ->icon('heroicon-o-shield-exclamation')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->modalHeading(__('resonator::resonator.confirmations.spam_title'))
                        ->modalDescription(__('resonator::resonator.confirmations.spam_description'))
                        ->action(function ($records) {
                            foreach ($records as $record) {
                                $record->moveToSpam();
                                ResonatorSpamFilter::addToSpamList($record->participant_email);
                            }
                        }),

                    Tables\Actions\BulkAction::make('trash')
                        ->label(__('resonator::resonator.actions.move_to_trash'))
                        ->icon('heroicon-o-trash')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->modalHeading(__('resonator::resonator.confirmations.trash_title'))
                        ->modalDescription(__('resonator::resonator.confirmations.trash_description'))
                        ->action(fn ($records) => $records->each->moveToTrash()),
                ]),
            ])
            ->headerActions([
                Tables\Actions\Action::make('sync')
                    ->label(__('resonator::resonator.actions.sync'))
                    ->icon('heroicon-o-arrow-path')
                    ->action(function () {
                        $result = (new SyncEmails)->execute();

                        Notification::make()
                            ->title(__('resonator::resonator.messages.sync_success', [
                                'synced' => $result['synced'],
                                'skipped' => $result['skipped'],
                                'spam' => $result['spam'],
                            ]))
                            ->success()
                            ->send();
                    }),
            ])
            ->defaultSort('last_message_at', 'desc')
            ->paginated(config('resonator.pagination.options', [25, 50, 100]))
            ->defaultPaginationPageOption(config('resonator.pagination.default', 25))
            ->recordUrl(fn (ResonatorThread $record) => Pages\ViewThread::getUrl(['record' => $record]));
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInbox::route('/'),
            'view' => Pages\ViewThread::route('/{record}'),
        ];
    }
}
