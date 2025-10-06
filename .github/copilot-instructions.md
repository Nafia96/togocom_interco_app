<!-- .github/copilot-instructions.md - guidance for AI coding agents working on togocom_interco_app -->
# Copilot instructions for togocom_interco_app

This file gives concise, actionable guidance for AI coding agents (or humans) to be immediately productive in this Laravel codebase.

Keep it short (20–50 lines). When in doubt, prefer minimal, non-breaking changes and add tests or examples if behavior changes.

## Big picture
- This is a Laravel 8 application (see `composer.json`). Main entry is `index.php` → `bootstrap/app.php`. Routes live in `routes/web.php` and controllers in `app/Http/Controllers/`.
- Views are in `resources/views/` (Blade templates). JavaScript/CSS are built with Laravel Mix (see `package.json` — `npm run dev` / `npm run production`).

## Architecture & important directories
- `app/` — core PHP code (Controllers, Models, Middleware, Providers).
- `routes/web.php` — primary web routes; many routes use controller methods like `HomeController@billingPivotNetCarrier`.
- `app/Helpers/functions.php` — global helper functions included via composer `autoload.files`.
- `resources/views/` — Blade templates (e.g. `resources/views/billing/billingPivotNetCarrier.blade.php`).
- `database/migrations`, `database/seeders` — DB schema and seeders (used by `artisan migrate` and `artisan db:seed`).

## Developer workflows (explicit)
- Install PHP deps: run `composer install` (ensure PHP 7.3+ / 8.0+).
- Install NPM deps: run `npm install` then `npm run dev` to build assets (see `package.json`).
- Run migrations: `php artisan migrate` or `php artisan migrate:fresh` (routes/web.php exposes convenience routes `/migrate` and `/migrate-fresh` that call Artisan but only use them with care).
- Clear caches: `php artisan config:cache`, `php artisan cache:clear`, `php artisan route:clear`, `php artisan view:clear` (used throughout routes for convenience).

## Conventions & gotchas (project-specific)
- Routing: many routes use `Route::match(['get','post'], ...)` and controller strings instead of PHP 7 callables — follow existing style when adding routes.
- Middleware: `app/Http/Middleware/` contains `NotConnected` and `superAdmin`. Preserve their intent when adding endpoints.
- Global helpers: `app/Helpers/functions.php` is auto-loaded via composer; prefer adding small helpers there rather than scattering utility functions.
- Blade: views are not strictly namespaced; search `resources/views/` to find the template used by a controller (e.g. `HomeController::billingPivotNetCarrier` → `resources/views/billing/billingPivotNetCarrier.blade.php`).

## Integration points & external deps
- Packages declared in `composer.json` (Laravel, guzzlehttp, maatwebsite/excel). Use existing packages for HTTP calls and Excel exports.
- Frontend tooling uses Laravel Mix (`webpack.mix.js`) — avoid adding unrelated build tools.

## Safety rules for edits
- Small changes only: never change global app bootstrap code (`bootstrap/`, `index.php`) unless necessary.
- Database changes: add migrations instead of editing the database directly. Prefer `migrate` + `seed` flows.
- Backwards compatibility: when modifying controller outputs consumed by views, update the Blade templates or add fallbacks to avoid breaking pages.

## Examples (where to look)
- To implement a new billing page: add route in `routes/web.php`, controller method in `app/Http/Controllers/HomeController.php`, and view under `resources/views/billing/`.
- To add a helper: edit `app/Helpers/functions.php` and run `composer dump-autoload` (or rely on `post-autoload-dump` script).

## Tests & verification
- PHPUnit configured (`phpunit.xml`). Run `vendor/bin/phpunit` after adding PHP tests.
- Quick smoke: start a local server `php artisan serve` and visit key routes like `/dashboard`, `/billingn` to validate views.

If anything above is unclear or you want more examples (controllers, middleware, or common SQL patterns used by the app), tell me which area to expand and I'll update this file.
