# Docker Environment

Final local environment should contain:

- gateway
- user-service
- product-service
- order-service
- payment-service
- notification-service
- one database per service
- Redis
- queue workers

Use Docker Compose.

Each service must be able to run independently and communicate over the Docker network.
