<?php

namespace Phpactor\LanguageServer\Tests\Unit\Core\Server\RpcClient;

use Amp\PHPUnit\AsyncTestCase;
use Phpactor\LanguageServer\Core\Server\ResponseWatcher\TestResponseWatcher;
use Phpactor\LanguageServer\Core\Server\RpcClient\JsonRpcClient;
use Phpactor\LanguageServer\Core\Server\Transmitter\TestMessageTransmitter;
use function Amp\async;
use function Amp\delay;

class JsonRpcClientTest extends AsyncTestCase
{
    public function testRequestIsSerializable(): void
    {
        $watcher = new TestResponseWatcher();
        $transmitter = new TestMessageTransmitter();
        $client = new JsonRpcClient($transmitter, $watcher);

        // request() waits for the response, so it has to run in a fiber: the
        // request is transmitted before it suspends.
        async(fn () => $client->request('foobar', []));
        delay(0);

        // request is serializable
        $request = $transmitter->shiftRequest();

        $encoded = json_encode($request);
        self::assertIsString($encoded);
    }
}
