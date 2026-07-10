# Changelog - Nefolio Setup

This document tracks all file modifications made to the Nefolio project during local setup and configuration.

## [2026-07-09]

### Files Created
- **[`.env`](file:///home/jarir-ahmed/Documents/Nefolio/.env):** Created database configuration file at the root. Now includes application settings and Google OAuth credentials.
- **[`.env.example`](file:///home/jarir-ahmed/Documents/Nefolio/.env.example):** Created environment template file to guide config setups.
- **[`router.php`](file:///home/jarir-ahmed/Documents/Nefolio/router.php):** Created router script to handle requests and route static files properly with PHP's built-in web server.
- **[`start.sh`](file:///home/jarir-ahmed/Documents/Nefolio/start.sh):** Created startup script to start PHP development server at `http://0.0.0.0:5400`.
- **[`ftp_listing.txt`](file:///home/jarir-ahmed/Documents/Nefolio/ftp_listing.txt):** Saved the remote FTP directory listing of the target upload directory (`public_html`).
- **[`.github/workflows/deploy.yml`](file:///home/jarir-ahmed/Documents/Nefolio/.github/workflows/deploy.yml):** Created GitHub Actions workflow for automatic FTP deployment on push to `main` branch.
- **`assets/` (Manually Created):** Created the `assets/` directory locally and copied resources there to fix the stylesheet/script loading paths.


### Files Modified
- **[`app/config.php`](file:///home/jarir-ahmed/Documents/Nefolio/app/config.php):** Modified to load `.env` variables first, then dynamically configure database credentials and system constants. Added dynamic host detection to automatically use the local host URL during local development (preventing cross-origin CORS/AJAX issues) while keeping production `.env` settings on the live remote server. Persistently enabled display_errors but adjusted the error_reporting mask to hide non-fatal Notices, Warnings, and Deprecation alerts on modern PHP versions, while keeping Fatal Errors fully visible.
- **[`app/controller/auth.php`](file:///home/jarir-ahmed/Documents/Nefolio/app/controller/auth.php):** Extracted hardcoded Google OAuth credentials to `.env` to prevent GitHub Push Protection failures when pushing.
- **[`router.php`](file:///home/jarir-ahmed/Documents/Nefolio/router.php):** Rewrote the router to intercept physical directory requests (such as `/admin/`) and route them to root `index.php`, preventing directory indexes or 403 blocks on localhost.

## [2026-07-10]

### Files Created
- **[`import-db.php`](file:///home/jarir-ahmed/Documents/Nefolio/import-db.php):** Created database sync script to import local database schemas/data to remote hosting database (removed post-import).
- **[`add-admin.php`](file:///home/jarir-ahmed/Documents/Nefolio/add-admin.php):** Created self-destructing sync script to insert/update the `admin` super-admin account on the live database (removed post-import).

### Files Modified
- **[`admin/views/new-footer.php`](file:///home/jarir-ahmed/Documents/Nefolio/admin/views/new-footer.php):** Added dynamic global JavaScript variable `site_url` to construct absolute URLs for all admin AJAX requests. Added error-handling path clearance for empty database lists (0 records) to correctly remove table loading spinners and show a clean "No Data" row, resolving the page stuck loading indefinitely on hosting.
- **[`admin/views/new-header.php`](file:///home/jarir-ahmed/Documents/Nefolio/admin/views/new-header.php):** Fixed double-slash path resolution bug for `ic_account_new.png` by removing leading slash from the `site_url` parameter.

### Custom Resend System Integration
- **[`app/controller/mock-api.php` [NEW]](file:///home/jarir-ahmed/Documents/Nefolio/app/controller/mock-api.php):** Created a mock SMM panel API endpoint at `http://localhost:5400/mock-api` to process and return successful JSON orders for local integration testing.
- **[`admin/views/orders.php` [MODIFY]](file:///home/jarir-ahmed/Documents/Nefolio/admin/views/orders.php):** Added the **Custom Resend** action trigger dropdown link to failed orders.
- **[`admin/controller/ajax_data.php` [MODIFY]](file:///home/jarir-ahmed/Documents/Nefolio/admin/controller/ajax_data.php):**
  - Registered `order_custom_resend` case to output searchable providers/services modal layout.
  - Registered `custom_resend_get_services` case to fetch active services.
  - Fixed AJAX scope resolution bug by querying the `site_url` path dynamically from the document base tag.
  - Fixed MySQL ENUM index-matching bug on the `service_deleted` column by comparing it against string `'0'`.
- **[`admin/controller/orders.php` [MODIFY]](file:///home/jarir-ahmed/Documents/Nefolio/admin/controller/orders.php):**
  - Implemented the `custom_resend` POST case handler to update database orders, send requests to SMM / SocialsMedia APIs, and redirect the admin.
  - Resolved MySQL strict mode error on integer column `api_orderid` by converting empty API results to `0`.
  - Resolved MySQL ENUM truncation error on the `order_status` column by setting it to the valid value `'pending'`.
- **[`start.sh` [MODIFY]](file:///home/jarir-ahmed/Documents/Nefolio/start.sh):** Rewrote the dev server startup script to automatically kill conflicting port `5400` processes and enable multi-worker threading (`PHP_CLI_SERVER_WORKERS=4`) to eliminate single-threaded loopback deadlocks.

> [!NOTE]
> All local and mock SMM API endpoints have been verified. To test final live order submissions end-to-end, you will need to add a working, funded SMM Provider API key to the Settings panel.



