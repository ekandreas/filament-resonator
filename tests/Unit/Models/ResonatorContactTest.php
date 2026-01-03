<?php

declare(strict_types=1);

use EkAndreas\Resonator\Models\ResonatorContact;

describe('ResonatorContact', function () {
    it('can create a contact', function () {
        $contact = ResonatorContact::create([
            'email' => 'john@example.com',
            'name' => 'John Doe',
            'phone' => '+46 70 123 4567',
            'company' => 'Acme Corp',
        ]);

        expect($contact)
            ->email->toBe('john@example.com')
            ->name->toBe('John Doe')
            ->phone->toBe('+46 70 123 4567')
            ->company->toBe('Acme Corp');
    });

    it('generates unsubscribe token on create', function () {
        $contact = ResonatorContact::create([
            'email' => 'test@example.com',
        ]);

        expect($contact->unsubscribe_token)
            ->not->toBeNull()
            ->toHaveLength(64);
    });

    it('can find or create by email', function () {
        $contact1 = ResonatorContact::findOrCreateByEmail('new@example.com', ['name' => 'New User']);
        $contact2 = ResonatorContact::findOrCreateByEmail('new@example.com', ['name' => 'Different Name']);

        expect($contact1->id)->toBe($contact2->id)
            ->and($contact2->name)->toBe('New User'); // Original name preserved
    });

    it('updates empty fields when finding existing', function () {
        $contact = ResonatorContact::create([
            'email' => 'partial@example.com',
            'name' => 'Existing Name',
        ]);

        ResonatorContact::findOrCreateByEmail('partial@example.com', [
            'name' => 'New Name', // Should be ignored
            'phone' => '+46 70 999 9999', // Should be added
        ]);

        $contact->refresh();

        expect($contact->name)->toBe('Existing Name')
            ->and($contact->phone)->toBe('+46 70 999 9999');
    });

    it('generates name from email if not provided', function () {
        $contact = ResonatorContact::findOrCreateByEmail('john.doe@example.com');

        expect($contact->name)->toBe('John Doe');
    });

    it('can unsubscribe and resubscribe', function () {
        $contact = ResonatorContact::create(['email' => 'sub@example.com']);

        expect($contact->isUnsubscribed())->toBeFalse();

        $contact->unsubscribe();

        expect($contact->fresh()->isUnsubscribed())->toBeTrue();

        $contact->resubscribe();

        expect($contact->fresh()->isUnsubscribed())->toBeFalse();
    });

    it('normalizes email to lowercase', function () {
        $contact = ResonatorContact::findOrCreateByEmail('UPPERCASE@EXAMPLE.COM');

        expect($contact->email)->toBe('uppercase@example.com');
    });
});
