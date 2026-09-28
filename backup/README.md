# Production backup and restore

Run these scripts on the production host from a checkout of this repository.
They require Bash, Docker Compose, and restic on the host. PostgreSQL tools run
only inside the production `db` container. The scripts read R2 credentials and
the restic password from `/backup.env` (override with `BACKUP_ENV_FILE` only for
testing). The file must be readable only by the backup operator.

The snapshot contains a compressed PostgreSQL custom-format dump, the root
`.env.production` for Docker Compose, `backend/.env.production`,
`frontend/.env.production`, and this directory's scripts and notes. Restic
encrypts all of these files before storing them in Cloudflare R2. `/backup.env`
is intentionally excluded: keep a separate secure copy of its repository URL,
R2 credentials, and restic password. Without the password the snapshots cannot
be restored.

## Setup

Create an R2 bucket and an API token with read/write access to it. Set
`RESTIC_REPOSITORY` to a path-style S3 URL, for example
`s3:https://ACCOUNT_ID.r2.cloudflarestorage.com/BUCKET_NAME/retourenjournal`.
For an EU-jurisdiction bucket, use the endpoint provided by Cloudflare.

```bash
sudo install -m 600 -o root -g root backup/backup.env.example /backup.env
sudoedit /backup.env
sudo bash -c 'set -a; source /backup.env; set +a; restic init'
```

Initialize the repository once. Keep the same restic password for subsequent
backups and restores. Ensure the three production env files exist in the
checkout, then run:

```bash
sudo ./backup/backup.sh
```

For a daily root cron job, use the absolute path to the checkout:

```cron
0 3 * * * /srv/retourenjournal/backup/backup.sh >> /var/log/retourenjournal-backup.log 2>&1
```

Every successful run keeps seven daily and four weekly snapshots for this
host and the `retourenjournal-production` tag, then prunes unused data. A failed
dump or upload does not trigger retention cleanup. The temporary staging
directory is removed on exit.

## Restore

List snapshots and choose a concrete snapshot ID:

```bash
sudo bash -c 'set -a; source /backup.env; set +a; restic snapshots --tag retourenjournal-production'
```

On a replacement host, place this checkout and `/backup.env` first. Restore
the env files before starting the production Compose stack:

```bash
sudo ./backup/restore-env.sh SNAPSHOT_ID --yes
```

Start only the database service. On an existing host, stop the application,
queue, and scheduler before restoring the database so they do not write to it.
The restore script refuses to run while any of these services is running.
The database restore **drops and recreates** the database named by the restored
root `.env.production` and then imports the selected snapshot. It does not
restore PostgreSQL roles outside that database; the Compose `POSTGRES_USER`
must already exist.

```bash
sudo docker compose --env-file .env.production -f docker-compose.prod.yml up -d db
sudo ./backup/restore-db.sh SNAPSHOT_ID --yes
```

After restoring, start the rest of the stack using the normal deployment
instructions. A change to `frontend/.env.production` requires rebuilding the
nginx image because Vite embeds those values during build.
