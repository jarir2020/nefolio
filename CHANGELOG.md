# Changelog - Nefolio Setup

This document tracks all file modifications and bug fixes made to the Nefolio project during local setup and debugging.

## [2026-07-09]

### Files Created
- **[`.env`](file:///home/jarir-ahmed/Documents/Nefolio/.env):** Created database configuration file at the root. Now includes application settings and Google OAuth credentials.
- **[`.env.example`](file:///home/jarir-ahmed/Documents/Nefolio/.env.example):** Created environment template file to guide config setups.
- **[`router.php`](file:///home/jarir-ahmed/Documents/Nefolio/router.php):** Created router script to handle requests and route static files properly with PHP's built-in web server.
- **[`start.sh`](file:///home/jarir-ahmed/Documents/Nefolio/start.sh):** Created startup script to start PHP development server at `http://0.0.0.0:5400`.
- **[`ftp_listing.txt`](file:///home/jarir-ahmed/Documents/Nefolio/ftp_listing.txt):** Saved the remote FTP directory listing of the target upload directory (`public_html`).
- **[`.github/workflows/deploy.yml`](file:///home/jarir-ahmed/Documents/Nefolio/.github/workflows/deploy.yml):** Created GitHub Actions workflow for automatic FTP deployment on push to `main` branch.

### Files Modified
- **[`app/config.php`](file:///home/jarir-ahmed/Documents/Nefolio/app/config.php):** Modified to load `.env` variables first, then dynamically configure database credentials (`DB_DATABASE`, `DB_HOST`, `DB_USERNAME`, `DB_PASSWORD`, `DB_CHARSET`) and system constants (`URL`, `SUBFOLDER`, `STYLESHEETS_URL`, and timezone settings).
- **[`app/controller/auth.php`](file:///home/jarir-ahmed/Documents/Nefolio/app/controller/auth.php):** Extracted hardcoded Google OAuth credentials to `.env` to fix GitHub Push Protection scan issues.
- **[`app/init.php`](file:///home/jarir-ahmed/Documents/Nefolio/app/init.php):** Fixed case-sensitivity of `general_options` SQL table query.
- **[`admin/controller/settings.php`](file:///home/jarir-ahmed/Documents/Nefolio/admin/controller/settings.php):** Fixed case-sensitivity of `general_options` SQL table queries.



### Bug Fixes
- **Case-Sensitive Table Name Query Crash:**
  - *Symptom:* WSOD (White Screen of Death) when accessing the project root due to uncaught PDOException.
  - *Cause:* MySQL table names are case-sensitive on Linux. The script queried `General_options` (capitalized) while the database table name is `general_options` (lowercase).
  - *Fix:* Replaced `General_options` with `general_options` in [`app/init.php`](file:///home/jarir-ahmed/Documents/Nefolio/app/init.php) and [`admin/controller/settings.php`](file:///home/jarir-ahmed/Documents/Nefolio/admin/controller/settings.php).
- **Missing Theme / Twig Loader Crash:**
  - *Symptom:* Fatal Twig loader exception stating that `/app/views/smmbekkul` directory does not exist.
  - *Cause:* The `site_theme` column in the `settings` table of the database was set to `smmbekkul`, which was missing from the `/app/views/` directory.
  - *Fix:* Ran a database query to update `site_theme` in `settings` table to the existing theme folder name `N1RentalPanel`.
