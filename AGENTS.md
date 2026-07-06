# AGENTS.md — Project context for AI coding assistants

> This file is the source of truth for architecture decisions and conventions.
> Every AI session must follow this document. If a request conflicts with it,
> flag the conflict instead of silently deviating.

## 1. Project overview

A web-based marketplace connecting Malaysian couples with wedding photographers
and videographers. Clients browse verified photographer profiles, filter by
location/budget/date availability, and send booking requests. Photographers
manage profiles, packages, availability, and respond to requests with quotes.

**Launch scope (MVP):** Wedding events only, Klang Valley only. The schema and
enums support multiple event types from day one, but UI/marketing exposes only
weddings at launch.

**Business model:** Subscription for photographers (free tier + paid tier),
NOT commission. No payment processing between client and photographer in MVP —
they deal directly (WhatsApp) after connecting. Do not build payment/escrow
features unless explicitly asked.

**Language:** UI copy is Bahasa Malaysia (casual, rojak-friendly). Code,
comments, commit messages, and identifiers are English.

## 2. Tech stack (fixed — do not substitute)

| Layer          | Choice                                              |
| -------------- | --------------------------------------------------- |
| Framework      | Laravel 12, PHP 8.4                                 |
| Frontend       | Blade + Livewire 3 (`wire:navigate`) + Alpine.js    |
| Styling        | Tailwind CSS, mobile-first                          |
| Admin & dashboards | Filament (multi-panel: `admin` + `photographer`) |
| Auth           | Laravel Breeze + Socialite (Google login)           |
| Database       | MySQL 8                                             |
| Cache/Queue/Session | Redis (queue monitored by Horizon)             |
| Media          | Spatie Media Library → S3 driver (MinIO local, Cloudflare R2 production) |
| Search         | Eloquent queries for MVP; Laravel Scout + Meilisearch later (do not add yet) |
| Email          | Laravel Mail → Mailpit (local), Resend/SES (production) |
| Testing        | Pest                                                |
| Dev environment | Docker via Laravel Sail on macOS (ARM64)           |
| Gallery        | Swiper.js for portfolio swipe                       |

**Do not introduce:** Inertia, Vue, React, Livewire alternatives, MongoDB,
Cashier/Stripe, GraphQL, or any real-time/websocket layer (no chat in MVP).

## 3. Core domain schema

Nine core tables. Column lists below are canonical — extend via migration only
when a feature requires it, never rename existing columns casually.

### users
- id, name, email (unique), phone (unique, nullable), password (nullable for Google-only accounts)
- role: enum `client | photographer | admin` (PHP Enum `UserRole`)
- google_id (nullable), timestamps, soft deletes

### photographer_profiles
- id, user_id (FK, unique — one profile per user)
- slug (unique, for SEO URLs like `/aiman-studio`)
- business_name, bio (text), location_area (string), coverage_areas (json array of areas)
- instagram_handle (nullable), whatsapp_number
- tier: enum `free | pro` (PHP Enum `ProfileTier`) — denormalized from active subscription, synced via observer
- verified_at (nullable timestamp — null = not yet approved, hidden from public)
- featured_until (nullable timestamp), timestamps, soft deletes

### packages
- id, profile_id (FK), name, event_type (PHP Enum `EventType`, only `wedding` used at launch)
- price_from (int, in RM — store ringgit as integers, no floats for money)
- deliverables (text), duration_hours (int), is_active (bool), timestamps

### portfolio_items
- id, profile_id (FK), event_type (enum), caption (nullable), sort_order (int)
- Images attached via Spatie Media Library (collection `portfolio`), NOT a path column
- Media conversions: thumbnail (webp, ~400px) and display (webp, ~1200px), queued

### availabilities
- id, profile_id (FK), date (date), status: enum `available | booked | tentative` (PHP Enum `AvailabilityStatus`)
- Unique composite index on (profile_id, date)
- One row per date. This powers the "who is available on X date" query — keep it row-based, never JSON ranges.

### booking_requests
- id, client_id (FK to users, NULLABLE — guest bookings allowed)
- guest_name, guest_phone, guest_email (nullable — used when client_id is null)
- profile_id (FK), package_id (FK, nullable)
- event_type (enum), event_date (date), location (string)
- budget_from, budget_to (int, RM), message (text, nullable)
- status: enum `pending | quoted | accepted | declined | expired` (PHP Enum `BookingStatus`)
- responded_at (nullable), timestamps, soft deletes

