<?php

declare(strict_types=1);

namespace EkAndreas\Resonator\Filament\Resources\InboxResource\Pages;

use EkAndreas\Resonator\Actions\SendReply;
use EkAndreas\Resonator\Filament\Resources\InboxResource;
use EkAndreas\Resonator\Models\ResonatorFolder;
use EkAndreas\Resonator\Models\ResonatorSnippet;
use EkAndreas\Resonator\Models\ResonatorSpamFilter;
use EkAndreas\Resonator\Models\ResonatorThread;
use Filament\Actions;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewThread extends ViewRecord
{
    protected static string $resource = InboxResource::class;

    protected static string $view = 'resonator::filament.pages.view-thread';

    public function mount(int|string $record): void
    {
        parent::mount($record);

        // Mark as read when viewing
        $this->record->markAsRead();
    }

    public function getRecord(): ResonatorThread
    {
        return $this->record;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('reply')
                ->label(__('resonator::resonator.actions.reply'))
                ->icon('heroicon-o-arrow-uturn-left')
                ->form([
                    Forms\Components\Select::make('snippet_id')
                        ->label(__('resonator::resonator.labels.snippet'))
                        ->options(ResonatorSnippet::active()->ordered()->pluck('name', 'id'))
                        ->placeholder(__('resonator::resonator.placeholders.select_snippet'))
                        ->live()
                        ->afterStateUpdated(function ($state, Forms\Set $set) {
                            if ($state) {
                                $snippet = ResonatorSnippet::find($state);
                                if ($snippet) {
                                    $set('body', $snippet->body);
                                }
                            }
                        }),

                    Forms\Components\RichEditor::make('body')
                        ->label(__('resonator::resonator.labels.message'))
                        ->required()
                        ->toolbarButtons([
                            'bold',
                            'italic',
                            'underline',
                            'link',
                            'bulletList',
                            'orderedList',
                        ]),
                ])
                ->action(function (array $data) {
                    (new SendReply)->execute(
                        thread: $this->record,
                        body: $data['body']
                    );

                    Notification::make()
                        ->title(__('resonator::resonator.messages.email_sent'))
                        ->success()
                        ->send();

                    return redirect(InboxResource::getUrl('index'));
                }),

            Actions\Action::make('move_to_folder')
                ->label(__('resonator::resonator.actions.move_to_folder'))
                ->icon('heroicon-o-folder')
                ->form([
                    Forms\Components\Select::make('folder_id')
                        ->label(__('resonator::resonator.labels.folder'))
                        ->options(ResonatorFolder::pluck('name', 'id'))
                        ->required()
                        ->createOptionForm([
                            Forms\Components\TextInput::make('name')
                                ->label(__('resonator::resonator.labels.name'))
                                ->required(),
                            Forms\Components\TextInput::make('slug')
                                ->label(__('resonator::resonator.labels.slug'))
                                ->required()
                                ->unique('resonator_folders', 'slug'),
                        ])
                        ->createOptionUsing(function (array $data) {
                            return ResonatorFolder::create($data)->id;
                        }),
                ])
                ->action(function (array $data) {
                    $folder = ResonatorFolder::find($data['folder_id']);
                    if ($folder) {
                        $this->record->moveToFolder($folder);
                        $this->record->markAsHandled();

                        Notification::make()
                            ->title(__('resonator::resonator.messages.moved_to_folder', ['folder' => $folder->name]))
                            ->success()
                            ->send();
                    }

                    return redirect(InboxResource::getUrl('index'));
                }),

            Actions\Action::make('archive')
                ->label(__('resonator::resonator.actions.archive'))
                ->icon('heroicon-o-archive-box')
                ->action(function () {
                    $this->record->archive();

                    Notification::make()
                        ->title(__('resonator::resonator.messages.archived'))
                        ->success()
                        ->send();

                    return redirect(InboxResource::getUrl('index'));
                }),

            Actions\Action::make('spam')
                ->label(__('resonator::resonator.actions.move_to_spam'))
                ->icon('heroicon-o-shield-exclamation')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading(__('resonator::resonator.confirmations.spam_title'))
                ->modalDescription(__('resonator::resonator.confirmations.spam_description'))
                ->action(function () {
                    $this->record->moveToSpam();
                    ResonatorSpamFilter::addToSpamList($this->record->participant_email);

                    Notification::make()
                        ->title(__('resonator::resonator.messages.moved_to_spam'))
                        ->success()
                        ->send();

                    return redirect(InboxResource::getUrl('index'));
                }),

            Actions\Action::make('trash')
                ->label(__('resonator::resonator.actions.move_to_trash'))
                ->icon('heroicon-o-trash')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading(__('resonator::resonator.confirmations.trash_title'))
                ->modalDescription(__('resonator::resonator.confirmations.trash_description'))
                ->action(function () {
                    $this->record->moveToTrash();

                    Notification::make()
                        ->title(__('resonator::resonator.messages.moved_to_trash'))
                        ->success()
                        ->send();

                    return redirect(InboxResource::getUrl('index'));
                }),
        ];
    }
}
