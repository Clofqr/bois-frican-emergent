#!/bin/bash
# Shim de démarrage du site PHP servi par supervisor "frontend".
# Installe PHP si absent (le pod peut être recréé avec une image minimale)
# puis lance le serveur intégré sur le port 3000.

set -e

if ! command -v php >/dev/null 2>&1; then
    echo "[start.sh] PHP absent, installation..."
    apt-get update -qq
    apt-get install -y --no-install-recommends php-cli php-mbstring php-xml unzip
fi

exec php -S 0.0.0.0:3000 -t /app /app/.emergent_router.php
