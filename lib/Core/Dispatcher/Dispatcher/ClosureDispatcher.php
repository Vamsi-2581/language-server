<?php

namespace Phpactor\LanguageServer\Core\Dispatcher\Dispatcher;

use Closure;
use Phpactor\LanguageServer\Core\Dispatcher\Dispatcher;
use Phpactor\LanguageServer\Core\Rpc\Message;
use Phpactor\LanguageServer\Core\Rpc\ResponseMessage;

final class ClosureDispatcher implements Dispatcher
{
	/**
	 * @param Closure(Message): ?ResponseMessage $closure
	 */
	public function __construct(private Closure $closure) {}

	/**
	 * {@inheritDoc}
	 */
	public function dispatch(Message $request): ?ResponseMessage
	{
		/** @phpstan-ignore-next-line */
		return $this->closure->__invoke($request);
	}
}
