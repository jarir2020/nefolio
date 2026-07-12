# Changes - 12 July

## Fixed

- Fixed strict-mode insert errors in the add-funds flow by adding the missing payment fields required by the `payments` table.
- Fixed the Alphapay gateway insert in `app/controller/addfunds/Initiators/alphapaybd.php` by including `payment_update_date` and `payment_bank`.
- Restored Alphapay to its original gateway behavior: create payment, redirect to Alphapay, then verify later in the callback.
- Updated the add-funds page layout so the `Add Fund` and `Fund History` tabs sit slightly lower and no longer get cropped by the top bar.
- Changed the new admin Rates saves to redirect back to the Rates page instead of printing raw JSON after submit, including the bonus edit route.
- Restored the missing legacy `payments_bonus` table so the admin `payment-bonuses` screen stops crashing on load.
- Fixed the `payment-bonuses` page queries to use the live `paymentmethods` table instead of the missing `payment_methods` name.
- Added a hosting migration helper to create the required DB columns and bonus tables safely on environments that are missing them.
- Added a per-payment-method bonus enable/disable setting in the payment method editor so admins can turn bonuses on for gateways like bKash and off for gateways like Binance.

## Updated Payment Labels

- Expanded the Alphapay method labels in the payment methods data so the panel shows the broader set of supported rails Alphapay advertises.
- Synced the live local `paymentmethods` rows for methods `41` and `42` with the broader Alphapay labels.
- Added the add-funds quick-select layout, USD-to-BDT converter, and method card positioning to match the requested panel flow.
- Reworked the add-funds quick select into eye-wash style gateway tiles and made the dropdown show gateway aliases while keeping the Alphapay backend route unchanged.
- Moved the dollar-rate converter below the instruction block and kept the submit button label as `Pay`.
- Saved the working USD rate as `132` in the local payment-method extras so it can be edited later from admin tools.
- Updated the add-funds converter so the `Total you get` value shows bonus-adjusted USD instead of the larger BDT figure.
- Rotated the Alphapay API key in both Alphapay payment-method rows to the new live key.
- Hid the BDT converter unless a local gateway alias like BD, Bkash, Nagad, Rocket, Upay, PhonePe, GCash, or Maya is selected.
- Added a new admin Rates page for the dollar rate and range-based bonus tiers, and wired add-funds to use those live settings.
- Changed the user-side collapsed Bonus Rates panel to show the bonus on/off state for each payment method.

## Notes

- The Alphapay form remains gateway-style, not manual send-money style.
- A separate reusable send-money gateway package plan is documented in `send-money-payment-gateway-plan.md`.

## Excel Rows

Copy/paste block for the spreadsheet:

