<?php

declare(strict_types=1);

namespace PhpLlm\LlmChain\Platform\Bridge\Albert;

use PhpLlm\LlmChain\Platform\Bridge\OpenAI\Embeddings\ResponseConverter as EmbeddingsResponseConverter;
use PhpLlm\LlmChain\Platform\Bridge\OpenAI\GPT\ResponseConverter as GPTResponseConverter;
use PhpLlm\LlmChain\Platform\Contract;
use PhpLlm\LlmChain\Platform\Platform;
use Symfony\Component\HttpClient\EventSourceHttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class PlatformFactory
{
    /**
     * Creates a Platform instance for Albert API (OpenAI-compatible).
     */
    public static function create(
        string $apiKey,
        string $albertUrl,
        ?HttpClientInterface $httpClient = null,
    ): Platform {
        $httpClient = $httpClient instanceof EventSourceHttpClient ? $httpClient : new EventSourceHttpClient($httpClient);

        // Configure base URL for Albert API
        $baseUrl = rtrim($albertUrl, '/').'/v1/';

        // Create Albert-specific model clients with custom base URL
        $gptClient = new GPTModelClient($httpClient, $apiKey, $baseUrl);
        $embeddingsClient = new EmbeddingsModelClient($httpClient, $apiKey, $baseUrl);

        return new Platform(
            [
                $gptClient,
                $embeddingsClient,
            ],
            [
                new GPTResponseConverter(),
                new EmbeddingsResponseConverter(),
            ],
            Contract::create(),
        );
    }
}
