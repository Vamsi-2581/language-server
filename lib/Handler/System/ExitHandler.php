<?php

namespace Phpactor\LanguageServer\Handler\System;

use Phpactor\LanguageServer\Adapter\Psr\NullEventDispatcher;
use Phpactor\LanguageServer\Core\Handler\Handler;
use Phpactor\LanguageServer\Core\Server\Exception\ExitSession;
use Phpactor\LanguageServer\Event\WillShutdown;
use Psr\EventDispatcher\EventDispatcherInterface;
use function Amp\delay;

class ExitHandler implements Handler
{
    public function __construct(
        private EventDispatcherInterface $eventDispatcher = new NullEventDispatcher(),
        private int $gracePeriod = 500,
    ) {
    }

    public function methods(): array
    {
        return [
            'shutdown' => 'shutdown',
            'exit' => 'exit',
        ];
    }

    public function shutdown(): void
    {
        $this->eventDispatcher->dispatch(new WillShutdown());
        delay($this->gracePeriod / 1000);
    }

    public function exit(): void
    {
        throw new ExitSession('Exit method invoked by client');
    }
}
