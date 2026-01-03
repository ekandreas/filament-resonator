<?php

declare(strict_types=1);

use EkAndreas\Resonator\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(TestCase::class, RefreshDatabase::class)->in('Feature', 'Unit');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
*/

function createUser(array $attributes = []): \Illuminate\Foundation\Auth\User
{
    return \EkAndreas\Resonator\Tests\Fixtures\User::create(array_merge([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => bcrypt('password'),
    ], $attributes));
}

function actingAsUser(array $attributes = []): \Illuminate\Foundation\Auth\User
{
    $user = createUser($attributes);
    test()->actingAs($user);

    return $user;
}
