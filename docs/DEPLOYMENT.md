# Production Deployment

The local `docker-compose.yml` is the development environment. Production uses
`docker-compose.prod.yml` together with the HTTPS override after the certificate
has been issued.

## Architecture

The production stack contains:

- `app`: Laravel PHP-FPM application;
- `nginx`: public web server, Laravel proxy, and compiled Vue frontend;
- `db`: PostgreSQL, available only on the internal Docker network;
- `certbot`: optional one-shot container for issuing and renewing certificates;
- `queue` and `scheduler`: optional Laravel workers.

The nginx image is built in two stages. Node/npm exists only in the temporary
frontend build stage. The final runtime image contains nginx and the compiled
`frontend/dist` files, but does not contain Node or npm.

## Files

- `docker-compose.prod.yml`: production services and internal networks.
- `docker-compose.prod.https.yml`: HTTPS ports, certificates, and nginx config override.
- `.env.production`: Docker Compose variables; server-only, not committed.
- `backend/.env.production`: Laravel runtime variables; server-only, not committed.
- `frontend/.env.production`: Vite build-time variables; server-only, not committed.

The root `.env` file is not used by production and should not exist on the server.
Local `docker-compose.yml` uses a fixed `1000:1000` container user and does not
require a root `.env` file.

The three production env files have different consumers and are not
interchangeable.

| File | Read by | When it is read | Contains |
| --- | --- | --- | --- |
| `.env.production` | Docker Compose | Each Compose command | image tag, domain, PostgreSQL container credentials |
| `backend/.env.production` | Laravel container | Container startup/runtime | Laravel, database, session, mail, and queue settings |
| `frontend/.env.production` | Vite | During `npm run build` inside the nginx image build | public `VITE_*` values compiled into JavaScript |

`npm run build` runs Vite in `production` mode, so Vite loads
`frontend/.env.production`. `npm run dev` uses `frontend/.env.development`.
Changing a frontend env file does not change an already built image: nginx must
be rebuilt.

Never put passwords, tokens, or other secrets into a `VITE_*` variable. These
values are compiled into browser JavaScript and are public.

## Server env files

Create these files on the server:

```bash
cp .env.production.example .env.production
cp backend/.env.example backend/.env.production
cp frontend/.env.example frontend/.env.production
```

Edit all three files before starting the stack.

Important values:

- `.env.production`: `APP_DOMAIN`, `POSTGRES_DB`, `POSTGRES_USER`, and `POSTGRES_PASSWORD`.
- `backend/.env.production`: `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL`, database credentials, mail settings, and Sanctum/session domains.
- `frontend/.env.production`: Impressum and legal document values used by the browser application.

For the current same-origin deployment, keep this value empty:

```dotenv
VITE_API_URL=
```

The production frontend will then call `/api` and `/sanctum` on the current
domain. Local development may use `frontend/.env.development` with
`VITE_API_URL=http://localhost:8000`.

The root production Compose database values and Laravel database values must
describe the same database. Inside Docker, Laravel connects to `DB_HOST=db`, not
`localhost`.

## Generate the Laravel application key

`APP_KEY` is mandatory. Laravel uses it to encrypt cookies, sessions, and other
application data. A missing key causes production requests to fail with HTTP
500.

Generate a key on the server:

```bash
printf 'base64:%s\n' "$(openssl rand -base64 32)"
```

Copy the complete generated value, including the `base64:` prefix, into
`backend/.env.production`:

```dotenv
APP_KEY=base64:generated-value
```

Generate the key only once for an installation. Keep it secret and preserve it
across deployments and container rebuilds. Changing or losing the key
invalidates encrypted cookies and can make previously encrypted application
data unreadable.

## First HTTP start

Start the stack without HTTPS first, so nginx can answer ACME HTTP challenges:

```bash
sudo docker compose --env-file .env.production \
  -f docker-compose.prod.yml \
  up -d --build
```

Run migrations:

```bash
sudo docker compose --env-file .env.production \
  -f docker-compose.prod.yml \
  exec app php artisan migrate --force
```

## Issue Let's Encrypt certificate

Every HTTPS hostname must point to the server before requesting its certificate.
Only include `www` when its DNS record exists and it should redirect to the
non-www domain.

```bash
sudo docker compose --env-file .env.production \
  -f docker-compose.prod.yml \
  --profile certbot run --rm certbot certonly \
  --webroot \
  --webroot-path /var/www/certbot \
  --domain retourenjournal.de \
  --email admin@example.com \
  --agree-tos \
  --no-eff-email
```

