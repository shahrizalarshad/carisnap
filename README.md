# CariSnap

Marketplace web app connecting Malaysian couples with wedding photographers in Klang Valley.

**Stack:** Laravel 12, Livewire 3, Filament, MySQL, Redis, Spatie Media Library, Pest.

## Quick start (Sail)

```bash
cp .env.example .env
./vendor/bin/sail up -d
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate --seed
./vendor/bin/sail npm install && ./vendor/bin/sail npm run build
./vendor/bin/sail artisan horizon   # queue worker
```

Open http://localhost

| Panel | URL |
|-------|-----|
| Public site | `/` |
| Admin | `/admin` |
| Photographer | `/photographer` |
| Horizon | `/horizon` |
| Mailpit | http://localhost:8025 |

## Tests

```bash
./vendor/bin/sail artisan test
```

## Documentation

- [AGENTS.md](AGENTS.md) — architecture & conventions for AI sessions
- [DEPLOYMENT.md](DEPLOYMENT.md) — production setup (R2, Resend, env vars)

## Seeded accounts

After `migrate --seed`:

```bash
# Upsert demo logins only (safe on existing DB)
./vendor/bin/sail artisan db:seed --class=DemoAccountsSeeder
```

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@example.com | `password` |
| Photographer | photographer@example.com | `password` |
| Client | client@example.com | `password` |

Photographer demo account: **Studio Cahaya Permata** → panel at `/photographer`, public profile at `/studio-cahaya-permata`.

Client demo account: **Siti Aisyah** → tempahan at `/my-bookings` (2 sample requests to Studio Cahaya Permata).

25 verified studio profiles are also seeded (other photographers use random emails).
