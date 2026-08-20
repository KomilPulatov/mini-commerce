# Order Service Specification

## Responsibility

Own orders and order lifecycle.

## Order

- id
- user_id
- status
- total
- created_at
- updated_at

## OrderItem

- id
- order_id
- product_id
- quantity
- price

## Endpoints

POST /api/orders
GET /api/orders
GET /api/orders/{id}
POST /api/orders/{id}/cancel

## Checkout flow

1. Validate request.
2. Verify user through User Service.
3. Fetch/check products through Product Service.
4. Calculate order total.
5. Create order and items in one local DB transaction.
6. Publish OrderCreated.

## Important

Do not attempt a distributed database transaction across services.
