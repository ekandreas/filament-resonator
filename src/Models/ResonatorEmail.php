<?php

declare(strict_types=1);

namespace EkAndreas\Resonator\Models;

use EkAndreas\Resonator\Jobs\DetectSpam;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ResonatorEmail extends Model
{
    protected $table = 'resonator_emails';

    protected $fillable = [
        'thread_id',
        'resend_id',
        'message_id',
        'in_reply_to',
        'references',
        'is_inbound',
        'from_email',
        'from_name',
        'to',
        'cc',
        'bcc',
        'reply_to',
        'subject',
        'html',
        'text',
        'headers',
        'sent_at',
    ];

    protected function casts(): array
    {
        return [
            'is_inbound' => 'boolean',
            'to' => 'array',
            'cc' => 'array',
            'bcc' => 'array',
            'headers' => 'array',
            'sent_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::created(function (ResonatorEmail $email) {
            if ($email->is_inbound && config('resonator.spam_detection.enabled', true)) {
                DetectSpam::dispatch($email)
                    ->delay(now()->addSeconds(config('resonator.spam_detection.delay_seconds', 5)));
            }
        });
    }

    public function thread(): BelongsTo
    {
        return $this->belongsTo(ResonatorThread::class, 'thread_id');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(ResonatorAttachment::class, 'email_id');
    }

    protected function fromDisplay(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->from_name
                ? "{$this->from_name} <{$this->from_email}>"
                : $this->from_email
        );
    }

    protected function preview(): Attribute
    {
        return Attribute::make(
            get: function () {
                $text = $this->text ?? strip_tags($this->html ?? '');
                $words = preg_split('/\s+/', trim($text));

                return implode(' ', array_slice($words, 0, 10));
            }
        );
    }

    public function hasAttachments(): bool
    {
        return $this->attachments()->exists();
    }
}
