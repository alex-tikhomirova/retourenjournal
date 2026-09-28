#!/usr/bin/env bash

source "$(dirname -- "${BASH_SOURCE[0]}")/common.sh"

if [[ $# -ne 2 || -z "$1" || "$2" != '--yes' ]]; then
    echo "Usage: $0 SNAPSHOT_ID --yes" >&2
    exit 2
fi

stage="$(mktemp -d "${TMPDIR:-/tmp}/retourenjournal-restore.XXXXXXXX")"
trap 'rm -rf -- "$stage"' EXIT

restic restore "$1" --target "$stage" \
    --include /.env.production \
    --include /backend/.env.production \
    --include /frontend/.env.production

for file in .env.production backend/.env.production frontend/.env.production; do
    if [[ ! -f "$stage/$file" ]]; then
        echo "Snapshot does not contain $file" >&2
        exit 1
    fi
done

install -m 600 "$stage/.env.production" "$PROJECT_ROOT/.env.production"
install -m 600 "$stage/backend/.env.production" "$PROJECT_ROOT/backend/.env.production"
install -m 600 "$stage/frontend/.env.production" "$PROJECT_ROOT/frontend/.env.production"

echo 'Restored all three production env files.'
