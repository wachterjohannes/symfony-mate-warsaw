#!/bin/bash
# rec.sh <plain|mate> [scenario, default form] : the same live run as ask.sh, recorded with asciinema.
# The recording ends when you leave Claude with /exit. Turn a cast into a GIF with: agg <file>.cast <file>.gif
here="$(cd "$(dirname "$0")" && pwd)"; v=${1:?plain|mate}; s=${2:-form}
out="$here/../recordings/$s-$v-$(date +%Y%m%d-%H%M%S).cast"
mkdir -p "$(dirname "$out")"
asciinema rec -c "$here/ask.sh $v $s" "$out" && echo "saved: $out"
