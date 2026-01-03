<?php

declare(strict_types=1);

use EkAndreas\Resonator\Models\ResonatorSnippet;

describe('ResonatorSnippet', function () {
    it('can create a snippet', function () {
        $snippet = ResonatorSnippet::create([
            'name' => 'Welcome',
            'shortcut' => 'welcome',
            'subject' => 'Welcome to our service',
            'body' => '<p>Thank you for reaching out!</p>',
            'is_active' => true,
        ]);

        expect($snippet)
            ->name->toBe('Welcome')
            ->shortcut->toBe('welcome')
            ->is_active->toBeTrue();
    });

    it('can find by shortcut', function () {
        ResonatorSnippet::create([
            'name' => 'Thanks',
            'shortcut' => 'thx',
            'body' => '<p>Thank you!</p>',
            'is_active' => true,
        ]);

        $found = ResonatorSnippet::findByShortcut('thx');

        expect($found)->not->toBeNull()
            ->and($found->name)->toBe('Thanks');
    });

    it('does not find inactive snippets by shortcut', function () {
        ResonatorSnippet::create([
            'name' => 'Inactive',
            'shortcut' => 'inactive',
            'body' => '<p>Body</p>',
            'is_active' => false,
        ]);

        expect(ResonatorSnippet::findByShortcut('inactive'))->toBeNull();
    });

    it('can filter active snippets', function () {
        ResonatorSnippet::create(['name' => 'Active 1', 'body' => 'Body', 'is_active' => true]);
        ResonatorSnippet::create(['name' => 'Active 2', 'body' => 'Body', 'is_active' => true]);
        ResonatorSnippet::create(['name' => 'Inactive', 'body' => 'Body', 'is_active' => false]);

        expect(ResonatorSnippet::active()->count())->toBe(2);
    });

    it('can order by sort_order', function () {
        ResonatorSnippet::create(['name' => 'Third', 'body' => 'Body', 'sort_order' => 3]);
        ResonatorSnippet::create(['name' => 'First', 'body' => 'Body', 'sort_order' => 1]);
        ResonatorSnippet::create(['name' => 'Second', 'body' => 'Body', 'sort_order' => 2]);

        $ordered = ResonatorSnippet::ordered()->pluck('name')->toArray();

        expect($ordered)->toBe(['First', 'Second', 'Third']);
    });
});
