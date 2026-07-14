# Nefolio — Architecture Documentation

> **What it is:** An SMM (Social Media Marketing) reseller panel — a PHP web application where users buy social media engagement (followers, likes, views) for platforms like Instagram, Facebook, YouTube, TikTok, etc. The panel connects to third-party provider APIs, places orders, and manages user balances.

---

## 1. High-Level Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                    Browser (Client)                          │
└─────────────────────┬───────────────────────────────────────┘
                      │  HTTPS
                      ▼
┌─────────────────────────────────────────────────────────────┐
│                    Web Server (Apache/Nginx)                  │
│                        .htaccess rules                        │
│              Rewrite all non-file URIs → index.php            │
└─────────────────────┬───────────────────────────────────────┘
                      │
                      ▼
┌─────────────────────────────────────────────────────────────┐
│                   Front Controller (index.php)                │
│                                                              │
│  1. Parse URL → determine route (controller name, params)     │
│  2. Handle language detection                                 │
│  3. Load the matching controller file                         │
│  4. After controller runs, render Twig view                  │
│  5. Output HTML to browser                                    │
└───────────┬──────────────────────────────────┬───────────────┘
            │                                  │
            ▼                                  ▼
┌──────────────────────┐        ┌──────────────────────────────┐
│  app/controller/     │        │  app/views/{THEME}/*.twig    │
│  *.php               │        │  + header.twig / footer.twig │
│  (Logic + DB work)   │        │  (HTML templates)            │
└──────────────────────┘        └──────────────────────────────┘
            │                                  ▲
            │  PDO (global $conn)              │  Twig variables
            ▼                                  │
┌──────────────────────┐        ┌──────────────┴───────────────┐
│   MySQL Database     │        │  app/helper/*.php             │
│                      │        │  app/classes/*.php            │
│                      │        │  app/language/*.php           │
└──────────────────────┘        └──────────────────────────────┘
```

---

## 2. Request Lifecycle

### Step-by-step

| # | File | What happens |
|---|------|-------------|
| 1 | `.htaccess` | Rewrite rules catch the request; forward to `index.php` (or serve static files directly) |
| 2 | `router.php` | Lightweight entry — checks for physical files, then passes to `index.php` |
| 3 | `index.php` | Front controller — sets `BASEPATH`, loads Composer autoload & PHPMailer |
| 4 | `index.php` | Calls `app/init.php` — the bootstrap |
| 5 | `app/init.php` | Starts session, connects to DB, loads cookie auth, fetches global settings, loads all helpers & classes, initialises Twig |
| 6 | `index.php` | Parses URL segments into `$route[]` array |
| 7 | `index.php` | Handles language detection (GET param → session → DB default) |
| 8 | `index.php` | `require controller($route[0])` — executes the controller file |
| 9 | Controller | Inline PHP script runs — processes POST, queries DB, sets template variables |
| 10 | `index.php` | `$twig->render(...)` — renders the view, passing 50+ variables |
| 11 | `index.php` | Outputs integrations JS/CSS, sends response |

### URL → Route mapping

```
URL:  /neworder            →  route(0) = "neworder",   controller: app/controller/neworder.php
URL:  /orders/all          →  route(0) = "orders",     route(1) = "all"
URL:  /orders/cancel/42    →  route(0) = "orders",     route(1) = "cancel",  route(2) = "42"
URL:  /admin/services      →  route(0) = "admin",      route(1) = "services" (but handled separately)
```

The `route()` helper returns the segment at a given index:
```php
function route($index) {
    global $route;
    return $route[$index] ?? false;
}
```

---

## 3. Directory Structure

```
/
├── index.php                 # Front controller
├── router.php                # Web server entry point (PHP built-in server / fallback)
├── .htaccess                 # Apache rewrite rules
├── .env                      # Environment configuration
│
├── app/                      # Application core
│   ├── config.php            # Reads .env → returns DB config array
│   ├── init.php              # Bootstrap: session, DB, auth, settings, helpers, Twig
│   │
│   ├── controller/           # ─── FRONTEND CONTROLLERS ───
│   │   ├── auth.php          #   Login/logout
│   │   ├── signup.php        #   Registration
│   │   ├── neworder.php      #   Place a new order
│   │   ├── orders.php        #   List orders, refill, cancel
│   │   ├── addfunds.php      #   Deposit money
│   │   ├── services.php      #   Browse services
│   │   ├── service.php       #   Single service detail
│   │   ├── account.php       #   User profile
│   │   ├── api.php           #   Programmatic API (SMM API v1)
│   │   ├── admin.php         #   Admin panel controller
│   │   ├── tickets.php       #   Support tickets
│   │   ├── affiliates.php    #   Referral/affiliate system
│   │   ├── massorder.php     #   Mass order upload
│   │   ├── subscriptions.php #   Subscription management
│   │   ├── dripfeeds.php     #   Drip-feed orders
│   │   ├── blog.php          #   Blog pages
│   │   ├── faq.php           #   FAQ page
│   │   ├── terms.php         #   Terms of service
│   │   ├── payment.php       #   Payment callbacks
│   │   ├── transferfunds.php #   Transfer between users
│   │   ├── cur.php           #   Currency selection
│   │   ├── earn.php          #   Earn page (tasks/rewards)
│   │   ├── kupon.php         #   Coupon system
│   │   ├── news.php          #   News/announcements
│   │   ├── integrations.php  #   Third-party integrations
│   │   ├── giveaways.php     #   Giveaway campaigns
│   │   ├── broadcast.php     #   Broadcast messages
│   │   ├── update.php        #   Single update detail
│   │   ├── updates.php       #   All updates/changelog
│   │   ├── affiliate/        #   Affiliate sub-controllers
│   │   ├── addfunds/         #   Payment gateway initiators
│   │   │   ├── getForm.php
│   │   │   └── Initiators/
│   │   │       └── alphapaybd.php
│   │   ├── payment/          #   Payment gateway callbacks
│   │   │   └── alphapaybd.php
│   │   ├── confirm_email.php #   Email confirmation
│   │   ├── resetpassword.php #   Password reset
│   │   ├── child-panels.php  #   Child panel (reseller) management
│   │   ├── transaction.php   #   Transaction history
│   │   ├── 404.php           #   Not found handler
│   │   ├── logout.php        #   Logout
│   │   ├── install.php       #   Installer
│   │   └── ajax_data.php     #   AJAX data endpoint
│   │
│   ├── views/                # ─── TWIG TEMPLATES ───
│   │   ├── maintenance.php   #   Maintenance mode page
│   │   ├── suspended.php     #   Account suspended page
│   │   └── {THEME}/          #   Active theme directory (e.g. N1RentalPanel)
│   │       ├── header.twig   #     Global <head>, nav, sidebar
│   │       ├── footer.twig   #     Closing tags, scripts
│   │       ├── auth.twig     #     Login page
│   │       ├── signup.twig   #     Registration
│   │       ├── neworder.twig #     New order form
│   │       ├── orders.twig   #     Order listing
│   │       ├── addfunds.twig #     Deposit page
│   │       └── ...           #     ~30+ page templates
│   │
│   ├── helper/               # ─── GLOBAL HELPERS ───
│   │   ├── app.php           #   Core: controller(), view(), route(), site_url(), GetIP(), themeExtras()
│   │   ├── admin.php         #   Admin: admin_controller(), admin_view()
│   │   └── data_control.php  #   Data: countRow(), getRow(), getRows(), from_to(), service_price(),
│   │                         #         format_amount_string(), get_currencies_array(), icon(), etc.
│   │
│   ├── classes/              # ─── SERVICE CLASSES ───
│   │   ├── smm.php           #   SMMApi — cURL wrapper for 3rd-party SMM provider APIs
│   │   │                     #   socialsmedia_api — alternative provider API
│   │   ├── mail.php          #   Email sending (SMTP)
│   │   └── sms.php           #   SMS sending
│   │
│   ├── language/             # ─── I18N ───
│   │   ├── default.php       #   Default language strings ($languageArray[])
│   │   ├── en.php            #   English
│   │   ├── ar.php            #   Arabic
│   │   ├── bn.php            #   Bengali
│   │   ├── fr.php            #   French
│   │   ├── hi.php            #   Hindi
│   │   └── list.php          #   Language selection logic
│   │
│   └── hidden/               #   Bridge scripts, proxy lists (internal)
│       └── bridge.php
│
├── admin/                    # ─── ADMIN PANEL ───
│   ├── controller/           #   Admin controllers (orders.php, services.php, clients.php, etc.)
│   ├── views/                #   Admin PHP views (not Twig)
│   └── ...                   #   Admin-specific assets
│
├── assets/                   # Public assets (CSS, JS, images)
├── css/                      # CSS files
├── js/                       # JavaScript files
├── img/                      # Image assets
├── public/                   # Public JS libs
│
├── vendor/                   # Composer dependencies (PHPMailer, Twig)
│
├── index.php                 # (see above)
├── router.php                # (see above)
└── .htaccess                 # (see above)
```

---

## 4. MVC Pattern Analysis

Nefolio implements a **convention-based, flat-file MVC** — not an OOP framework but a pragmatic script-inclusion pattern.

### 4.1 Controller (Backend) — `app/controller/*.php`

**Style:** Flat PHP scripts, not classes. Each controller is `require`d by the front controller.

**Convention:**
- File name = URL route segment (e.g. `/neworder` → `neworder.php`)
- Guard clause: `if(!defined('BASEPATH')) { die('Direct access...'); }`
- No function/class declaration — code runs at inclusion time
- Outputs nothing directly (except API controllers which echo JSON)
- Sets **template variables** that `index.php` passes to Twig: `$title`, `$error`, `$success`, `$errorText`, `$successText`, etc.
- Processes `$_POST` directly (form submission)
- Uses `$conn` (global PDO) for database queries
- Redirects with `header("Location:...")` after successful form processing
- Can consume `route(1)`, `route(2)` for actions/parameters

**Example — `neworder.php` flow:**
```
1. Guard
2. Append page title
3. Auth check → redirect if not logged in
4. Load categories from DB
5. If $_POST → validate, calculate price, check balance
6.   If manual order → INSERT into DB, update balance
7.   If API order → call SMMApi → INSERT with api_orderid
8.   Redirect to /order/{id}
9. (No output — variables feed into Twig)
```

### 4.2 View (Frontend) — `app/views/{THEME}/*.twig`

**Engine:** Twig (v1.x, via `Twig_Environment`)

**Theme system:**
- Theme directory = `$settings["site_theme"]` (e.g. `N1RentalPanel`)
- Set in `app/init.php`: `$loader = new Twig_Loader_Filesystem(__DIR__.'/views/'.THEME);`
- Template files: `{route0}.twig` (e.g. `neworder.twig`, `orders.twig`)

**Layout structure:**
```twig
{% include 'header.twig' %}    {# Global <head>, navbar, sidebar #}
...page content...              {# Uses Twig variables from controller #}
{% include 'footer.twig' %}    {# Closing tags, scripts #}
```

**Data flow:**
```php
// index.php — after controller is loaded and has set variables:
echo $twig->render(
    $templateDir . '.twig',
    [
        'user'   => $user,           // From init.php
        'error'  => $error,          // Set by controller
        'orders' => $ordersList,     // Set by controller
        'site'   => [...],           // Site config from DB
        // 50+ variables passed to every template
    ]
);
```

### 4.3 Model / Data Layer

There is **no ORM, no Eloquent, no ActiveRecord**. The data layer consists of:

1. **Global PDO connection** (`$conn`) — established in `app/init.php`
2. **Helper functions** in `app/helper/data_control.php`:
   - `countRow($data)` — SELECT COUNT wrapper
   - `getRow($data)` — fetch single row by WHERE clause
   - `getRows($data)` — fetch multiple rows with WHERE/ORDER/LIMIT
3. **Direct PDO queries in controllers** — most business logic uses `$conn->prepare(...)` inline
4. **Service-specific helpers** — `service_price()`, `client_price()`, `from_to()` (currency conversion), `format_amount_string()`

### 4.4 Summary: Nefolio MVC

| Layer | Location | Technology | Notes |
|-------|----------|-----------|-------|
| **Router** | `router.php` → `index.php` | Procedural PHP | URL parsing, route extraction |
| **Controller** | `app/controller/*.php` | Raw PHP scripts | No classes — inline execution |
| **View** | `app/views/{THEME}/*.twig` | Twig templates | Includes header/footer |
| **Model** | `$conn` + helpers + inline PDO | PDO + helpers | No ORM |
| **Service Classes** | `app/classes/*.php` | PHP classes | SMM API wrappers, Mail, SMS |
| **Helpers** | `app/helper/*.php` | Procedural PHP | Auto-loaded via `glob()` |
| **Config** | `.env` → `app/config.php` | Environment vars | DB creds, APP_URL, timezone |

---

## 5. The Admin Panel

The admin side (`/admin/*` routes) duplicates the MVC pattern with its own controllers and views:

```
router.php → detects /admin/* → routes to index.php
index.php  → route(0) = "admin" → require app/controller/admin.php
admin.php  → includes admin/controller/{route(1)}.php
```

- **Admin controllers:** `admin/controller/` — services, orders, clients, settings, etc.
- **Admin views:** `admin/views/*.php` — raw PHP + HTML (not Twig)
- **Admin helpers:** `admin_controller()`, `admin_view()` path builders in `app/helper/admin.php`
- **Auth:** Separate session (`msmbilisim_adminslogin`) and cookie (`a_id`, `a_password`) — checked against `admins` table
- **Access control:** `$admin["access"]` — JSON-encoded permissions in `admins.access` column

---

## 6. Key System Flows

### 6.1 Authentication

```
┌─────────┐     ┌──────────────┐     ┌───────────┐
│  Login   │────▶│  app/init.php│────▶│  Session   │
│  Page    │     │  verifies    │     │  created   │
└─────────┘     │  credentials │     └───────────┘
                └──────────────┘
                       │
                       ▼
                ┌──────────────┐
                │  Cookie set  │
                │  (remember)  │
                └──────────────┘
```

- User credentials verified against `clients` table
- Password hashed: `md5(sha1(md5($pass)))`
- Session: `$_SESSION["msmbilisim_userlogin"]`
- Cookie: `u_id`, `u_password`, `u_login` — checked on every request in `init.php`
- Admin auth follows the same pattern with `a_id`/`a_password` cookies

### 6.2 Order Placement

```
User fills form → neworder.php
    │
    ├── Validate fields, check balance
    │
    ├── Manual order?
    │   └── INSERT INTO orders → UPDATE clients balance
    │
    ├── API order?
    │   ├── Call SMMApi::action('add', ...) → gets api_orderid
    │   ├── INSERT INTO orders with api_orderid, api_charge
    │   ├── UPDATE clients balance
    │   └── Email alert if low provider balance
    │
    └── Redirect to /order/{id}
```

### 6.3 Add Funds / Payments

```
addfunds.php
    │
    ├── Loads available payment methods from DB
    ├── Renders deposit form via Twig
    │
    └── User submits → payment gateway:
        ├── Manual bank transfer → INSERT pending payment
        ├── PayPal / Stripe / etc. → redirect to gateway
        └── Local gateways (alphapaybd, shopier) → via Initiators/
```

### 6.4 API Integration (SMM Provider)

SMM provider communication happens through `app/classes/smm.php`:

**SMMApi class:** Standard SMM provider API (JSON response)
```php
$api->action(['key' => '...', 'action' => 'add', 'service' => 123, 'link' => '...'], $api_url);
// Returns: { "order": 1234 }
```

**socialsmedia_api class:** Alternative provider API format
```php
$api->query(['cmd' => 'orderadd', 'token' => '...', 'orders' => [...]]);
```

---

## 7. Database Pattern

- **Engine:** MySQL / MariaDB via PDO
- **Connection:** `app/config.php` reads `.env` → `app/init.php` creates `$conn` PDO instance
- **Query style:** 99% prepared statements with named parameters
- **Important tables:** `clients`, `orders`, `services`, `service_api`, `categories`, `payments`, `settings`, `tickets`, `currencies`, `tasks`, `referral`, `admin_constants`

---

## 8. Language / i18n System

- Language files are PHP arrays in `app/language/`
- Each file defines `$languageArray['key'] = 'Translation';`
- Selected language stored in session or user preference
- Loaded in `index.php`, available in controllers as `$languageArray["key"]`
- Twig receives it as `{{ lang["key"] }}`
- Admin panel uses English only

---

## 9. Frontend (Assets)

- **CSS:** `/assets/css/` and `/css/` — mostly Bootstrap 5-based, with custom theme overrides
- **JS:** `/assets/public/` and `/public/` — jQuery, custom modules
- **Theme assets (images):** `app/views/{THEME}/Image/`
- **Stylesheets config:** `themeExtras()` in `app/helper/app.php` — reads theme-specific stylesheets/scripts from DB
- **RTL support:** When `$selectedLang == "ar"`, adds `body-rtl` and `table-rtl` CSS classes

---

## 10. Security Notes

- **XSS Protection:** `htmlspecialchars()` used on POST input in critical controllers
- **CSRF:** No CSRF token pattern visible
- **SQL Injection:** Prepared statements used throughout (with some exceptions in dynamic query building in `data_control.php`)
- **Password Hashing:** `md5(sha1(md5($pass)))` — legacy, not bcrypt/argon2
- **Direct Access Guard:** `BASEPATH` constant check at top of every controller
- **.htaccess:** Extensive injection/filter rules blocking SQLi, XSS, UA-based attacks
- **SSL Enforce:** `.htaccess` redirects all HTTP → HTTPS

---

## 11. Composer Dependencies

```
- PHPMailer/PHPMailer     — SMTP email sending
- Twig (v1.x)             — Template engine
```

Loaded via `vendor/autoload.php` at the top of `index.php`.

---

## 12. Configuration (.env)

```env
APP_URL=https://example.com
DB_DATABASE=netfolio
DB_HOST=localhost
DB_USERNAME=root
DB_PASSWORD=...
DB_CHARSET=utf8mb4
APP_SUBFOLDER=false
APP_TIMEZONE=Asia/Dhaka
```

Consumed by `app/config.php` which returns the config array and defines constants (`URL`, `SUBFOLDER`, etc.).