### quotes
- id, booking_request_id (FK), amount (int, RM), message (text, nullable)
- valid_until (date), status: enum `sent | accepted | declined | expired` (PHP Enum `QuoteStatus`)
- A request may have multiple quotes over time (revisions); latest active one is authoritative.

### reviews
- id, booking_request_id (FK, unique — one review per booking)
- rating (tinyint 1–5), comment (text, nullable), published_at (nullable — null = pending moderation)
- Reviews ONLY exist against accepted booking requests. Never allow reviews without a booking. Enforce in policy + validation.

### subscriptions
- id, profile_id (FK), plan: enum `free | pro` , status: enum `active | cancelled | expired`
- starts_at, ends_at (nullable), timestamps
- Simple custom table. Do NOT use Laravel Cashier (Malaysian gateways, not Stripe).

## 4. Architecture conventions

- **Enums:** every status/type column has a backed PHP Enum in `app/Enums`. Never raw strings in code.
- **Money:** integers in ringgit (RM). No floats, no cents (wedding pricing doesn't need sen).
- **Queues:** all image processing, email, and notification sending goes through queued jobs. Never inline in the request cycle.
- **Authorization:** Laravel Policies for every model. Photographers can only touch their own profile/packages/portfolio/availability. Clients can only see their own requests. Admin bypasses via `before()`.
- **Filament panels:** `admin` panel (verification queue, listings management, moderation) and `photographer` panel (profile, packages, portfolio, availability, incoming requests). Public site is Blade + Livewire, NOT Filament.
- **Public routes:** SEO-first. `/{profile:slug}` for profiles, clean filter URLs on browse page. Every public page needs proper meta tags and OpenGraph.
- **Guest bookings:** booking request form must work without login. If phone matches an existing user later, requests can be linked.
- **Verified gate:** profiles with null `verified_at` never appear in public listings or search. Filter this in a global query scope or dedicated `visible()` scope — use it everywhere public.
- **Images:** always through Spatie Media Library, always converted to WebP via queued conversions, always lazy-loaded on frontend with skeleton placeholders.
- **Denormalized tier:** `photographer_profiles.tier` is synced from subscriptions via a model observer. Read tier from the profile in listing queries (no JOIN); read from subscriptions only in billing logic.

## 5. Coding style

- Follow Laravel conventions and Pint defaults (run `./vendor/bin/pint` before commit).
- Form Requests for validation — no inline `$request->validate()` in controllers.
- Actions/single-purpose classes for business logic (e.g. `CreateBookingRequest`, `SendQuote`) — thin controllers, thin Livewire components.
- Livewire components: one responsibility each. Extract Blade partials aggressively.
- Tailwind: mobile-first. Design for 390px viewport first, then `md:` upward. No custom CSS unless Tailwind genuinely can't express it.
- Database: every FK indexed, composite index on `availabilities (profile_id, date)`, index on `booking_requests (profile_id, status)`.

## 6. Testing expectations

Write Pest tests alongside every feature that touches these areas (mandatory):
- Booking request creation (guest + authenticated paths)
- Quote lifecycle and status transitions
- Review creation rules (only on accepted bookings)
- Visibility scope (unverified profiles never leak into public queries)
- Authorization policies

UI/Blade-only changes don't require tests. Never delete a failing test to make
a build pass — fix the code or flag the conflict.

## 7. Explicitly out of scope for MVP

Do not build, scaffold, or "prepare" these unless explicitly requested:
- Payment processing, escrow, invoicing
- In-platform chat / websockets / Laravel Reverb
- Native or hybrid mobile app code
- Google Calendar sync
- Meilisearch/Scout integration
- Multi-language i18n framework (hardcode BM copy for now)
- Event types other than wedding in the UI

## 8. Workflow rules for AI sessions

- Work in small slices: one feature (migration + model + policy + UI) per session.
- Show migrations for review before assuming they're final — schema changes are expensive after data exists.
- When touching auth, policies, or anything money-adjacent, summarize the security implications of the change.
- Prefer editing existing files over creating parallel implementations.
- Commit-sized diffs. If a task would touch 15+ files, propose a breakdown first.
