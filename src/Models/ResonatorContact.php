<?php

declare(strict_types=1);

namespace EkAndreas\Resonator\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class ResonatorContact extends Model
{
    protected $table = 'resonator_contacts';

    protected $fillable = [
        'email',
        'name',
        'phone',
        'company',
        'unsubscribe_token',
        'unsubscribed_at',
    ];

    protected function casts(): array
    {
        return [
            'unsubscribed_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (ResonatorContact $contact) {
            if (! $contact->unsubscribe_token) {
                $contact->unsubscribe_token = Str::random(64);
            }
        });
    }

    public function threads(): BelongsToMany
    {
        return $this->belongsToMany(ResonatorThread::class, 'resonator_thread_contact', 'contact_id', 'thread_id')
            ->withTimestamps();
    }

    public static function findOrCreateByEmail(string $email, array $attributes = []): self
    {
        $email = strtolower(trim($email));

        $contact = static::where('email', $email)->first();

        if ($contact) {
            // Only update empty fields
            $updateData = [];
            foreach (['name', 'phone', 'company'] as $field) {
                if (empty($contact->$field) && ! empty($attributes[$field])) {
                    $updateData[$field] = $attributes[$field];
                }
            }
            if (! empty($updateData)) {
                $contact->update($updateData);
            }

            return $contact;
        }

        // Generate name from email if not provided
        $name = $attributes['name'] ?? null;
        if (! $name) {
            $localPart = Str::before($email, '@');
            $name = Str::title(str_replace(['.', '_', '-'], ' ', $localPart));
        }

        return static::create(array_merge($attributes, [
            'email' => $email,
            'name' => $name,
        ]));
    }

    public function isUnsubscribed(): bool
    {
        return $this->unsubscribed_at !== null;
    }

    public function unsubscribe(): void
    {
        $this->update(['unsubscribed_at' => now()]);
    }

    public function resubscribe(): void
    {
        $this->update(['unsubscribed_at' => null]);
    }
}
