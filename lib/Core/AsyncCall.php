<?php

namespace Phpactor\LanguageServer\Core;

use Amp\DeferredFuture;
use Amp\Future;
use Fiber;
use Throwable;

/**
 * Run a closure in a new fiber which is started immediately, i.e. it runs
 * until its first suspension point before this method returns.
 *
 * Amp 3's async() only queues the fiber for the next event loop tick, while
 * Amp 2's call() and asyncCall() both started the coroutine immediately. The
 * server relies on that ordering for the side effects it performs before it
 * suspends, so this shim preserves it.
 *
 * TODO: Remove in favour of async() once the callers no longer depend on the
 *       ordering.
 */
final class AsyncCall
{
    /**
     * @template T
     *
     * @param callable():T $closure
     *
     * @return Future<T>
     */
    public static function start(callable $closure): Future
    {
        $deferred = new DeferredFuture();

        $fiber = new Fiber(function () use ($closure, $deferred): void {
            try {
                $deferred->complete($closure());
            } catch (Throwable $error) {
                $deferred->error($error);
            }
        });

        $fiber->start();

        return $deferred->getFuture();
    }
}
