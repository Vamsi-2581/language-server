<?php

namespace Phpactor\LanguageServer\Core\Handler;

use Phpactor\LanguageServer\Core\Rpc\Message;
use Phpactor\LanguageServer\Core\Rpc\ResponseMessage;

interface MethodRunner
{
    public function dispatch(Message $request): ?ResponseMessage;

    /**
     * @param int|string $id
     */
    public function cancelRequest($id): void;
}
