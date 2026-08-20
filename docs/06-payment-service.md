# Payment Service Specification

## Responsibility

Own payment records and payment processing.

## Payment

- id
- order_id
- amount
- status
- transaction_id
- created_at
- updated_at

## Statuses

- pending
- completed
- failed
- refunded

## Fake gateway

Implement a local interface such as PaymentGateway.

Provide a fake implementation capable of:
- success
- failure
- timeout

The purpose is to practice dependency inversion and service providers without introducing a real payment provider yet.

## Reliability

Payment operations must be idempotent. A repeated request/event must not charge the same transaction twice.
