#!/usr/bin/env bash

source "$(dirname -- "${BASH_SOURCE[0]}")/common.sh"

if [[ $# -ne 2 || -z "$1" || "$2" != '--yes' ]]; then
    echo "Usage: $0 SNAPSHOT_ID --yes" >&2
    exit 2
fi

if ! command -v docker >/dev/null 2>&1; then
    echo 'Docker is required on the host' >&2
    exit 1
fi

if [[ ! -f "$PROJECT_ROOT/.env.production" ]]; then
    echo 'Restore .env.production first with restore-env.sh' >&2
    exit 1
fi

stage="$(mktemp -d "${TMPDIR:-/tmp}/retourenjournal-restore.XXXXXXXX")"
trap 'rm -rf -- "$stage"' EXIT

restic restore "$1" --target "$stage" --include /database.dump

if [[ ! -s "$stage/database.dump" ]]; then
    echo 'Snapshot does not contain database.dump' >&2
    exit 1
fi

running_services="$("${compose[@]}" ps --status running --services)"
while IFS= read -r service; do
    case "$service" in
        app|queue|scheduler)
            echo "Stop $service before restoring the database" >&2
            exit 1
            ;;
    esac
done <<< "$running_services"

echo 'Replacing the production PostgreSQL database from the selected snapshot.' >&2
"${compose[@]}" exec -T db sh -c '
    set -eu
    export PGPASSWORD="$POSTGRES_PASSWORD"
    case "$POSTGRES_DB" in postgres|template0|template1) exit 1;; esac
    dropdb --if-exists --force -U "$POSTGRES_USER" "$POSTGRES_DB"
    createdb -U "$POSTGRES_USER" "$POSTGRES_DB"
    exec pg_restore --no-owner --no-acl --exit-on-error --single-transaction \
        -U "$POSTGRES_USER" -d "$POSTGRES_DB"
' < "$stage/database.dump"
