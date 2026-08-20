# User Service Specification

## Responsibility

Own user identity data and authentication.

## Data

User:
- id
- name
- email
- password
- created_at
- updated_at

## Endpoints

POST /api/users
GET /api/users/{id}
PATCH /api/users/{id}
DELETE /api/users/{id}

POST /api/login
POST /api/logout
GET /api/me

## Requirements

- Laravel Sanctum for the initial authentication implementation
- Form Requests for validation
- API Resources for responses
- Policies for authorization
- pagination where collection endpoints are introduced
- feature tests for authentication and CRUD
- never expose passwords

## Architectural constraint

The User Service owns the user database. Other services must not query it directly.

## Naming note

For this project, "User Service" owns identity and authentication initially. Later, if authentication becomes sufficiently independent, it can be extracted/renamed to "Identity Service" or "Auth Service" depending on its actual responsibility.
