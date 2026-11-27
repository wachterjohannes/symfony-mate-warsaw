#!/bin/bash
# trigger.sh <scenario> : what "the developer did in the browser", replayed with curl on both copies.
# Live you can do the same by hand in the browser instead: http://127.0.0.1:8100/signup feeds both copies.
here="$(cd "$(dirname "$0")" && pwd)"; s=${1:?scenario}
for port in ${PLAIN_PORT:-8130} ${MATE_PORT:-8131}; do bash "$here/../scenarios/$s/trigger.sh" $port; done
