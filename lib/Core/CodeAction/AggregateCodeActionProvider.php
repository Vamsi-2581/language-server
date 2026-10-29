<?php

namespace Phpactor\LanguageServer\Core\CodeAction;

use Amp\Cancellation;
use Phpactor\LanguageServerProtocol\Range;
use Phpactor\LanguageServerProtocol\TextDocumentItem;
use function Amp\delay;

class AggregateCodeActionProvider implements CodeActionProvider
{
    /**
     * @var CodeActionProvider[]
     */
    private array $providers;

    public function __construct(CodeActionProvider ...$providers)
    {
        $this->providers = $providers;
    }

    /**
     * {@inheritDoc}
     */
    public function provideActionsFor(TextDocumentItem $textDocument, Range $range, ?Cancellation $cancel = null): array
    {
        $actions = [];
        foreach ($this->providers as $provider) {
            $actions = array_merge($actions, $provider->provideActionsFor($textDocument, $range, $cancel));

            delay(0);

            $cancel?->throwIfRequested();
        }

        return $actions;
    }

    /**
     * {@inheritDoc}
     */
    public function kinds(): array
    {
        return array_values(
            array_reduce(
                $this->providers,
                function (array $kinds, CodeActionProvider $provider): array {
                    return array_merge(
                        $kinds,
                        (array)array_combine($provider->kinds(), $provider->kinds())
                    );
                },
                []
            )
        );
    }

    public function describe(): string
    {
        return sprintf('aggregate code action proivder with %s providers', count($this->providers));
    }
}
