<?php

declare(strict_types=1);

namespace EkAndreas\Resonator\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ResonatorFolder extends Model
{
    protected $table = 'resonator_folders';

    protected $fillable = [
        'name',
        'slug',
        'icon',
        'color',
        'is_system',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_system' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function threads(): HasMany
    {
        return $this->hasMany(ResonatorThread::class, 'folder_id');
    }

    public function unreadCount(): int
    {
        return $this->threads()->where('is_read', false)->count();
    }

    // Static helpers for system folders
    public static function inbox(): ?self
    {
        return static::where('slug', 'inbox')->first();
    }

    public static function sent(): ?self
    {
        return static::where('slug', 'sent')->first();
    }

    public static function archive(): ?self
    {
        return static::where('slug', 'archive')->first();
    }

    public static function spam(): ?self
    {
        return static::where('slug', 'spam')->first();
    }

    public static function trash(): ?self
    {
        return static::where('slug', 'trash')->first();
    }
}
