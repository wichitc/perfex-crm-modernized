# External API

## Purpose
Specifies external communications integrations and payment gateway webhooks.

## Scope
Stripe API, PayPal SDK, Twilio SMS, Clickatell integrations, and Pusher settings.

## Detailed Explanation
### 1. Stripe Payment Webhook
- Callback URL: `gateways/stripe/callback`
- Logic: Validates Stripe signature header, parses payload, logs payment record, updates invoice status to Paid.

### 2. SMS Gateways
- Integrates with Twilio and Clickatell REST APIs using custom library wrappers to send bulk SMS reminders.

### 3. Pusher Websockets
- Triggers dashboard alerts dynamically without requiring page refreshes.

## References
- [API Document](14_API_Document.md)
- [Integration](36_Integration.md)
