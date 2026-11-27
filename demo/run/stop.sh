#!/bin/bash
# stop.sh [scenario] : stops the demo servers by port (never by pattern; see measure-agent-experiment); "api" also stops the fake carrier API
ports="${PLAIN_PORT:-8130} ${MATE_PORT:-8131} ${FRONT_PORT:-8100}"; [ "${1:-}" = api ] && ports="$ports 8229"; [ "${1:-}" = apit ] && ports="$ports 8239"
for port in $ports; do
  if command -v ss >/dev/null; then pids=$(ss -ltnp 2>/dev/null | grep ":$port " | grep -o 'pid=[0-9]*' | cut -d= -f2)
  else pids=$(lsof -nP -iTCP:$port -sTCP:LISTEN -t 2>/dev/null); fi   # macOS has no ss
  [ -n "$pids" ] && kill $pids 2>/dev/null
done; true
