<?php

declare(strict_types=1);

namespace EkAndreas\Resonator\Http\Integrations\Resend\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class ListReceivedEmailsRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected int $limit = 100,
        protected ?string $after = null,
        protected ?string $before = null
    ) {}

    public function resolveEndpoint(): string
    {
        return '/emails/receiving';
    }

    protected function defaultQuery(): array
    {
        $query = ['limit' => $this->limit];

        if ($this->after) {
            $query['after'] = $this->after;
        }

        if ($this->before) {
            $query['before'] = $this->before;
        }

        return $query;
    }
}
