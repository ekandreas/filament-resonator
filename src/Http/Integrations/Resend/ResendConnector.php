<?php

declare(strict_types=1);

namespace EkAndreas\Resonator\Http\Integrations\Resend;

use Saloon\Http\Auth\TokenAuthenticator;
use Saloon\Http\Connector;

class ResendConnector extends Connector
{
    public function resolveBaseUrl(): string
    {
        return 'https://api.resend.com';
    }

    protected function defaultAuth(): TokenAuthenticator
    {
        return new TokenAuthenticator(
            config('resonator.resend.key')
        );
    }

    protected function defaultHeaders(): array
    {
        return [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];
    }
}
