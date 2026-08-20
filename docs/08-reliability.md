# Reliability Requirements

Introduce these after the basic services work.

## Timeouts

Every synchronous service-to-service HTTP call must have a finite timeout.

## Retries

Retry only operations that are safe to retry.

Use bounded retries and backoff.

## Idempotency

Duplicate requests/messages must not produce duplicate side effects.

Examples:
- payment transaction IDs
- event IDs
- unique database constraints

## Failed jobs

Configure a failed-job strategy and inspect failures.

## Eventual consistency

Accept that an order may temporarily show a state that catches up after a message is processed.

Do not fake distributed transactions with shared databases.
