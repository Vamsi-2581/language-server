CHANGELOG
=========

9.0
---

- Migrated from amphp/amp 2 to amphp/amp 3 (fibers instead of generators).
- Breaking: handlers, middleware, dispatchers and method runners return values
  instead of `Amp\Promise`, and no longer need `Amp\call()`.
- Breaking: `Amp\CancellationToken` parameters became optional
  `?Amp\Cancellation $cancellation = null`.
- Breaking: `DiagnosticsProvider`, `CodeActionProvider`, `Formatter`,
  `ServiceProvider`, `RpcClient`, `ProgressNotifier` and `StreamProvider` have
  synchronous signatures.
- Breaking: `RequestReader::wait()` became `readMessage()`.
- Breaking: `LanguageServer::start()` and `shutdown()` block instead of
  returning a promise.
- Breaking: `Amp\Delayed` milliseconds became `Amp\delay()` seconds.
- Breaking: `RpcClient::request()` blocks until the response arrives, so it must
  be called from within a fiber.

8.0
---

- PHP 8.x updates
- Add support for incrementally updating text documents
- Breaking changes content changes are now DTOs instead of arrays.

7.0
---

- Dropped dependency on `Safe`
- Bumped minimum version of PHP to 8.1

6.0
---

- Refactored diagnostics engine to execute diagnostic providers in parallel

5.0
---

- Code actions must implement the `describe(): string` method.

1.0.2
-----

- Add support for file events

0.2.0
-----

Rewritten to use Amphp.
