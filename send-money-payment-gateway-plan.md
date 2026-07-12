# Send Money Payment Gateway Plan

## Goal
Extract the current "Send Money" style payment flow into a separate reusable PHP package that can unify several manual or semi-manual payment methods under one gateway interface.

## What This Gateway Means

The idea is to provide a single gateway abstraction for flows like:
- Send money
- Manual or semi-manual payment initiation
- Transaction ID entry
- Verification step after submission

The package should support the panel behavior already used here:
- Create a payment record locally first
- Collect a sender / transaction reference from the user
- Verify or simulate verification through a gateway adapter
- Finalize the local payment once the flow is accepted

## Why This Is Useful

- Reduces duplicate code across multiple payment methods
- Makes it easier to add new "send money" providers
- Keeps the local app independent from a specific provider implementation
- Gives a clear package boundary for future reuse in other PHP projects

## Proposed Package Shape

### Core classes
- `GatewayManager`
- `GatewayAdapterInterface`
- `PaymentIntent`
- `VerificationResult`
- `PaymentSessionStore`

### Adapter responsibilities
- Build the payment form payload
- Accept the submitted transaction or sender reference
- Perform provider-specific verification logic
- Return a normalized success or failure response

### Local app responsibilities
- Create the `payments` row
- Store the gateway reference in `payment_extra`
- Update the payment after verification
- Credit the user balance

## Suggested Flow

1. User opens Add Funds.
2. User selects a Send Money gateway.
3. The package returns a dynamic form field set, such as amount and transaction ID.
4. The app stores a local pending payment row.
5. The adapter verifies the input or simulates provider confirmation.
6. The payment is marked successful and the balance is updated.

## Package Requirements

- PHP 7.4 compatible
- Framework-agnostic
- No direct dependency on this panel codebase
- Adapter-based so multiple providers can be added later
- Support for local dry-run / simulation mode for development

## Files to Reuse as Reference

- `app/controller/addfunds.php`
- `app/controller/addfunds/Initiators/alphapaybd.php`
- `app/controller/payment/alphapaybd.php`
- `app/controller/addfunds/getForm.php`

## Delivery Plan

### Phase 1: Map the existing flow
- List every current add-funds method
- Identify which methods are true redirect gateways and which are send-money/verification flows
- Document the fields needed by each flow

### Phase 2: Design the package API
- Define the adapter interface
- Define the payload format for create, verify, and finalize steps
- Define local storage requirements for gateway references

### Phase 3: Extract the common logic
- Move shared payment state handling into the package
- Move transaction reference handling into the package
- Keep provider-specific code in small adapter classes

### Phase 4: Integrate back into the panel
- Replace duplicated gateway handlers with package calls
- Keep the existing UI behavior unchanged
- Preserve current payment table and balance update semantics

### Phase 5: Add simulation support
- Provide a local mode that does not hit a real provider
- Allow the gateway to return a successful response for testing
- Keep verification pluggable so real provider checks can be swapped in later

## Open Questions

- Should verification be synchronous or queued?
- Should the package own the database persistence layer or only return payloads?
- Should manual gateway approval be treated as a first-class adapter or a special mode?

