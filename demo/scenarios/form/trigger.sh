#!/bin/bash
# $1 = port. Normal noise traffic, then the one failing signup (username has an uppercase letter).
p=$1
curl -s -o /dev/null 127.0.0.1:$p/
curl -s -o /dev/null 127.0.0.1:$p/about
curl -s -o /dev/null 127.0.0.1:$p/signup
curl -s -o /dev/null -w "POST /signup -> %{http_code}\n" -X POST 127.0.0.1:$p/signup \
  --data-urlencode 'signup[email]=johannes@example.org' --data-urlencode 'signup[username]=Johannes_W' \
  --data-urlencode 'signup[password]=correct-horse-battery' --data-urlencode 'signup[acceptTerms]=1'
