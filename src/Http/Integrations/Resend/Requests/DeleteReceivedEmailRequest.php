<?php

declare(strict_types=1);

namespace EkAndreas\Resonator\Http\Integrations\Resend\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class DeleteReceivedEmailRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        protected string $emailId
    ) {}

    public function resolveEndpoint(): string
    {
        return "/emails/receiving/{$this->emailId}";
    }
}
