<?php

declare(strict_types=1);

namespace PhpLlm\LlmChain\Tests\Platform\Message;

use PhpLlm\LlmChain\Platform\Message\AssistantMessage;
use PhpLlm\LlmChain\Platform\Message\Content\ContentInterface;
use PhpLlm\LlmChain\Platform\Message\Content\ImageUrl;
use PhpLlm\LlmChain\Platform\Message\Content\Text;
use PhpLlm\LlmChain\Platform\Message\Message;
use PhpLlm\LlmChain\Platform\Message\Role;
use PhpLlm\LlmChain\Platform\Message\SystemMessage;
use PhpLlm\LlmChain\Platform\Message\ToolCallMessage;
use PhpLlm\LlmChain\Platform\Message\UserMessage;
use PhpLlm\LlmChain\Platform\Response\ToolCall;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Message::class)]
#[UsesClass(UserMessage::class)]
#[UsesClass(SystemMessage::class)]
#[UsesClass(AssistantMessage::class)]
#[UsesClass(ToolCallMessage::class)]
#[UsesClass(Role::class)]
#[UsesClass(ToolCall::class)]
#[UsesClass(ImageUrl::class)]
#[UsesClass(Text::class)]
#[Small]
final class MessageTest extends TestCase
{
    #[Test]
    public function createSystemMessageWithString(): void
    {
        $message = Message::forSystem('My amazing system prompt.');

        self::assertSame('My amazing system prompt.', $message->content);
    }

    #[Test]
    public function createSystemMessageWithStringable(): void
    {
        $message = Message::forSystem(new class implements \Stringable {
            public function __toString(): string
            {
                return 'My amazing system prompt.';
            }
        });

        self::assertSame('My amazing system prompt.', $message->content);
    }

    #[Test]
    public function createAssistantMessage(): void
    {
        $message = Message::ofAssistant('It is time to sleep.');

        self::assertSame('It is time to sleep.', $message->content);
    }

    #[Test]
    public function createAssistantMessageWithToolCalls(): void
    {
        $toolCalls = [
            new ToolCall('call_123456', 'my_tool', ['foo' => 'bar']),
            new ToolCall('call_456789', 'my_faster_tool'),
        ];
        $message = Message::ofAssistant(toolCalls: $toolCalls);

        self::assertCount(2, $message->toolCalls);
        self::assertTrue($message->hasToolCalls());
    }

    #[Test]
    public function createUserMessageWithString(): void
    {
        $message = Message::ofUser('Hi, my name is John.');

        self::assertCount(1, $message->content);
        self::assertInstanceOf(Text::class, $message->content[0]);
        self::assertSame('Hi, my name is John.', $message->content[0]->text);
    }

    #[Test]
    public function createUserMessageWithStringable(): void
    {
        $message = Message::ofUser(new class implements \Stringable {
            public function __toString(): string
            {
                return 'Hi, my name is John.';
            }
        });

        self::assertCount(1, $message->content);
        self::assertInstanceOf(Text::class, $message->content[0]);
        self::assertSame('Hi, my name is John.', $message->content[0]->text);
    }

    #[Test]
    public function createUserMessageContentInterfaceImplementingStringable(): void
    {
        $message = Message::ofUser(new class implements ContentInterface, \Stringable {
            public function __toString(): string
            {
                return 'I am a ContentInterface!';
            }
        });

        self::assertCount(1, $message->content);
        self::assertInstanceOf(ContentInterface::class, $message->content[0]);
    }

    #[Test]
    public function createUserMessageWithTextContent(): void
    {
        $text = new Text('Hi, my name is John.');
        $message = Message::ofUser($text);

        self::assertSame([$text], $message->content);
    }

    #[Test]
    public function createUserMessageWithImages(): void
    {
        $message = Message::ofUser(
            new Text('Hi, my name is John.'),
            new ImageUrl('http://images.local/my-image.png'),
            'The following image is a joke.',
            new ImageUrl('http://images.local/my-image2.png'),
        );

        self::assertCount(4, $message->content);
    }

    #[Test]
    public function createToolCallMessage(): void
    {
        $toolCall = new ToolCall('call_123456', 'my_tool', ['foo' => 'bar']);
        $message = Message::ofToolCall($toolCall, 'Foo bar.');

        self::assertSame('Foo bar.', $message->content);
        self::assertSame($toolCall, $message->toolCall);
    }
}
