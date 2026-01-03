<?php

declare(strict_types=1);

use EkAndreas\Resonator\Models\ResonatorSpamFilter;

describe('ResonatorSpamFilter', function () {
    it('can add email to spam list', function () {
        $filter = ResonatorSpamFilter::addToSpamList('spam@example.com', 'Known spammer');

        expect($filter)
            ->email->toBe('spam@example.com')
            ->reason->toBe('Known spammer');
    });

    it('normalizes email to lowercase', function () {
        ResonatorSpamFilter::addToSpamList('SPAM@EXAMPLE.COM');

        expect(ResonatorSpamFilter::where('email', 'spam@example.com')->exists())->toBeTrue();
    });

    it('can check if email is spam', function () {
        ResonatorSpamFilter::addToSpamList('blocked@example.com');

        expect(ResonatorSpamFilter::isSpam('blocked@example.com'))->toBeTrue()
            ->and(ResonatorSpamFilter::isSpam('BLOCKED@EXAMPLE.COM'))->toBeTrue()
            ->and(ResonatorSpamFilter::isSpam('allowed@example.com'))->toBeFalse();
    });

    it('can remove email from spam list', function () {
        ResonatorSpamFilter::addToSpamList('temporary@example.com');

        expect(ResonatorSpamFilter::isSpam('temporary@example.com'))->toBeTrue();

        ResonatorSpamFilter::removeFromSpamList('temporary@example.com');

        expect(ResonatorSpamFilter::isSpam('temporary@example.com'))->toBeFalse();
    });

    it('does not duplicate emails', function () {
        ResonatorSpamFilter::addToSpamList('duplicate@example.com', 'First');
        ResonatorSpamFilter::addToSpamList('duplicate@example.com', 'Second');

        expect(ResonatorSpamFilter::where('email', 'duplicate@example.com')->count())->toBe(1);
    });
});
