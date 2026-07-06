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

| Role | Email |
|------|-------|
| Admin | admin@example.com |

Photographer profiles are seeded from the database seeder (25 verified studios).
