#!/bin/bash
# setup.sh <scenario: form|slow|api>   builds $DEMO_DIR (default ~/mate-demo-<scenario>)/{plain,mate}
#   plain/ : the Symfony app with Monolog (var/log/dev.log at debug) and the web profiler
#   mate/  : the same app + symfony/ai-mate 1.0 BETA1 with the symfony and monolog extensions, `mate discover` run
# MATE_PATCH=1 additionally copies the changes from symfony/ai PR #2785 (patch/, merged 2026-11-11, not in 1.0 BETA1) into mate/vendor.
# Drop the patch once the demo runs on a release that contains it (BETA2 or later).
# Keep DEMO_DIR outside of any folder with a CLAUDE.md, or it loads into both runs.
set -euo pipefail
here="$(cd "$(dirname "$0")" && pwd)"; s=${1:?scenario}
dir="${DEMO_DIR:-$HOME/mate-demo-$s}"
rm -rf "$dir" && mkdir -p "$dir"
for v in plain mate; do
  cp -a "$here/app-$v" "$dir/$v"
  cp -a "$here/../scenarios/$s/." "$dir/$v/"
  rm -rf "$dir/$v/trigger.sh" "$dir/$v/mock" "$dir/$v/services.sh" "$dir/$v/setup.sh"
  [ -f "$here/../scenarios/$s/setup.sh" ] && (cd "$dir/$v" && bash "$here/../scenarios/$s/setup.sh" $v)
  (cd "$dir/$v" && composer install --no-interaction --quiet)
done
# mate init pinned the PHP of the machine it ran on; Mate refuses to read the app under another major.minor.
pv=$(php -r 'echo PHP_MAJOR_VERSION.".".PHP_MINOR_VERSION;')
perl -pi -e "s/->set\('mate.php_version', '[0-9.]+'\)/->set('mate.php_version', '$pv')/" "$dir/mate/mate/config.php"
if [ "${MATE_PATCH:-0}" = 1 ]; then
  cp "$here/patch/AgentInstructionsMaterializer.php" "$dir/mate/vendor/symfony/ai-mate/src/Agent/"
  cp "$here/patch/ProfilerTool.php" "$dir/mate/vendor/symfony/ai-symfony-mate-extension/Capability/"
fi
(cd "$dir/mate" && vendor/bin/mate discover >/dev/null)
echo "ready in $dir (plain/, mate/). Next: ./start.sh $s"
