<?php

namespace Phpactor\LanguageServer\Core\Server\Client;

use Phpactor\LanguageServerProtocol\Registration;
use Phpactor\LanguageServerProtocol\Unregistration;
use Phpactor\LanguageServer\Core\Rpc\ResponseMessage;
use Phpactor\LanguageServer\Core\Server\RpcClient;

final class ClientClient
{
    public function __construct(private RpcClient $client)
    {
    }

    public function registerCapability(Registration ...$registrations): ResponseMessage
    {
        return $this->client->request('client/registerCapability', [
            'registrations' => $registrations
        ]);
    }

    public function unregisterCapability(Unregistration ...$unregistrations): ResponseMessage
    {
        return $this->client->request('client/unregisterCapability', [
            'unregistrations' => $unregistrations
        ]);
    }
}
