<?php

namespace Phpactor\LanguageServer\Core\Middleware;

use Phpactor\LanguageServer\Core\Rpc\Message;
use Phpactor\LanguageServer\Core\Rpc\ResponseMessage;

interface Middleware
{
	public function process(Message $request, RequestHandler $handler): ?ResponseMessage;
}
