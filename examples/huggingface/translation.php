<?php

use PhpLlm\LlmChain\Platform\Bridge\HuggingFace\PlatformFactory;
use PhpLlm\LlmChain\Platform\Bridge\HuggingFace\Task;
use PhpLlm\LlmChain\Platform\Model;
use Symfony\Component\Dotenv\Dotenv;

require_once dirname(__DIR__, 2).'/vendor/autoload.php';
(new Dotenv())->loadEnv(dirname(__DIR__, 2).'/.env');

if (!$_ENV['HUGGINGFACE_KEY']) {
    echo 'Please set the HUGGINGFACE_KEY environment variable.'.\PHP_EOL;
    exit(1);
}

$platform = PlatformFactory::create($_ENV['HUGGINGFACE_KEY']);
$model = new Model('facebook/mbart-large-50-many-to-many-mmt');

$response = $platform->request($model, 'Меня зовут Вольфганг и я живу в Берлине', [
    'task' => Task::TRANSLATION,
    'src_lang' => 'ru',
    'tgt_lang' => 'en',
]);

echo $response->asText().\PHP_EOL;
