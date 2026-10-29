<?php

namespace Phpactor\LanguageServer\Tests\Unit\Core\Handler;

use Amp\Cancellation;
use Amp\CancelledException;
use Amp\PHPUnit\AsyncTestCase;
use Phpactor\LanguageServer\Core\Handler\ClosureHandler;
use Phpactor\LanguageServer\Core\Handler\HandlerMethodRunner;
use Phpactor\LanguageServer\Core\Handler\Handlers;
use Phpactor\LanguageServer\Core\Rpc\NotificationMessage;
use Phpactor\LanguageServer\Core\Rpc\RequestMessage;
use Phpactor\LanguageServer\Core\Rpc\ResponseMessage;
use RuntimeException;
use function Amp\async;
use function Amp\delay;

class HandlerMethodRunnerTest extends AsyncTestCase
{
    public function testExceptionIfNotRequestOrNotification(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Message must either be a ');

        $runner = $this->createRunner([
            new ClosureHandler('foobar', function () {
                return 'foobar';
            })
        ]);
        $runner->dispatch(
            new ResponseMessage(1, 'foobar')
        );
    }

    public function testReturnsNullIfMessageIsANotification(): void
    {
        $dispatcher = $this->createRunner([
            new ClosureHandler('foobar', function (): void {
            })
        ]);

        $response = $dispatcher->dispatch(
            new NotificationMessage('foobar', [])
        );

        self::assertNull($response);
    }

    public function testReturnsValueFromHandler(): void
    {
        $dispatcher = $this->createRunner([
            new ClosureHandler('foobar', function (string $bar) {
                return 'foobar: '.$bar;
            })
        ]);

        $response = $dispatcher->dispatch(
            new RequestMessage(2, 'foobar', ['bar' => 'foo'])
        );

        self::assertInstanceOf(ResponseMessage::class, $response);
        self::assertEquals('foobar: foo', $response->result);
        self::assertEquals(2, $response->id);
    }

    public function testToleratesTryingToCancelNonRunningRequest(): void
    {
        $dispatcher = $this->createRunner([
            new ClosureHandler('foobar', function (): void {
                delay(0.01);
            })
        ]);

        async(fn () => $dispatcher->dispatch(
            new RequestMessage(1, 'foobar', ['bar' => 'foo'])
        ));

        delay(0.005);

        $dispatcher->cancelRequest(2);
        $this->addToAssertionCount(1);
    }

    public function testCancelsRequest(): void
    {
        $this->expectException(CancelledException::class);

        $dispatcher = $this->createRunner([
            new ClosureHandler('foobar', function (string $bar, Cancellation $token): void {
                delay(0.1);
                $token->throwIfRequested();
            })
        ]);

        $response = async(fn () => $dispatcher->dispatch(
            new RequestMessage(1, 'foobar', ['bar' => 'foo'])
        ));

        delay(0);
        $dispatcher->cancelRequest(1);

        $response->await();
    }

    private function createRunner(array $handlers): HandlerMethodRunner
    {
        return new HandlerMethodRunner(
            new Handlers(...$handlers)
        );
    }
}
