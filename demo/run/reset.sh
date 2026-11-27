#!/bin/bash
# reset.sh <scenario> : back to "nothing happened yet": undo agent edits, empty log + profiler, restart servers
set -euo pipefail
here="$(cd "$(dirname "$0")" && pwd)"; s=${1:?scenario}; dir="${DEMO_DIR:-$HOME/mate-demo-$s}"
"$here/stop.sh" $s
for v in plain mate; do
  rm -rf "$dir/$v/src" "$dir/$v/templates"
  cp -a "$here/app-$v/src" "$dir/$v/src"; cp -a "$here/../scenarios/$s/." "$dir/$v/"
  rm -rf "$dir/$v/trigger.sh" "$dir/$v/mock" "$dir/$v/services.sh" "$dir/$v/setup.sh"
  rm -rf "$dir/$v/var"; (cd "$dir/$v" && php bin/console cache:clear -q)
done
"$here/start.sh" $s
