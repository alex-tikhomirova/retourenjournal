# Production Deployment

This project uses a separate production Compose setup. The local `docker-compose.yml` remains the development environment.

## Files

- `docker-compose.prod.yml` — production services for app, nginx, PostgreSQL, optional workers, and certbot.
- `docker-compose.prod.https.yml` — HTTPS override used after the first certificate has been issued.
- `.env.production.example` — root Compose variables example.
- `backend/.env.production` — real Laravel production environment file, created on the server and not committed.

## Server env files

Create these files on the server:

```bash
cp .env.production.example .env.production
cp backend/.env.example backend/.env.production
```

Edit both files before starting the stack.

Important values:

- `.env.production`: `APP_DOMAIN`, `POSTGRES_DB`, `POSTGRES_USER`, `POSTGRES_PASSWORD`.
- `backend/.env.production`: `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL`, database values, mail values, Sanctum/session domains.

## First HTTP start

Start the stack without HTTPS first, so nginx can answer ACME HTTP challenges:

```bash
docker compose --env-file .env.production -f docker-compose.prod.yml up -d --build
```

Run migrations:

```bash
docker compose --env-file .env.production -f docker-compose.prod.yml exec app php artisan migrate --force
```

## Issue Let's Encrypt certificate

Replace the domain and email:

```bash
docker compose --env-file .env.production -f docker-compose.prod.yml --profile certbot run --rm certbot certonly \
  --webroot \
  --webroot-path /var/www/certbot \
  --domain retourenjournal.example \
  --domain www.retourenjournal.example \
  --email admin@example.com \
  --agree-tos \
  --no-eff-email
```

## Switch to HTTPS

After the certificate exists:

```bash
docker compose --env-file .env.production \
  -f docker-compose.prod.yml \
  -f docker-compose.prod.https.yml \
  up -d
```

## Certificate renewal

Configure a host cron or systemd timer to run:

```bash
docker compose --env-file /path/to/project/.env.production \
  -f /path/to/project/docker-compose.prod.yml \
  --profile certbot run --rm certbot renew

docker compose --env-file /path/to/project/.env.production \
  -f /path/to/project/docker-compose.prod.yml \
  -f /path/to/project/docker-compose.prod.https.yml \
  exec nginx nginx -s reload
```

## Optional workers

Queue and scheduler are defined behind the `workers` profile. Enable them only when background jobs or scheduled jobs are needed:

```bash
docker compose --env-file .env.production -f docker-compose.prod.yml --profile workers up -d
```
