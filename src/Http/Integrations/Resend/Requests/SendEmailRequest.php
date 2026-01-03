<?php

declare(strict_types=1);

namespace EkAndreas\Resonator\Http\Integrations\Resend\Requests;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class SendEmailRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected string $from,
        protected array $to,
        protected string $subject,
        protected ?string $html = null,
        protected ?string $text = null,
        protected ?string $replyTo = null,
        protected array $attachments = [],
        protected array $headers = []
    ) {}

    public function resolveEndpoint(): string
    {
        return '/emails';
    }

    protected function defaultBody(): array
    {
        $body = [
            'from' => $this->from,
            'to' => $this->to,
            'subject' => $this->subject,
        ];

        if ($this->html) {
            $body['html'] = $this->html;
        }

        if ($this->text) {
            $body['text'] = $this->text;
        }

        if ($this->replyTo) {
            $body['reply_to'] = $this->replyTo;
        }

        if (! empty($this->attachments)) {
            $body['attachments'] = $this->attachments;
        }

        if (! empty($this->headers)) {
            $body['headers'] = $this->headers;
        }

        return $body;
    }
}
