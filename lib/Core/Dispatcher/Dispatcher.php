<?php

namespace Phpactor\LanguageServer\Core\Dispatcher;

use Phpactor\LanguageServer\Core\Rpc\Message;
use Phpactor\LanguageServer\Core\Rpc\ResponseMessage;

interface Dispatcher
{
	public function dispatch(Message $request): ?ResponseMessage;
}
