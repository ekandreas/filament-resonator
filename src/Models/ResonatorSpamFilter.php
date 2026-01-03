<?php

declare(strict_types=1);

namespace EkAndreas\Resonator\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResonatorSpamFilter extends Model
{
    protected $table = 'resonator_spam_filters';

    protected $fillable = [
        'email',
        'reason',
        'created_by',
    ];

    public function createdBy(): BelongsTo
    {
        $userModel = config('resonator.user_model', \App\Models\User::class);

        return $this->belongsTo($userModel, 'created_by');
    }

    public static function isSpam(string $email): bool
    {
        return static::where('email', strtolower($email))->exists();
    }

    public static function addToSpamList(string $email, ?string $reason = null, $userId = null): self
    {
        return static::firstOrCreate(
            ['email' => strtolower($email)],
            [
                'reason' => $reason,
                'created_by' => $userId ?? auth()->id(),
            ]
        );
    }

    public static function removeFromSpamList(string $email): bool
    {
        return static::where('email', strtolower($email))->delete() > 0;
    }
}
