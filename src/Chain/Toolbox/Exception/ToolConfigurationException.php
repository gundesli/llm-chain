<?php

declare(strict_types=1);

namespace PhpLlm\LlmChain\Chain\Toolbox\Exception;

use PhpLlm\LlmChain\Chain\Exception\InvalidArgumentException;

/**
 * @author Christopher Hertel <mail@christopher-hertel.de>
 */
final class ToolConfigurationException extends InvalidArgumentException implements ExceptionInterface
{
    public static function invalidMethod(string $toolClass, string $methodName, \ReflectionException $previous): self
    {
        return new self(\sprintf('Method "%s" not found in tool "%s".', $methodName, $toolClass), previous: $previous);
    }
}
