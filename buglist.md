# Nefolio Bug List

This document tracks identified bugs, issues, and pending fixes in the Nefolio application.

## Open Issues

### 1. Production API Documentation URL Schema Resolution
* **Location:** `https://netfollows.com/api` (relative route `api`)
* **Symptom:** The API documentation page shows the API URL as `//netfollows.com/api/v2`, which is broken/unresolved.
* **Localhost Behavior:** Works correctly on localhost (e.g., outputs `http://localhost:5400/api/v2`).
* **Root Cause & Next Steps:** 
  The URL is likely rendered dynamically using `site_url("api/v2")` or similar system configurations. Because `.env` sets `APP_URL=//netfollows.com` (without schema), it is missing the `http:` or `https:` prefix on production. The rendering script or config needs to be updated to ensure the current protocol is prepended.
