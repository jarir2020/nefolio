# Workspace Rules

- **Git Pushes:** Do not automatically run `git push` to push local commits to the remote repository. Always wait for the user to explicitly request a push.

## Special Workspace Memory & Lessons Learned

### 1. SSL/TLS Verification on Shared Hosting
* **Context:** Remote shared hosting servers frequently have outdated root CA certificate stores, causing outbound cURL connection requests (e.g. to SMM provider APIs) to fail with SSL handshake errors.
* **Resolution:** Always ensure that `CURLOPT_SSL_VERIFYPEER` is explicitly set to `false` in SMM API wrappers (like `app/classes/smm.php`) for these environments.

### 2. Broken Provider API Responses (HTTP 500 Success)
* **Context:** The SMM provider API (`demo.alphapaybd.com`) returns an `HTTP 500` error with an empty body even when an order is successfully accepted and placed on their backend.
* **Resolution:** Handle empty/null API responses as successful transactions when dealing with this provider. Set `order_error` to `"-"` (cleared) and fallback `api_orderid` to `0` so the order transitions to the "Pending" list.

### 3. Local Database Service Mappings vs. Live IDs
* **Context:** During a custom resend, setting the order's `service_id` to the provider's remote service ID (like `9165`) will break the `INNER JOIN services` relation on the admin listing pages if that ID does not exist in the local `services` table, causing the order to disappear from the UI.
* **Resolution:** Always map back to the local database's primary key (`services.service_id`) if matching, or preserve the order's original `service_id` when submitting to an unmapped provider service.

