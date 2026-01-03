<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Thread Header --}}
        <x-filament::section>
            <x-slot name="heading">
                <div class="flex items-center gap-2">
                    @if($this->getRecord()->is_starred)
                        <x-heroicon-s-star class="w-5 h-5 text-warning-500" />
                    @endif
                    <span>{{ $this->getRecord()->subject }}</span>
                </div>
            </x-slot>
            <x-slot name="description">
                <div class="flex flex-wrap gap-4 text-sm text-gray-500 dark:text-gray-400">
                    <span>
                        <strong>{{ __('resonator::resonator.labels.from') }}:</strong>
                        {{ $this->getRecord()->participant_name ?? $this->getRecord()->participant_email }}
                        @if($this->getRecord()->participant_name)
                            &lt;{{ $this->getRecord()->participant_email }}&gt;
                        @endif
                    </span>
                    <span>
                        <strong>{{ __('resonator::resonator.labels.folder') }}:</strong>
                        {{ $this->getRecord()->folder?->name }}
                    </span>
                    <span>
                        {{ trans_choice('resonator::resonator.thread.messages_count', $this->getRecord()->emails()->count(), ['count' => $this->getRecord()->emails()->count()]) }}
                    </span>
                </div>
            </x-slot>
        </x-filament::section>

        {{-- Email Messages --}}
        @foreach($this->getRecord()->emails()->orderBy('created_at', 'desc')->get() as $index => $email)
            <x-filament::section :collapsed="$index > 0">
                <x-slot name="heading">
                    <div class="flex items-center gap-2">
                        @if($email->is_inbound)
                            <x-heroicon-o-arrow-down-tray class="w-5 h-5 text-info-500" />
                            <span class="text-info-600 dark:text-info-400">{{ __('resonator::resonator.thread.inbound') }}</span>
                        @else
                            <x-heroicon-o-arrow-up-tray class="w-5 h-5 text-success-500" />
                            <span class="text-success-600 dark:text-success-400">{{ __('resonator::resonator.thread.outbound') }}</span>
                        @endif
                        <span class="font-medium">{{ $email->from_display }}</span>
                    </div>
                </x-slot>
                <x-slot name="description">
                    <span class="text-sm text-gray-500 dark:text-gray-400">
                        {{ ($email->sent_at ?? $email->created_at)->format('j M Y H:i') }}
                    </span>
                </x-slot>

                {{-- Attachments --}}
                @if($email->attachments->count() > 0)
                    <div class="mb-4 p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('resonator::resonator.thread.attachments') }}
                        </h4>
                        <div class="flex flex-wrap gap-2">
                            @foreach($email->attachments as $attachment)
                                <a
                                    href="{{ $attachment->getDownloadUrl() }}"
                                    target="_blank"
                                    class="inline-flex items-center gap-1 px-3 py-1 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-md text-sm hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors"
                                >
                                    <x-heroicon-o-paper-clip class="w-4 h-4" />
                                    <span>{{ $attachment->filename }}</span>
                                    @if($attachment->human_readable_size)
                                        <span class="text-gray-400">({{ $attachment->human_readable_size }})</span>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Email Content --}}
                <div class="prose dark:prose-invert max-w-none">
                    @if($email->html)
                        <iframe
                            srcdoc="{{ str_replace(['<script', '</script'], ['&lt;script', '&lt;/script'], $email->html) }}"
                            sandbox="allow-same-origin"
                            class="w-full min-h-[300px] border-0 bg-white dark:bg-gray-900 rounded"
                            style="min-height: 300px;"
                        ></iframe>
                    @elseif($email->text)
                        <pre class="whitespace-pre-wrap font-sans text-sm bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">{{ $email->text }}</pre>
                    @else
                        <p class="text-gray-500 dark:text-gray-400 italic">No content</p>
                    @endif
                </div>
            </x-filament::section>
        @endforeach
    </div>
</x-filament-panels::page>
