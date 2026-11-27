#!/bin/bash
# start.sh <scenario> : plain on 8130, mate on 8131, the browser entry on 8100 (tee.php sends every request to both copies).
# nohup instead of setsid: macOS has no setsid.
set -euo pipefail
here="$(cd "$(dirname "$0")" && pwd)"; s=${1:?scenario}; dir="${DEMO_DIR:-$HOME/mate-demo-$s}"
export PHP_CLI_SERVER_WORKERS=4
[ -x "$here/../scenarios/$s/services.sh" ] && "$here/../scenarios/$s/services.sh"
for pair in plain:${PLAIN_PORT:-8130} mate:${MATE_PORT:-8131}; do v=${pair%%:*}; port=${pair##*:}
  # the whole subshell is redirected, so no helper process keeps the caller's stdout open (a pipe on start.sh would never close)
  # no router script: php -S then serves files from public/ itself and hands everything else to index.php
  (cd "$dir/$v" && nohup php -S 127.0.0.1:$port -t public &) >"$dir/$v.log" 2>&1 </dev/null
  echo $port > "$dir/$v.port"
done
(nohup php -S 127.0.0.1:${FRONT_PORT:-8100} "$here/tee.php" &) >"$dir/front.log" 2>&1 </dev/null
sleep 1.5
echo "browser: http://127.0.0.1:${FRONT_PORT:-8100}   (plain: ${PLAIN_PORT:-8130}, mate: ${MATE_PORT:-8131})"
