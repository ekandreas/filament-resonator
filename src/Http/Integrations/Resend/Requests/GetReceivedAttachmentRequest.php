<?php

declare(strict_types=1);

namespace EkAndreas\Resonator\Http\Integrations\Resend\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetReceivedAttachmentRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected string $emailId,
        protected string $attachmentId
    ) {}

    public function resolveEndpoint(): string
    {
        return "/emails/receiving/{$this->emailId}/attachments/{$this->attachmentId}";
    }
}
