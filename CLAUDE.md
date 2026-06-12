# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project

Marketing website for **Judo Club Raion-ryu** (Kódókan Judo & Hiko-ryu Taijutsu, Praha 8 + Vodochody).
Laravel 13 · Livewire 4 + Volt 1 · Tailwind 4 (Vite) · SQLite · PHP 8.3.

**All site copy is Czech.** Keep every user-facing string, label, error message, and comment in Czech.

**No Japanese characters anywhere in the UI** (client decision, June 2026). Do not add kanji/kana watermarks, accents, or labels — the original design's decorative glyphs were all removed. Latin transliterations (Jūdō, randori, kata…) are fine.

## Commands

```bash
composer dev          # full dev stack: serve + queue + pail (logs) + vite, via concurrently
npm run dev           # Vite HMR only (needed for `app`-layout pages; landing pages inline their CSS)
npm run build         # production assets → public/build
composer test         # config:clear then artisan test
php artisan test --filter=SomeTest      # single test (PHPUnit, not Pest)
vendor/bin/pint       # format (Pint); `vendor/bin/pint --test` to check only
php artisan migrate   # SQLite at database/database.sqlite
```

The app is served by **Laravel Herd** at `http://judo.test` — no `artisan serve` needed for normal browsing.

Project-specific commands:

```bash
php artisan inquiries:send-pending      # email the queued (sent_at = null) form inquiries; --force ignores the enabled flag
python3 _scraper/build_data.py          # regenerate the gallery from manifest.json (see Gallery pipeline)
php artisan db:seed                     # admin user (ADMIN_* env) + events + documents; all seeders are idempotent
```

After editing any `config/content/*.php` file, run `php artisan config:clear` if config is cached.

## Architecture — read this first

The site exists in **two generations**, and which layout a page uses tells you which one it belongs to:

| | **Current** (`components.layouts.landing`) | **Legacy** (`components.layouts.app`) |
|---|---|---|
| Pages | home, `deti`, `instruktori`, `ke-stazeni`, `gallery-page`, `kodokan/pobyt-mistru` | everything else under `resources/views/livewire/pages/` |
| Routes | live | **all redirect to `/`** (see `routes/web.php`) |
| Styling | hand-written editorial CSS inlined in the layout's `<style>` (Noto Serif/Noto Sans, `--red: #C0261E`) | Tailwind 4 + Material-style tokens in `resources/css/app.css` (Space Grotesk, `#620000`) |
| Content | mostly **inline** in the Blade | read from `config('content.<key>')` |

When building or editing a **live** page, copy the landing-layout pages. Do **not** introduce Tailwind into them — the landing layout has no `@vite` and ships its CSS inline. The legacy `app`-layout pages are effectively dead code kept for reference; don't revive one without re-enabling its route.

There is also a third area, the **admin** (`/admin/*`, layouts `admin` + `admin-login`, CSS in `resources/views/partials/admin-css.blade.php` — IBM Plex Sans/Mono for data, same palette as landing, no Vite). See "Administrace" below.

> ⚠️ `_design/DESIGN.md` describes the *legacy* design system (Space Grotesk / `#620000` / "Modern Dojo"). It does **not** match the live site. The source of truth for the current design tokens is the `:root` block at the top of `resources/views/components/layouts/landing.blade.php`.

### Pages & routing

- Pages are **Volt single-file components** (`new #[Layout(...)] #[Title(...)] class extends Component {}` then Blade). Routed via `Volt::route('/path', 'pages.x')` in `routes/web.php`.
- `VoltServiceProvider` mounts both `resources/views/livewire` and `resources/views/pages`.
- Per-page meta description is passed through the `#[Layout(..., ['metaDescription' => '...'])]` attribute into the shared `<x-seo>` component.
- The homepage (`pages.home-page`) is one long single-page site (hero → maxims → about → techniques → děti → masters → japan → contact → calendar) with in-page `#anchor` navigation; most nav links point at anchors, not separate routes.
- Shared UI: `<x-ui.landing-nav>`, `<x-ui.landing-footer>`, `<x-ui.glossary>`. The legacy generation uses `<x-ui.header>` / `<x-ui.footer>` instead.
- `<x-seo>` builds canonical URL, Open Graph, and `SportsClub` JSON-LD from the **request host** (works regardless of `APP_URL`). `routes/web.php` also has a hand-built `/sitemap.xml` (live pages only) plus a block of 301s from the old site's URLs.

