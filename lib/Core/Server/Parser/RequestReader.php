<?php

namespace Phpactor\LanguageServer\Core\Server\Parser;

use Phpactor\LanguageServer\Core\Rpc\RawMessage;

interface RequestReader
{
    public function readMessage(): ?RawMessage;
}
