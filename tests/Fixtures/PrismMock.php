<?php

declare(strict_types=1);

namespace EkAndreas\Resonator\Tests\Fixtures;

use Mockery;
use Prism\Prism\Prism;

class PrismMock
{
    /**
     * Mock spam detection response
     */
    public static function spamDetection(bool $isSpam = false, ?string $reason = null): void
    {
        $response = new class($isSpam, $reason) {
            public array $structured;

            public function __construct(bool $isSpam, ?string $reason)
            {
                $this->structured = [
                    'is_spam' => $isSpam,
                    'reason' => $reason ?? ($isSpam ? 'Marketing newsletter' : 'Personal inquiry'),
                ];
            }
        };

        $generator = Mockery::mock('overload:' . \Prism\Prism\Structured\Generator::class);
        $generator->shouldReceive('using')->andReturnSelf();
        $generator->shouldReceive('withSchema')->andReturnSelf();
        $generator->shouldReceive('withSystemPrompt')->andReturnSelf();
        $generator->shouldReceive('withPrompt')->andReturnSelf();
        $generator->shouldReceive('generate')->andReturn($response);

        $prism = Mockery::mock('alias:' . Prism::class);
        $prism->shouldReceive('structured')->andReturn($generator);
    }

    /**
     * Mock contact enrichment response
     */
    public static function contactEnrichment(?string $name = null, ?string $phone = null, ?string $company = null): void
    {
        $response = new class($name, $phone, $company) {
            public array $structured;

            public function __construct(?string $name, ?string $phone, ?string $company)
            {
                $this->structured = array_filter([
                    'name' => $name,
                    'phone' => $phone,
                    'company' => $company,
                ]);
            }
        };

        $generator = Mockery::mock('overload:' . \Prism\Prism\Structured\Generator::class);
        $generator->shouldReceive('using')->andReturnSelf();
        $generator->shouldReceive('withSchema')->andReturnSelf();
        $generator->shouldReceive('withSystemPrompt')->andReturnSelf();
        $generator->shouldReceive('withPrompt')->andReturnSelf();
        $generator->shouldReceive('generate')->andReturn($response);

        $prism = Mockery::mock('alias:' . Prism::class);
        $prism->shouldReceive('structured')->andReturn($generator);
    }

    /**
     * Mock Prism to throw an exception
     */
    public static function error(string $message = 'AI service unavailable'): void
    {
        $generator = Mockery::mock('overload:' . \Prism\Prism\Structured\Generator::class);
        $generator->shouldReceive('using')->andReturnSelf();
        $generator->shouldReceive('withSchema')->andReturnSelf();
        $generator->shouldReceive('withSystemPrompt')->andReturnSelf();
        $generator->shouldReceive('withPrompt')->andReturnSelf();
        $generator->shouldReceive('generate')->andThrow(new \Exception($message));

        $prism = Mockery::mock('alias:' . Prism::class);
        $prism->shouldReceive('structured')->andReturn($generator);
    }
}
