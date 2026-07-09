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

### Files Modified
- **[`app/config.php`](file:///home/jarir-ahmed/Documents/Nefolio/app/config.php):** Modified to load `.env` variables first, then dynamically configure database credentials and system constants. Added dynamic host detection to automatically use the local host URL during local development (preventing cross-origin CORS/AJAX issues) while keeping production `.env` settings on the live remote server.
- **[`app/controller/auth.php`](file:///home/jarir-ahmed/Documents/Nefolio/app/controller/auth.php):** Extracted hardcoded Google OAuth credentials to `.env` to prevent GitHub Push Protection failures when pushing.
- **[`router.php`](file:///home/jarir-ahmed/Documents/Nefolio/router.php):** Rewrote the router to intercept physical directory requests (such as `/admin/`) and route them to root `index.php`, preventing directory indexes or 403 blocks on localhost.

