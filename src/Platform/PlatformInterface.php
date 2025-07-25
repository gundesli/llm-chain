<?php

declare(strict_types=1);

namespace PhpLlm\LlmChain\Platform;

use PhpLlm\LlmChain\Platform\Response\ResponsePromise;

/**
 * @author Christopher Hertel <mail@christopher-hertel.de>
 */
interface PlatformInterface
{
    /**
     * @param array<mixed>|string|object $input
     * @param array<string, mixed>       $options
     */
    public function request(Model $model, array|string|object $input, array $options = []): ResponsePromise;
}
