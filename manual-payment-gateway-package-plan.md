# Manual Payment Gateway Package Plan

## Goal
Extract the current manual and semi-manual payment flows into a reusable PHP package that standardizes how the app renders payment forms, collects sender details, stores payment references, and completes verification.

## Suggested Package Identity

- Composer package: `jarir-ahmed/manual-payment-gateway`
- PHP namespace: `JarirAhmed\ManualPaymentGateway`
- Main entry point: `JarirAhmed\ManualPaymentGateway\GatewayManager`

This follows the same vendor style as `jarir-ahmed/http-response`.

## What This Package Should Cover

The package should support payment flows where the user must manually complete or confirm a transfer, for example:

- manual bank or wallet payments
- send-money gateways
- gateway flows that need sender name
- gateway flows that need transaction ID
- gateway flows that need local admin review before final approval

## Current App Behavior To Preserve

The package must fit the existing panel flow instead of replacing it all at once:

- the user selects a gateway on Add Funds
- the app builds the gateway-specific form fields
- the app creates the local `payments` row
- the app stores any gateway metadata needed for later review
- the payment is finalized only after the gateway-specific completion step

## Proposed Package Shape

### Core classes

- `GatewayManager`
- `GatewayAdapterInterface`
- `PaymentIntent`
- `PaymentRequest`
- `PaymentResult`
- `GatewayContext`
- `PaymentSessionStore`

### Adapter responsibilities

- declare gateway name and supported aliases
- build the form fields for the gateway
- validate user-submitted payment data
- normalize payment metadata
- provide completion or verification result

### Package responsibilities

- normalize manual gateway behavior across providers
- return form schema and validation rules
- expose a consistent result object for success and failure
- keep provider-specific logic isolated in adapters

### App responsibilities

- load active payment methods from `paymentmethods`
- map the selected method to the correct adapter
- create and update the local payment record
- keep user balance updates inside the app
- keep admin review logic in the app database layer

## Suggested Public API

```php
$manager = new GatewayManager($config);
$adapter = $manager->resolve('bkash');
$form = $adapter->buildForm($context);
$result = $adapter->validate($payload, $context);
```

Expected methods:

- `resolve(string $alias): GatewayAdapterInterface`
- `supports(string $alias): bool`
- `buildForm(GatewayContext $context): array`
- `validate(array $input, GatewayContext $context): PaymentResult`
- `finalize(PaymentIntent $intent): PaymentResult`

## Data To Pass Into The Package

Each gateway should receive a small normalized context object:

- method id
- method alias
- visible name
- minimum and maximum amount
- fee percentage
- bonus settings
- icon/logo
- current user identity
- amount and currency
- callback or verification metadata

## Where It Connects In This Repo

Reference files:

- [`app/controller/addfunds.php`](/home/jarir-ahmed/Documents/Nefolio/app/controller/addfunds.php)
- [`app/controller/addfunds/getForm.php`](/home/jarir-ahmed/Documents/Nefolio/app/controller/addfunds/getForm.php)
- [`app/controller/payment.php`](/home/jarir-ahmed/Documents/Nefolio/app/controller/payment.php)
- `app/controller/addfunds/Initiators/*.php`
- `app/controller/payment/*.php`

## Migration Strategy

### Phase 1: Map current manual gateways

- list every manual or semi-manual payment method
- document the required form fields for each one
- identify which flows are truly manual and which are hybrid auto/manual

### Phase 2: Define the package contract

- create the adapter interface
- define the payment intent and result objects
- define how form schema gets returned to the panel

### Phase 3: Extract shared behavior

- move field rendering into package adapters
- move common validation into shared helpers
- move gateway metadata normalization into the manager

### Phase 4: Integrate with the panel

- replace the hardcoded `getForm.php` branches gradually
- keep existing payment table writes unchanged
- keep the current admin editor behavior intact

### Phase 5: Add adapter examples

- manual payment adapter
- send-money adapter
- one hybrid gateway adapter as a reference implementation

## Recommended File Layout

```text
src/
  GatewayManager.php
  Contracts/GatewayAdapterInterface.php
  DTO/PaymentIntent.php
  DTO/PaymentResult.php
  DTO/GatewayContext.php
  Adapters/ManualPaymentAdapter.php
  Adapters/SendMoneyAdapter.php
  Support/PaymentSessionStore.php
```

## Open Questions

- Should gateway verification stay synchronous or be queue-based?
- Should the package only build form payloads, or also write payment metadata?
- Should admin approval be a first-class adapter type or a separate workflow?
- Should gateway aliases come from the database or from package configuration?

