<?php

declare(strict_types=1);

namespace EkAndreas\Resonator\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ResonatorSnippet extends Model
{
    protected $table = 'resonator_snippets';

    protected $fillable = [
        'name',
        'shortcut',
        'subject',
        'body',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    public static function findByShortcut(string $shortcut): ?self
    {
        return static::active()->where('shortcut', $shortcut)->first();
    }
}
