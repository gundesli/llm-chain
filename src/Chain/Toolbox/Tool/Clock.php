<?php

declare(strict_types=1);

namespace PhpLlm\LlmChain\Chain\Toolbox\Tool;

use PhpLlm\LlmChain\Chain\Toolbox\Attribute\AsTool;
use Symfony\Component\Clock\Clock as SymfonyClock;
use Symfony\Component\Clock\ClockInterface;

/**
 * @author Christopher Hertel <mail@christopher-hertel.de>
 */
#[AsTool('clock', description: 'Provides the current date and time.')]
final readonly class Clock
{
    public function __construct(
        private ClockInterface $clock = new SymfonyClock(),
    ) {
    }

    public function __invoke(): string
    {
        return \sprintf(
            'Current date is %s (YYYY-MM-DD) and the time is %s (HH:MM:SS).',
            $this->clock->now()->format('Y-m-d'),
            $this->clock->now()->format('H:i:s'),
        );
    }
}
