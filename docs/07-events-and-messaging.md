# Events and Messaging

## Core events

- OrderCreated
- PaymentCompleted
- PaymentFailed
- OrderCancelled

## Principle

An event states that something happened. A listener/consumer reacts to it.

Within one Laravel service, events may be synchronous.

Across services, use asynchronous messaging once the system reaches this phase.

## Example

Order Service:
OrderCreated -> message broker

Payment Service:
OrderCreated -> create payment

Payment Service:
PaymentCompleted -> message broker

Order Service:
PaymentCompleted -> update order state

Notification Service:
PaymentCompleted -> queue notification

## Requirements

Messages should contain stable identifiers and enough metadata for consumers to process them safely.