To add `www` to an existing certificate later:

```bash
sudo docker compose --env-file .env.production \
  -f docker-compose.prod.yml \
  --profile certbot run --rm certbot certonly \
  --webroot \
  --webroot-path /var/www/certbot \
  --cert-name retourenjournal.de \
  --domain retourenjournal.de \
  --domain www.retourenjournal.de \
  --expand \
  --email admin@example.com \
  --agree-tos \
  --no-eff-email
```

## Switch to HTTPS

After the certificate exists:

```bash
sudo docker compose --env-file .env.production \
  -f docker-compose.prod.yml \
  -f docker-compose.prod.https.yml \
  up -d
```

## Deploy an update

Pull or copy the new application version to the server. Keep the three real env
files on the server; they are not committed.

Rebuild and replace all changed application images:

```bash
sudo docker compose --env-file .env.production \
  -f docker-compose.prod.yml \
  -f docker-compose.prod.https.yml \
  up -d --build
```

Run migrations after the new app container is running:

```bash
sudo docker compose --env-file .env.production \
  -f docker-compose.prod.yml \
  -f docker-compose.prod.https.yml \
  exec app php artisan migrate --force
```

For a frontend-only change, rebuild and replace nginx:

```bash
sudo docker compose --env-file .env.production \
  -f docker-compose.prod.yml \
  -f docker-compose.prod.https.yml \
  build nginx

sudo docker compose --env-file .env.production \
  -f docker-compose.prod.yml \
  -f docker-compose.prod.https.yml \
  up -d --force-recreate nginx
```

For a backend-only change, rebuild and replace `app`. If the optional workers
are active, recreate them from the same new app image as well.

Use `--no-cache` only when diagnosing a stale or unexpected image. A normal
source or `frontend/.env.production` change invalidates the relevant Docker
build layer automatically.

## Verification

Check container state:

```bash
sudo docker compose --env-file .env.production \
  -f docker-compose.prod.yml \
  -f docker-compose.prod.https.yml \
  ps
```

The production bundle must not contain the development API address:

```bash
sudo docker compose --env-file .env.production \
  -f docker-compose.prod.yml \
  -f docker-compose.prod.https.yml \
  exec nginx grep -R "localhost:8000" -n /var/www/html/frontend
```

No output is the expected result. In browser DevTools, API requests should use
`https://retourenjournal.de/api/...`.

## Backups and restore

Production backups are handled by the scripts in `backup/`. They use restic and
store encrypted snapshots in Cloudflare R2. PostgreSQL tools run inside the
production `db` container; the host needs Bash, Docker Compose, and restic.

Before enabling the cron job, create `/backup.env` on the server from
`backup/backup.env.example`, fill in the R2 credentials and restic password,
and run `restic init` once as described in `backup/README.md`. Keep a separate
secure copy of `/backup.env`; it is intentionally not included in the backup.

Run a backup manually:

```bash
sudo /srv/retourenjournal/backup/backup.sh
```

A daily cron entry can run the same command and append output to a server log.
The backup contains the PostgreSQL dump, `.env.production`,
`backend/.env.production`, `frontend/.env.production`, and backup/restore notes.

Restore is split into two explicit steps:

```bash
sudo /srv/retourenjournal/backup/restore-env.sh SNAPSHOT_ID --yes
sudo docker compose --env-file .env.production -f docker-compose.prod.yml up -d db
sudo /srv/retourenjournal/backup/restore-db.sh SNAPSHOT_ID --yes
```

`restore-db.sh` drops and recreates the configured production database. Stop the
application, queue, and scheduler before restoring. See `backup/README.md` for
the full procedure and restore caveats.

## Certificate renewal

Configure a host cron or systemd timer to run:

```bash
sudo docker compose --env-file /srv/retourenjournal/.env.production \
  -f /srv/retourenjournal/docker-compose.prod.yml \
  --profile certbot run --rm certbot renew

sudo docker compose --env-file /srv/retourenjournal/.env.production \
  -f /srv/retourenjournal/docker-compose.prod.yml \
  -f /srv/retourenjournal/docker-compose.prod.https.yml \
  exec nginx nginx -s reload
```


## Optional workers

Queue and scheduler are defined behind the `workers` profile. Enable them only when background jobs or scheduled jobs are needed:

```bash
sudo docker compose --env-file .env.production \
  -f docker-compose.prod.yml \
  -f docker-compose.prod.https.yml \
  --profile workers \
  up -d
```
