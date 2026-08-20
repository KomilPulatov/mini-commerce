# Product Service Specification

## Responsibility

Own product/catalog information and inventory state.

## Product

- id
- name
- description
- price
- stock
- status
- created_at
- updated_at

## Endpoints

POST /api/products
GET /api/products
GET /api/products/{id}
PATCH /api/products/{id}
DELETE /api/products/{id}

## Collection features

Support:
- search
- price filtering
- status filtering
- sorting
- pagination

## Constraint

Other services access products through the Product Service API or published events. They do not access the Product Service database.
