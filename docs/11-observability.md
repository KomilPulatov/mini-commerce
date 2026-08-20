# Observability

## Request ID

Every incoming request receives a correlation/request ID.

Propagate it across service-to-service calls and messages.

## Logs

Prefer structured logs containing:
- timestamp
- service
- request_id
- event/message ID
- operation
- status
- error information

## Health

Each service should expose a simple health endpoint.

Later, distinguish:
- liveness
- readiness

## Goal

Given an order ID or request ID, it should be possible to understand what happened across services.