### Content-as-config

Legacy pages pull their copy from `config/content/*.php` (`club`, `contact`, `kodokan`, `news`, `training`, …). Nested config dir → dotted key: `config/content/gallery.php` is `config('content.gallery')`. Three of these are used by live pages: **`gallery.php`** (generated, see below), **`glossary.php`** (read by `<x-ui.glossary>`), and **`schedule.php`** — the single source of truth for the weekly training schedule, consumed by the homepage calendar (Alpine via `@json`), the inquiry-form validation (`$trainingDays` built in `boot()`), and the admin Rozvrh panel. Change the schedule **only** there; `ScheduleConfigTest` guards the shape and the en-dash labels (`'Judo – Praha 8'`) that inquiries are stored under.

One-off deviations from the weekly schedule live in the `schedule_overrides` table (`ScheduleOverride`, kind `zruseno`|`extra`), managed in admin → Rozvrh. The homepage calendar renders cancelled sessions struck-through and extra sessions on top of the regular ones, the inquiry form drops cancelled dates and offers extra dates (when the override has a `form` label), and the admin dashboard's "Nejbližší tréninky" respects both. Cancellations match config sessions by `form` + `time` snapshot. The homepage calendar also marks club events from the `events` table (gold dot + day detail linking to `/akce`).

Infra configs (not content): `config/gallery.php` (`media_path`/`media_url`/thumb sizes — tests override `media_path` to tmp) and `config/documents.php` (`path` for the PDF storage — same test override pattern).

### Administrace (`/admin`)

Single-user admin behind native session auth (no Breeze). Login `/admin/login` (Volt `pages.admin.login`, layout `admin-login`, rate-limited 5/min, red "wipe" animation on success); other sections are Volt pages in `resources/views/livewire/pages/admin/*` on the `admin` layout, routed in an `auth` group. Guests are redirected via `redirectGuestsTo` in `bootstrap/app.php`. The admin user is seeded by `AdminUserSeeder` from `ADMIN_EMAIL` / `ADMIN_PASSWORD` / `ADMIN_NAME` env (defaults in `.env.example`).

- Sections: Přehled (dashboard), Členové (`members` CRUD + inquiry→member flow), Rozvrh (week view from `schedule.php` + one-off cancel/extra via `schedule_overrides`), Akce (`events` CRUD → public `/akce`), Galerie (scraper albums read-only + DB album upload), Dokumenty (`documents` CRUD → public `/ke-stazeni`), Analytika (`site_visits`).
- Sidebar = `<x-ui.admin-sidebar>` (badge = unhandled inquiries). Modals are Livewire state (`@if` + `.modal-bg.open`), Alpine only for Escape/backdrop; deletes use `wire:confirm`; feedback via `$this->dispatch('toast', message: ...)` (listener in the `admin` layout). Admin pages are `noindex` and excluded from analytics + sitemap; `robots.txt` disallows `/admin`.

### Analytics

`TrackSiteVisit` middleware (appended to `web` in `bootstrap/app.php`) records **one row per visitor per day** into `site_visits`: anonymous `rr_visitor` cookie (unencrypted, excluded in `encryptCookies`) → sha256 hash, `Cache::add` guard till end of day, `insertOrIgnore`. Only GET + 200 + `text/html`, never `/admin*`/`livewire/*`, bots filtered by `App\Support\BotUserAgent` (substring list; `symfony` is included so the test client never records). No IP/UA stored. Dashboarded in admin → Analytika (server-side SVG chart, no JS chart lib).

### Inquiry pipeline

Contact form lives in the shared `livewire/inquiry-form.blade.php` Volt component, reused by the homepage `training-calendar` and the `deti` popup.

