<?php

namespace Phpactor\LanguageServer\Tests\Unit\Core\Server\RpcClient;

use PHPUnit\Framework\TestCase;
use Phpactor\LanguageServer\Core\AsyncCall;
use Phpactor\LanguageServer\Core\Server\ResponseWatcher\TestResponseWatcher;
use Phpactor\LanguageServer\Core\Server\RpcClient\JsonRpcClient;
use Phpactor\LanguageServer\Core\Server\Transmitter\TestMessageTransmitter;

class JsonRpcClientTest extends TestCase
{
    public function testRequestIsSerializable(): void
    {
        $watcher = new TestResponseWatcher();
        $transmitter = new TestMessageTransmitter();
        $client = new JsonRpcClient($transmitter, $watcher);

        // request() waits for the response, so it has to run in a fiber: the
        // request is transmitted before it suspends.
        AsyncCall::start(fn () => $client->request('foobar', []));

        // request is serializable
        $request = $transmitter->shiftRequest();

        $encoded = json_encode($request);
        self::assertIsString($encoded);
    }
}
