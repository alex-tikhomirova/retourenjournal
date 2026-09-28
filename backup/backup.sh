#!/usr/bin/env bash

source "$(dirname -- "${BASH_SOURCE[0]}")/common.sh"

if ! command -v docker >/dev/null 2>&1; then
    echo 'Docker is required on the host' >&2
    exit 1
fi

for file in \
    "$PROJECT_ROOT/.env.production" \
    "$PROJECT_ROOT/backend/.env.production" \
    "$PROJECT_ROOT/frontend/.env.production"; do
    if [[ ! -f "$file" ]]; then
        echo "Missing production env file: $file" >&2
        exit 1
    fi
done

# Fail before creating a dump if the repository has not been initialized.
restic snapshots --latest 1 >/dev/null

stage="$(mktemp -d "${TMPDIR:-/tmp}/retourenjournal-backup.XXXXXXXX")"
trap 'rm -rf -- "$stage"' EXIT

mkdir -p "$stage/backend" "$stage/frontend" "$stage/backup"

# PostgreSQL's custom format compresses the dump; restic encrypts the whole snapshot.
"${compose[@]}" exec -T db sh -c \
    'PGPASSWORD="$POSTGRES_PASSWORD" exec pg_dump -Fc -Z 9 -U "$POSTGRES_USER" "$POSTGRES_DB"' \
    > "$stage/database.dump"

if [[ ! -s "$stage/database.dump" ]]; then
    echo 'PostgreSQL dump is empty' >&2
    exit 1
fi

install -m 600 "$PROJECT_ROOT/.env.production" "$stage/.env.production"
install -m 600 "$PROJECT_ROOT/backend/.env.production" "$stage/backend/.env.production"
install -m 600 "$PROJECT_ROOT/frontend/.env.production" "$stage/frontend/.env.production"
cp "$BACKUP_DIR/README.md" "$BACKUP_DIR/backup.env.example" \
    "$BACKUP_DIR/backup.sh" "$BACKUP_DIR/common.sh" \
    "$BACKUP_DIR/restore-db.sh" "$BACKUP_DIR/restore-env.sh" "$stage/backup/"

(
    cd "$stage"
    restic backup --compression max --tag retourenjournal-production .
)

restic forget --tag retourenjournal-production --group-by host,tags \
    --keep-daily 7 --keep-weekly 4 --prune