1. `inquiry-form` validates, then **always** persists an `Inquiry` (so nothing is lost before SMTP exists).
2. It emails immediately **only if** `config('mail.inquiries_enabled')` is true (env `MAIL_INQUIRIES_ENABLED`), sending to `config('mail.inquiries_to')` and stamping `sent_at`.
3. `training-calendar` is an Alpine calendar; clicking a day dispatches the Livewire event **`inquiry-prefill`** (`trainingType` + `date`, optionally `message` — used by the per-event "Zeptat se na akci" popup on `/akce`) to the form. Both read `config('content.schedule')`, so they stay in sync automatically. The prefilled message is only overwritten while it still starts with `Dotaz k akci` (never a user's own text).
4. `php artisan inquiries:send-pending` batch-sends everything still unsent (`Inquiry::pending()`) once SMTP is configured.
5. `handled_at` is separate from `sent_at`: it marks the inquiry as dealt with by the club leader in the admin (dashboard card "Nové poptávky" — "Vyřízeno" or "Založit člena", which prefills the member form via `/admin/clenove?from_inquiry=ID` and stamps `handled_at` on save).

### Gallery pipeline

`/galerie` (`gallery-page`) renders an album grid from `config('content.gallery')`, then **lazy-loads** per-album JSON and `/galerie-media/videos.json` client-side (vanilla JS at the bottom of the page) for the overlay + lightbox.

The data is generated, not hand-edited: `_scraper/` scrapes Rajče (`enumerate.py` → `download.py`/`video_download.py` → `manifest.json`) and `build_data.py` writes:
- **`config/content/gallery.php`** — album metadata (titles, dates, counts, cover URLs). **Committed.** Do not edit by hand.
- `public/galerie-media/<year>/<slug>/album.json` + the ~7 GB of images/videos + `videos.json` — **gitignored.** A fresh clone renders the grid but every cover/photo/video 404s until the media is restored or re-scraped. `/_scraper` is also gitignored.

> Media lives under `public/galerie-media/`, **not** `public/galerie/`, to avoid colliding with the `/galerie` route. Some scraper docstrings still say `galerie/`; trust the `galerie-media` paths in `gallery.php`.

**Admin-uploaded albums** live alongside the scraper ones: rows in the `gallery_albums` table + files written by `App\Support\GalleryImporter` (pure GD — EXIF orientation, downscale to `config('gallery.max_width')`, thumbs at `thumb_width`, everything normalized to `.jpg`, `album.json` in the exact scraper shape `{title, date, photos: [{t, f, c}]}`). The public `gallery-page` merges `config('content.gallery.albums')` + `GalleryAlbum::toPublicArray()` — the client-side JS is unchanged and must keep working for both sources. Scraper albums are read-only in the admin.

## Conventions & gotchas

- Slideshows (hero, děti) and the gallery JS are plain `<script>` that must survive Livewire SPA navigation — they re-init on `livewire:navigated` and guard against double-init. Follow that pattern for any new vanilla JS.
- Alpine is bundled with Livewire; `[x-cloak]` is defined in both layouts.
- Local images live in `public/images/...` (e.g. `images/hero/`, `images/deti/`, `images/instruktori/`) and are referenced with `asset()`.
- Defaults are queue/cache/session = `database`, mail = `log`. `MAIL_INQUIRIES_ENABLED=false` in dev keeps inquiries DB-only.
- `/ke-stazeni` is DB-driven (`documents` table, seeded from the original hardcoded list by `DocumentSeeder`); file links go through `GET /stahnout/{document}` which increments the `downloads` counter and serves the PDF inline (external rows redirect). Files live in `public/dokumenty` (`config('documents.path')`).
- There is a real PHPUnit feature suite in `tests/Feature` (auth, members, events, documents, gallery importer, analytics, schedule config, inquiry form). Tests that touch files **must** override `config('gallery.media_path')` / `config('documents.path')` to a tmp dir — never write into `public/`. Run the suite after touching any of these areas.

===

<laravel-boost-guidelines>
=== foundation rules ===

# Laravel Boost Guidelines

The Laravel Boost guidelines are specifically curated by Laravel maintainers for this application. These guidelines should be followed closely to ensure the best experience when building Laravel applications.

## Foundational Context

This application is a Laravel application and its main Laravel ecosystems package & versions are below. You are an expert with them all. Ensure you abide by these specific packages & versions.

- php - 8.4
- laravel/framework (LARAVEL) - v13
- laravel/prompts (PROMPTS) - v0
- livewire/livewire (LIVEWIRE) - v4
- livewire/volt (VOLT) - v1
- laravel/boost (BOOST) - v2
- laravel/mcp (MCP) - v0
- laravel/pail (PAIL) - v1
- laravel/pint (PINT) - v1
- phpunit/phpunit (PHPUNIT) - v12
- tailwindcss (TAILWINDCSS) - v4

## Skills Activation

This project has domain-specific skills available in `**/skills/**`. You MUST activate the relevant skill whenever you work in that domain—don't wait until you're stuck.

## Conventions

- You must follow all existing code conventions used in this application. When creating or editing a file, check sibling files for the correct structure, approach, and naming.
- Use descriptive names for variables and methods. For example, `isRegisteredForDiscounts`, not `discount()`.
- Check for existing components to reuse before writing a new one.

## Verification Scripts

- Do not create verification scripts or tinker when tests cover that functionality and prove they work. Unit and feature tests are more important.

## Application Structure & Architecture

- Stick to existing directory structure; don't create new base folders without approval.
- Do not change the application's dependencies without approval.

## Frontend Bundling

- If the user doesn't see a frontend change reflected in the UI, it could mean they need to run `npm run build`, `npm run dev`, or `composer run dev`. Ask them.

## Documentation Files

- You must only create documentation files if explicitly requested by the user.

## Replies

- Be concise in your explanations - focus on what's important rather than explaining obvious details.

=== boost rules ===

# Laravel Boost

## Tools

- Laravel Boost is an MCP server with tools designed specifically for this application. Prefer Boost tools over manual alternatives like shell commands or file reads.
- Use `database-query` to run read-only queries against the database instead of writing raw SQL in tinker.
- Use `database-schema` to inspect table structure before writing migrations or models.
- Use `get-absolute-url` to resolve the correct scheme, domain, and port for project URLs. Always use this before sharing a URL with the user.
- Use `browser-logs` to read browser logs, errors, and exceptions. Only recent logs are useful, ignore old entries.

## Searching Documentation (IMPORTANT)

- Always use `search-docs` before making code changes. Do not skip this step. It returns version-specific docs based on installed packages automatically.
- Pass a `packages` array to scope results when you know which packages are relevant.
- Use multiple broad, topic-based queries: `['rate limiting', 'routing rate limiting', 'routing']`. Expect the most relevant results first.
- Do not add package names to queries because package info is already shared. Use `test resource table`, not `filament 4 test resource table`.

### Search Syntax

1. Use words for auto-stemmed AND logic: `rate limit` matches both "rate" AND "limit".
2. Use `"quoted phrases"` for exact position matching: `"infinite scroll"` requires adjacent words in order.
3. Combine words and phrases for mixed queries: `middleware "rate limit"`.
4. Use multiple queries for OR logic: `queries=["authentication", "middleware"]`.

## Artisan

- Run Artisan commands directly via the command line (e.g., `php artisan route:list`). Use `php artisan list` to discover available commands and `php artisan [command] --help` to check parameters.
- Inspect routes with `php artisan route:list`. Filter with: `--method=GET`, `--name=users`, `--path=api`, `--except-vendor`, `--only-vendor`.
- Read configuration values using dot notation: `php artisan config:show app.name`, `php artisan config:show database.default`. Or read config files directly from the `config/` directory.

## Tinker

- Execute PHP in app context for debugging and testing code. Do not create models without user approval, prefer tests with factories instead. Prefer existing Artisan commands over custom tinker code.
- Always use single quotes to prevent shell expansion: `php artisan tinker --execute 'Your::code();'`
  - Double quotes for PHP strings inside: `php artisan tinker --execute 'User::where("active", true)->count();'`

=== php rules ===

# PHP

- Always use curly braces for control structures, even for single-line bodies.
- Use PHP 8 constructor property promotion: `public function __construct(public GitHub $github) { }`. Do not leave empty zero-parameter `__construct()` methods unless the constructor is private.
- Use explicit return type declarations and type hints for all method parameters: `function isAccessible(User $user, ?string $path = null): bool`
- Use TitleCase for Enum keys: `FavoritePerson`, `BestLake`, `Monthly`.
- Prefer PHPDoc blocks over inline comments. Only add inline comments for exceptionally complex logic.
- Use array shape type definitions in PHPDoc blocks.

=== deployments rules ===

# Deployment

- Laravel can be deployed using [Laravel Cloud](https://cloud.laravel.com/), which is the fastest way to deploy and scale production Laravel applications.

=== laravel/core rules ===

# Do Things the Laravel Way

- Use `php artisan make:` commands to create new files (i.e. migrations, controllers, models, etc.). You can list available Artisan commands using `php artisan list` and check their parameters with `php artisan [command] --help`.
- If you're creating a generic PHP class, use `php artisan make:class`.
- Pass `--no-interaction` to all Artisan commands to ensure they work without user input. You should also pass the correct `--options` to ensure correct behavior.

### Model Creation

- When creating new models, create useful factories and seeders for them too. Ask the user if they need any other things, using `php artisan make:model --help` to check the available options.

## APIs & Eloquent Resources

- For APIs, default to using Eloquent API Resources and API versioning unless existing API routes do not, then you should follow existing application convention.

## URL Generation

- When generating links to other pages, prefer named routes and the `route()` function.

## Testing

- When creating models for tests, use the factories for the models. Check if the factory has custom states that can be used before manually setting up the model.
- Faker: Use methods such as `$this->faker->word()` or `fake()->randomDigit()`. Follow existing conventions whether to use `$this->faker` or `fake()`.
- When creating tests, make use of `php artisan make:test [options] {name}` to create a feature test, and pass `--unit` to create a unit test. Most tests should be feature tests.

## Vite Error

- If you receive an "Illuminate\Foundation\ViteException: Unable to locate file in Vite manifest" error, you can run `npm run build` or ask the user to run `npm run dev` or `composer run dev`.

=== livewire/core rules ===

# Livewire

- Livewire allow to build dynamic, reactive interfaces in PHP without writing JavaScript.
- You can use Alpine.js for client-side interactions instead of JavaScript frameworks.
- Keep state server-side so the UI reflects it. Validate and authorize in actions as you would in HTTP requests.

=== volt/core rules ===

# Livewire Volt

- Single-file Livewire components: PHP logic and Blade templates in one file.
- Always check existing Volt components to determine functional vs class-based style.
- IMPORTANT: Always use `search-docs` tool for version-specific Volt documentation and updated code examples.
- IMPORTANT: Activate `volt-development` every time you're working with a Volt or single-file component-related task.

=== pint/core rules ===

# Laravel Pint Code Formatter

- If you have modified any PHP files, you must run `vendor/bin/pint --dirty --format agent` before finalizing changes to ensure your code matches the project's expected style.
- Do not run `vendor/bin/pint --test --format agent`, simply run `vendor/bin/pint --format agent` to fix any formatting issues.

=== phpunit/core rules ===

# PHPUnit

- This application uses PHPUnit for testing. All tests must be written as PHPUnit classes. Use `php artisan make:test --phpunit {name}` to create a new test.
- If you see a test using "Pest", convert it to PHPUnit.
- Every time a test has been updated, run that singular test.
- When the tests relating to your feature are passing, ask the user if they would like to also run the entire test suite to make sure everything is still passing.
- Tests should cover all happy paths, failure paths, and edge cases.
- You must not remove any tests or test files from the tests directory without approval. These are not temporary or helper files; these are core to the application.

## Running Tests

- Run the minimal number of tests, using an appropriate filter, before finalizing.
- To run all tests: `php artisan test --compact`.
- To run all tests in a file: `php artisan test --compact tests/Feature/ExampleTest.php`.
- To filter on a particular test name: `php artisan test --compact --filter=testName` (recommended after making a change to a related file).

</laravel-boost-guidelines>
