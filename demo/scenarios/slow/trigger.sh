#!/bin/bash
# $1 = port. A browser session: each page also loads the debug toolbar (/_wdt/<token>), like a real browser.
# The coffee product page (/product/17) is the slow one; the user clicked on to the cart afterwards.
p=$1
for u in / /category/tea /product/470 /category/coffee /product/17 /cart; do
  tok=$(curl -s -o /dev/null -D - 127.0.0.1:$p$u | grep -i '^x-debug-token:' | tr -d '\r' | awk '{print $2}')
  [ -n "$tok" ] && curl -s -o /dev/null 127.0.0.1:$p/_wdt/$tok
  sleep 1
done
