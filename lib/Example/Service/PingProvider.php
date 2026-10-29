<?php

namespace Phpactor\LanguageServer\Example\Service;

use Amp\Cancellation;
use Amp\CancelledException;
use Phpactor\LanguageServer\Core\Server\ClientApi;
use Phpactor\LanguageServer\Core\Service\ServiceProvider;
use function Amp\delay;

/**
 * Example service which shows a "ping" message every second.
 */
class PingProvider implements ServiceProvider
{
    public function __construct(private ClientApi $client)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function services(): array
    {
        return [
            'ping'
        ];
    }

    public function ping(?Cancellation $cancel = null): void
    {
        while (true) {
            try {
                $cancel?->throwIfRequested();
            } catch (CancelledException $cancelled) {
                break;
            }
            delay(1);
            $this->client->window()->showMessage()->info('ping');
        }
    }
}
