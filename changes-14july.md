# Changes - 14 July

## Fixed

- Added a `methodShortName` column to the `paymentmethods` table so each payment gateway can carry a short display alias (e.g. `bKash`, `Nagad`, `Binance`) alongside its full visible name.
- Added a `dollarRateConversionEnabled` column to the `paymentmethods` table so admins can turn the USD-to-BDT converter off per gateway.
- Added a **ShortName** text input to the admin "Edit payment method" form, positioned directly after the Icon selector, so admins can set a short label for each gateway.
- Added a **Dollar Rate Conversion** on/off toggle to the admin "Edit payment method" form, positioned between the Bonus Enabled and Status dropdowns, defaulting to Enabled.
- Wired the ShortName and Dollar Rate Conversion fields into the automatic and manual method update queries so they persist on save.
- Added the `short_name` and `dollar_rate_conversion_enabled` properties to the user-side add-funds payment method payload so the frontend has access to both values.
- Updated the gateway quick-grid tile renderer (`renderGatewayQuickGrid`) to display the payment method's `short_name` from the database as the tile label, falling back to the hardcoded label when no short name is set.
- Removed the "Quick select" subtitle from the gateway tiles to keep the tile compact and let `short_name` serve as the sole label.
- Added an `isDollarRateConversionEnabled()` helper to the add-funds page JS that checks each method's `dollar_rate_conversion_enabled` setting.
- Updated `syncCurrencyConverter()` so the converter panel shows only when both `isLocalGateway()` and `isDollarRateConversionEnabled()` are true — if the admin disabled the converter for a gateway, it stays hidden regardless of the gateway's region type.
- Added the migration entries for both new columns to `hosting-db-columns.php` so existing / new hosting environments receive the schema update safely.
- **Fixed the gateway on/off filtering** — The gateway quick-grid tiles and Method dropdown were both hardcoded, so disabling payment methods in admin had no effect on the addfunds page. Replaced the hardcoded `gatewayTiles[]` array with `getDynamicGatewayTiles()` that builds tiles directly from active payment methods (logo, shortName, stable hue-based colors). Replaced `isLocalGateway()` (which checked hardcoded aliases) with `hasExchangeRate()` that checks the method's `exchange_rate` field to determine if a gateway is local. Replaced all hardcoded `"binance_pay"` fallback references with proper gateway detection. Added early bail-out in `addfundsInit()` when no payment methods are active.
- Updated the payment method icon picker to support multi-upload, show uploaded icons side by side, allow selecting any uploaded icon as the active logo, and soft-delete broken or unwanted icons before final save.
- Removed the old AJAX-based payment methods list flow and made the `/admin/settings/paymentMethods` page server-rendered so it loads faster and no longer spins forever.
- Refreshed the `/addfunds` page UI to a compact card layout with a cleaner gateway grid, better converter spacing, and a visible calculation breakdown for bonus and fee math.
- Updated the add-funds amount fields so the visible USD input stays editable while typing, with no frontend formatting or validation forcing the value into a locked decimal shape.
- Added the one-off DB helper `hosting-db-columns.php` for deploying the missing schema columns on hosted environments.

## Notes

- The ShortName field is optional; when left empty the gateway tile falls back to the hardcoded label from `gatewayTiles[]`.
- Dollar Rate Conversion defaults to Enabled for all existing methods; admins must explicitly disable it per gateway.
- No changes were needed to the routing layer, language files, or Twig template structure — all changes are scoped to the controller/data/view triad.
- The add-funds front-end intentionally keeps the typed amount raw and leaves validation to the server-side flow.

## Excel Rows

Copy/paste block for the spreadsheet:

```tsv
Feature Name	File Name	Line Start	Line End	Function Name	Type	Comments	Description	Database	Version	Date
ShortName Column Migration	hosting-db-columns.php	91	92	addColumnIfMissing	New	Schema migration	Added methodShortName VARCHAR(100) column to paymentmethods table	paymentmethods	:-	:-
DollarRateConversion Column Migration	hosting-db-columns.php	93	93	addColumnIfMissing	New	Schema migration	Added dollarRateConversionEnabled TINYINT(1) column to paymentmethods table	paymentmethods	:-	:-
ShortName Field in Admin Form	admin/controller/settings/paymentMethods/getForm.php	57	59	getForm form builder	Modify	Admin method editor	Added ShortName text input after the Icon selector in the Edit payment method modal	paymentmethods	:-	:-
Dollar Rate Conversion Toggle in Admin Form	admin/controller/settings/paymentMethods/getForm.php	113	117	getForm form builder	Modify	Admin method editor	Added Dollar Rate Conversion on/off toggle between Bonus Enabled and Status dropdowns	paymentmethods	:-	:-
ShortName + DollarRate Save (Auto)	admin/controller/settings/paymentMethods/edit.php	37	38	POST edit handler	Modify	Admin method editor	Added method_short_name and dollar_rate_conversion_enabled to the automatic methods UPDATE query	paymentmethods	:-	:-
ShortName + DollarRate Save (Manual)	admin/controller/settings/paymentMethods/edit.php	106	113	POST edit handler	Modify	Admin method editor	Added method_short_name and dollar_rate_conversion_enabled to the manual methods UPDATE query	paymentmethods	:-	:-
ShortName + DollarRate in AddFunds Payload	app/controller/addfunds.php	98	99	GET addfunds data loader	Modify	User add-funds flow	Added short_name and dollar_rate_conversion_enabled properties to the methodsList array sent to Twig	addfunds, paymentmethods	:-	:-
ShortName Display in Gateway Tiles	app/views/N1RentalPanel/addfunds.twig	1276	1295	renderGatewayQuickGrid	Modify	User add-funds UI	Updated gateway quick-grid tiles to show method short_name from DB, fallback to hardcoded label	addfunds page, paymentmethods	:-	:-
DollarRateConversion JS Helper	app/views/N1RentalPanel/addfunds.twig	1286	1293	isDollarRateConversionEnabled	New	User add-funds JS helper	Added JS function to check if a payment method has dollar rate conversion enabled	addfunds page	:-	:-
Converter Visibility Wiring	app/views/N1RentalPanel/addfunds.twig	1433	1434	syncCurrencyConverter	Modify	User add-funds converter logic	Updated syncCurrencyConverter to respect dollar_rate_conversion_enabled — converter hides when disabled	addfunds page	:-	:-
Payment Method Icon Multi-Upload	admin/controller/appearance.php	1	1	upload handler	Modify	Admin media flow	Allowed multiple image uploads, preserved source extensions, and stored uploaded files for payment method icon selection	files, paymentmethods	:-	:-
Payment Method Icon Picker	admin/controller/settings/paymentMethods/getForm.php	1	1	getForm form builder	Modify	Admin method editor	Rendered uploaded icons side by side with select and delete actions inside the edit modal	paymentmethods	:-	:-
AddFunds UI Refresh	app/views/N1RentalPanel/addfunds.twig	1	1	Template and JS	Modify	User add-funds UI	Redesigned the add-funds panel, moved quick presets, added raw amount entry, and showed bonus/fee breakdown	inline UI	:-	:-
```