```tsv
Feature Name	File Name	Line Start	Line End	Function Name	Type	Comments	Description	Database	Version	Date
Client Ref Code Fix	admin/controller/clients.php	196	215	Create client with ref_code	Modify	Strict insert fix	Generated missing ref_code before inserting new client records	clients	:-	:-
Alphapay Payment Insert Fix	app/controller/addfunds/Initiators/alphapaybd.php	15	38	alphapaybd gateway insert	Modify	Strict insert fix	Added payment_update_date and payment_bank to the Alphapay payment insert	payments	:-	:-
Add Funds Gateway Routing	app/controller/addfunds.php	21	195	GET/POST addfunds flow	Modify	Gateway loader + payment routing	Loaded active methods, payment history, and routed supported gateways	addfunds, paymentmethods, payments	:-	:-
Add Funds UI Rework	app/views/N1RentalPanel/addfunds.twig	772	853	Add Fund layout swap	Modify	UI section swap	Hid the old How to Add Money block and moved Add Fund Policy into that space	addfunds page	:-	:-
Add Funds Quick Select	app/views/N1RentalPanel/addfunds.twig	530	739	Gateway quick tiles and converter	Modify	UI enhancement	Added eye-wash style quick select tiles, USD to BDT converter, and gateway alias dropdown	addfunds page, paymentmethods	:-	:-
Add Funds Bonus Ladder	app/views/N1RentalPanel/addfunds.twig	685	1126	USD bonus calculator	Modify	UI update	Added tiered bonus handling and changed `Total you get` to display USD	addfunds page	:-	:-
Local Converter Visibility	app/views/N1RentalPanel/addfunds.twig	1171	1360	Local payment converter toggle	Modify	UI behavior	Shows the BDT converter only for local payment gateways and keeps it hidden for other methods	addfunds page	:-	:-
Rates Settings	admin/controller/settings.php	697	819	Rates page CRUD	Modify	Admin configuration	Adds the new Rates admin route for dollar rate updates and range-based bonus rule CRUD	rates, settings, rates_bonus_rules	:-	:-
Rates Redirect Flow	admin/controller/settings.php	697	819	POST redirect handling	Modify	Admin form behavior	Redirects Rates dollar, add, edit, and delete actions back to the Rates page instead of echoing JSON	rates, settings, rates_bonus_rules	:-	:-
Payments Bonus Table	netfollo_growthgalaxy.sql	1608	1622	New legacy table	New	Schema restore	Added the missing `payments_bonus` table so the payment-bonuses admin page can load again	payments_bonus	:-	:-
Payment Method Table Alias	admin/controller/settings.php	689	689	Bonus list join fix	Modify	Table name alignment	Switched the payment-bonuses list query to the live `paymentmethods` table and aliased the displayed method name	payments_bonus, paymentmethods	:-	:-
Client Ref Code Column	netfollo_growthgalaxy.sql	227	227	Client referral code column	New	Schema column	Added the `ref_code` client column used by the strict insert flow	clients	:-	:-
Payment Update Date Column	netfollo_growthgalaxy.sql	1585	1586	Payment timestamp column	New	Schema column	Added the `payment_update_date` column required by the add-funds insert flow	payments	:-	:-
Payment Bank Column	netfollo_growthgalaxy.sql	1588	1589	Payment bank column	New	Schema column	Added the `payment_bank` column required by the add-funds insert flow	payments	:-	:-
Hosting DB Columns Script	hosting-db-columns.php	1	95	Portable schema migration	New	Hosting helper	One-off helper that creates the required missing columns and bonus tables on hosting if they are absent	clients, payments, settings, payments_bonus, rates_bonus_rules	:-	:-
Payment Method Bonus Toggle	admin/controller/settings/paymentMethods/getForm.php	1	40	Bonus enable switch	Modify	Admin method editor	Added a per-method bonus on/off control directly in the Edit payment method modal	paymentmethods	:-	:-
Payment Method Bonus Column	hosting-db-columns.php	1	95	Schema migration	New	Hosting helper	Adds the `methodBonusEnabled` column on paymentmethods and defaults it on for existing methods	paymentmethods	:-	:-
Rates Data Plumbing	index.php	552	600	Bonus rules site JSON	Modify	Data exposure	Loads active bonus rules and the dollar rate into the shared site payload	addfunds page, settings, rates_bonus_rules	:-	:-
Add Funds Bonus Source	app/views/N1RentalPanel/addfunds.twig	1068	1335	Dynamic bonus ladder	Modify	UI behavior	Swapped hardcoded bonus thresholds for live bonus rules and the admin-configured dollar rate	addfunds page, rates_bonus_rules	:-	:-
User Bonus Status Panel	app/views/N1RentalPanel/addfunds.twig	1338	1394	Collapsed bonus status list	Modify	UI behavior	Changed the user-side Bonus Rates collapse to show payment-method bonus on/off settings	addfunds page, paymentmethods	:-	:-
Alphapay API Key Refresh	netfollo_growthgalaxy.sql	1550	1552	Payment method api_key update	Modify	Credential rotation	Replaced the Alphapay API key in the live payment-method seed rows	paymentmethods	:-	:-
USD Rate Storage	netfollo_growthgalaxy.sql	1550	1552	Payment method exchange_rate	Modify	Local data update	Set Alphapay method exchange_rate to 132 for the working USD rate	paymentmethods	:-	:-
Rates Bonus Rules	netfollo_growthgalaxy.sql	1916	1927	New bonus rule seed	New	Seed data	Added the default global bonus ranges and seeded the live dollar rate as 132	settings, rates_bonus_rules	:-	:-
Send Money Gateway Plan	send-money-payment-gateway-plan.md	1	1	Gateway package plan	New	Planning note	Documented the reusable send-money gateway package idea for later extraction	:-	:-	:-
```
