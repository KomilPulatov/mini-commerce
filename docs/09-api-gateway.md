# API Gateway

## Responsibility

Provide one client-facing entry point.

Example:

/api/users/* -> User Service
/api/products/* -> Product Service
/api/orders/* -> Order Service
/api/payments/* -> Payment Service

## Initial implementation

Use a small Laravel application for learning.

The gateway should not contain business logic.

It should handle concerns such as:
- routing
- authentication/token validation where appropriate
- request correlation
- rate limiting
- forwarding

Internal services remain independently deployable.
