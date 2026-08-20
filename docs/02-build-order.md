# Build Order

## Phase 1 — User Service

Build a normal Laravel API.

Learn:
- routes
- controllers
- Form Requests
- Eloquent
- Resources
- Sanctum
- Policies
- migrations
- factories
- seeders
- feature tests
- exception handling

Do not add repositories/services/providers just for ceremony.

## Phase 2 — User Service Refactor

Introduce only when justified:
- Actions
- Services
- Repository interfaces
- Repository implementations
- dependency injection
- Service Provider bindings

Goal: understand why each abstraction exists.

## Phase 3 — Product Service

Build product CRUD plus:
- search
- filtering
- sorting
- pagination

## Phase 4 — Service Communication

Order-related code will eventually call User/Product services over HTTP.

Do not share their databases.

## Phase 5 — Order Service

Create:
- orders
- order items
- order lifecycle
- checkout
- cancellation

Use transactions inside the service's own database.

## Phase 6 — Payment Service

Create a fake payment gateway with:
- success
- failure
- timeout

Keep real Stripe integration out of scope initially.

## Phase 7 — Events

Introduce events such as:
- OrderCreated
- PaymentCompleted
- OrderCancelled

Move cross-service communication toward asynchronous messaging.

## Phase 8 — Notification Service

Consume events and simulate:
- email
- SMS
- push notifications

## Phase 9 — Reliability

Add:
- queues
- retries
- backoff
- failed jobs
- idempotency
- timeouts

## Phase 10 — API Gateway

Expose one client-facing entry point and route requests to internal services.

## Phase 11 — Docker

Run services, databases, Redis, and workers with Docker Compose.

## Phase 12 — Observability

Add:
- structured logs
- request IDs
- health checks
- correlation IDs

## Phase 13 — Failure Testing

Deliberately break services and verify:
- timeouts
- retries
- duplicate messages
- unavailable services
- eventual consistency
