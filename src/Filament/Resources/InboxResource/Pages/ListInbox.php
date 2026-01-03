<?php

declare(strict_types=1);

namespace EkAndreas\Resonator\Filament\Resources\InboxResource\Pages;

use EkAndreas\Resonator\Filament\Resources\InboxResource;
use EkAndreas\Resonator\Models\ResonatorContact;
use EkAndreas\Resonator\Models\ResonatorEmail;
use EkAndreas\Resonator\Models\ResonatorFolder;
use EkAndreas\Resonator\Models\ResonatorThread;
use Filament\Actions;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Mail;

class ListInbox extends ListRecords
{
    protected static string $resource = InboxResource::class;

    public function mount(): void
    {
        parent::mount();

        // Set default filter to inbox folder if no filter is set
        if (empty($this->tableFilters['folder_id']['value'])) {
            $inbox = ResonatorFolder::inbox();
            if ($inbox) {
                $this->tableFilters['folder_id']['value'] = $inbox->id;
            }
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('compose')
                ->label(__('resonator::resonator.actions.compose'))
                ->icon('heroicon-o-pencil-square')
                ->form([
                    Forms\Components\Select::make('to')
                        ->label(__('resonator::resonator.labels.to'))
                        ->multiple()
                        ->searchable()
                        ->getSearchResultsUsing(function (string $search) {
                            return ResonatorContact::where('email', 'like', "%{$search}%")
                                ->orWhere('name', 'like', "%{$search}%")
                                ->limit(10)
                                ->pluck('email', 'email');
                        })
                        ->required()
                        ->placeholder(__('resonator::resonator.placeholders.select_recipients')),

                    Forms\Components\TextInput::make('subject')
                        ->label(__('resonator::resonator.labels.subject'))
                        ->required()
                        ->maxLength(255)
                        ->placeholder(__('resonator::resonator.placeholders.enter_subject')),

                    Forms\Components\RichEditor::make('body')
                        ->label(__('resonator::resonator.labels.body'))
                        ->required()
                        ->toolbarButtons([
                            'bold',
                            'italic',
                            'underline',
                            'link',
                            'bulletList',
                            'orderedList',
                        ])
                        ->placeholder(__('resonator::resonator.placeholders.enter_message')),

                    Forms\Components\FileUpload::make('attachments')
                        ->label(__('resonator::resonator.labels.attachments'))
                        ->multiple()
                        ->maxFiles(5)
                        ->maxSize(10240)
                        ->disk('local')
                        ->directory('resonator-attachments')
                        ->visibility('private'),
                ])
                ->action(function (array $data) {
                    $fromAddress = config('resonator.mail.from_address');
                    $fromName = config('resonator.mail.from_name');

                    // Build signature
                    $user = auth()->user();
                    $signature = "<br><br>// " . __('resonator::resonator.signature.regards') . ",<br>{$user->name}";

                    $fullBody = $data['body'] . $signature;

                    foreach ($data['to'] as $recipient) {
                        // Send email
                        Mail::html($fullBody, function ($message) use ($recipient, $data, $fromAddress, $fromName) {
                            $message->from($fromAddress, $fromName)
                                ->to($recipient)
                                ->subject($data['subject']);

                            if (! empty($data['attachments'])) {
                                foreach ($data['attachments'] as $path) {
                                    $message->attach(storage_path('app/' . $path));
                                }
                            }
                        });

                        // Find or create thread
                        $inbox = ResonatorFolder::inbox();
                        $thread = ResonatorThread::where('participant_email', $recipient)
                            ->where('subject', $data['subject'])
                            ->first();

                        if (! $thread) {
                            $thread = ResonatorThread::create([
                                'folder_id' => ResonatorFolder::sent()?->id ?? $inbox->id,
                                'subject' => $data['subject'],
                                'participant_email' => $recipient,
                                'is_read' => true,
                                'last_message_at' => now(),
                            ]);
                        }

                        // Create email record
                        ResonatorEmail::create([
                            'thread_id' => $thread->id,
                            'is_inbound' => false,
                            'from_email' => $fromAddress,
                            'from_name' => $fromName,
                            'to' => [$recipient],
                            'subject' => $data['subject'],
                            'html' => $fullBody,
                            'text' => strip_tags($fullBody),
                            'sent_at' => now(),
                        ]);

                        $thread->update(['last_message_at' => now()]);
                    }

                    Notification::make()
                        ->title(__('resonator::resonator.messages.email_sent'))
                        ->success()
                        ->send();
                }),
        ];
    }
}
