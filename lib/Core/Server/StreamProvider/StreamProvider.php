<?php

namespace Phpactor\LanguageServer\Core\Server\StreamProvider;

interface StreamProvider
{
	public function accept(): ?Connection;

	public function close(): void;
}
