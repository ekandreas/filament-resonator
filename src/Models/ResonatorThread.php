<?php

declare(strict_types=1);

namespace EkAndreas\Resonator\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ResonatorThread extends Model
{
    protected $table = 'resonator_threads';

    protected $fillable = [
        'folder_id',
        'subject',
        'participant_email',
        'participant_name',
        'is_starred',
        'is_read',
        'last_message_at',
        'handled_by',
        'handled_at',
    ];

    protected function casts(): array
    {
        return [
            'is_starred' => 'boolean',
            'is_read' => 'boolean',
            'last_message_at' => 'datetime',
            'handled_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::created(function (ResonatorThread $thread) {
            $thread->syncContactByEmail($thread->participant_email, [
                'name' => $thread->participant_name,
            ]);
        });
    }

    public function folder(): BelongsTo
    {
        return $this->belongsTo(ResonatorFolder::class, 'folder_id');
    }

    public function handler(): BelongsTo
    {
        $userModel = config('resonator.user_model', \App\Models\User::class);

        return $this->belongsTo($userModel, 'handled_by');
    }

    public function emails(): HasMany
    {
        return $this->hasMany(ResonatorEmail::class, 'thread_id');
    }

    public function latestEmail(): HasOne
    {
        return $this->hasOne(ResonatorEmail::class, 'thread_id')->latestOfMany();
    }

    public function firstEmail(): HasOne
    {
        return $this->hasOne(ResonatorEmail::class, 'thread_id')->oldestOfMany();
    }

    public function contacts(): BelongsToMany
    {
        return $this->belongsToMany(ResonatorContact::class, 'resonator_thread_contact', 'thread_id', 'contact_id')
            ->withTimestamps();
    }

    public function markAsHandled($user = null): void
    {
        $this->update([
            'handled_by' => $user?->id ?? auth()->id(),
            'handled_at' => now(),
        ]);
    }

    public function markAsRead(): void
    {
        if (! $this->is_read) {
            $this->update(['is_read' => true]);
        }
    }

    public function markAsUnread(): void
    {
        if ($this->is_read) {
            $this->update(['is_read' => false]);
        }
    }

    public function toggleStar(): void
    {
        $this->update(['is_starred' => ! $this->is_starred]);

        if ($this->is_starred) {
            $this->markAsHandled();
        }
    }

    public function moveToFolder(ResonatorFolder $folder): void
    {
        $this->update(['folder_id' => $folder->id]);
    }

    public function archive(): void
    {
        if ($folder = ResonatorFolder::archive()) {
            $this->moveToFolder($folder);
            $this->markAsHandled();
        }
    }

    public function moveToTrash(): void
    {
        if ($folder = ResonatorFolder::trash()) {
            $this->moveToFolder($folder);
        }
    }

    public function moveToSpam(): void
    {
        if ($folder = ResonatorFolder::spam()) {
            $this->moveToFolder($folder);
        }
    }

    public function syncContactByEmail(?string $email, array $attributes = []): ?ResonatorContact
    {
        if (! $email) {
            return null;
        }

        $contact = ResonatorContact::findOrCreateByEmail($email, $attributes);

        if ($contact && ! $this->contacts()->where('contact_id', $contact->id)->exists()) {
            $this->contacts()->attach($contact->id);
        }

        return $contact;
    }
}
