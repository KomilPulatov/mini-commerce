# Project Overview

## Goal

Build a small commerce platform whose business domain is intentionally simple:

- users buy products
- orders are created
- payments are processed
- notifications are sent

The learning goal is Laravel architecture and distributed systems, not complex commerce logic.

## Final services

- API Gateway
- User Service
- Product Service
- Order Service
- Payment Service
- Notification Service

## Core rule

Each service owns its own data. Services communicate through APIs or asynchronous messages rather than directly accessing another service's database.
