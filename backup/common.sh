#!/usr/bin/env bash

set -euo pipefail
umask 077

BACKUP_DIR="$(cd -- "$(dirname -- "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_ROOT="$(cd -- "$BACKUP_DIR/.." && pwd)"
BACKUP_ENV_FILE="${BACKUP_ENV_FILE:-/backup.env}"

if [[ ! -r "$BACKUP_ENV_FILE" ]]; then
    echo "Cannot read $BACKUP_ENV_FILE" >&2
    exit 1
fi

set -a
# This file is managed by the server administrator and must not be writable by untrusted users.
# shellcheck source=/dev/null
source "$BACKUP_ENV_FILE"
set +a

for variable in RESTIC_REPOSITORY RESTIC_PASSWORD AWS_ACCESS_KEY_ID AWS_SECRET_ACCESS_KEY; do
    if [[ -z "${!variable:-}" ]]; then
        echo "Missing $variable in $BACKUP_ENV_FILE" >&2
        exit 1
    fi
done

if ! command -v restic >/dev/null 2>&1; then
    echo 'restic is required on the host' >&2
    exit 1
fi

compose=(docker compose --env-file "$PROJECT_ROOT/.env.production" -f "$PROJECT_ROOT/docker-compose.prod.yml")
