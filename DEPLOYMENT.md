# CariSnap — Deployment Guide

Production checklist for the CariSnap MVP (Laravel 12 + Sail locally).

## Local development (Laravel Sail)

```bash
cp .env.example .env
./vendor/bin/sail up -d
./vendor/bin/sail composer install
./vendor/bin/sail npm install && ./vendor/bin/sail npm run build
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate --seed
```

### Local services

| Service | URL / Notes |
|---------|-------------|
| App | http://localhost |
| Mailpit | http://localhost:8025 |
| MinIO console | http://localhost:8900 (user: `sail`, pass: `password`) |
| Horizon | http://localhost/horizon |

### MinIO bucket (first run)

Create the `local` bucket in MinIO console (or via `mc`) so portfolio uploads work:

1. Open http://localhost:8900
2. Login: `sail` / `password`
3. Create bucket named `local`
4. Set bucket access policy to public (dev only)

### Queue worker

Notifications and image conversions are queued. Run Horizon locally:

```bash
./vendor/bin/sail artisan horizon
```

---

## Production environment variables

Copy `.env.example` and set these for production:

```dotenv
APP_NAME=CariSnap
APP_ENV=production
APP_DEBUG=false
APP_URL=https://carisnap.my

DB_CONNECTION=mysql
DB_HOST=
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=

SESSION_DRIVER=redis
CACHE_STORE=redis
QUEUE_CONNECTION=redis

REDIS_HOST=
REDIS_PASSWORD=
REDIS_PORT=6379

MAIL_MAILER=resend
RESEND_API_KEY=re_xxxxxxxx
MAIL_FROM_ADDRESS=hello@carisnap.my
MAIL_FROM_NAME=CariSnap

FILESYSTEM_DISK=s3
MEDIA_DISK=s3

# Cloudflare R2
AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=auto
AWS_BUCKET=carisnap-media
AWS_USE_PATH_STYLE_ENDPOINT=false
AWS_ENDPOINT=https://<account_id>.r2.cloudflarestorage.com
AWS_URL=https://media.carisnap.my

GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
GOOGLE_REDIRECT_URI=https://carisnap.my/auth/google/callback
```

---

## Cloudflare R2 (media storage)

CariSnap uses Spatie Media Library with the `s3` disk driver. R2 is S3-compatible.

1. Create an R2 bucket (e.g. `carisnap-media`)
2. Create R2 API token with Object Read & Write
3. Set `AWS_*` variables as above
4. Enable public access via R2 custom domain or Cloudflare CDN
5. Set `AWS_URL` to the public URL (custom domain recommended)

Portfolio images are stored with queued WebP conversions (`thumbnail` 400px, `display` 1200px). Ensure the queue worker runs in production.

---

## Resend (transactional email)

All booking/quote/profile notifications implement `ShouldQueue` and send via Laravel Mail.

1. Verify your sending domain at [resend.com](https://resend.com)
2. Create API key → set `RESEND_API_KEY`
3. Set `MAIL_MAILER=resend`
4. Set `MAIL_FROM_ADDRESS` to a verified domain address

Install the Resend PHP SDK (included via Composer):

```bash
composer require resend/resend-php
```

---

## Google OAuth

1. Create OAuth credentials in Google Cloud Console
2. Add authorized redirect URI: `https://your-domain/auth/google/callback`
3. Set `GOOGLE_CLIENT_ID` and `GOOGLE_CLIENT_SECRET`

---

## Post-deploy commands

```bash
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

Start the queue worker (supervisor/systemd):

```bash
php artisan horizon
```

### Scheduler (cron)

Daily commands expire stale quotes and request reviews. Add a cron entry on the server:

```bash
* * * * * cd /path/to/carisnap && php artisan schedule:run >> /dev/null 2>&1
```

Scheduled tasks (see `routes/console.php`):

| Command | Schedule |
|---------|----------|
| `quotes:expire` | Daily |
| `reviews:request` | Daily at 10:00 |

No `storage:link` needed when `MEDIA_DISK=s3` — media URLs come from R2.

---

## Production checklist

- [ ] `APP_DEBUG=false`, `APP_ENV=production`
- [ ] MySQL 8 with backups
- [ ] Redis for session, cache, queue
- [ ] Horizon running and monitored
- [ ] Scheduler cron (`schedule:run` every minute)
- [ ] R2 bucket + public CDN domain
- [ ] Resend domain verified
- [ ] Google OAuth redirect URIs match production URL
- [ ] HTTPS enforced
- [ ] Horizon dashboard restricted to admin users (via `viewHorizon` gate)
- [ ] Filament admin panel restricted to admin users
- [ ] Run test suite: `php artisan test`
