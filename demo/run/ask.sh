#!/bin/bash
# ask.sh <plain|mate> [scenario, default form] [prompt-file] : the live run. Haiku, interactive, no MCP servers, prompt from ../prompts/<scenario>.txt
# Measured runs used a clean config dir (no global CLAUDE.md, skills or memory). Do the same live:
#   ~/.claude-demo is used when it exists (log in there once with /login); DEMO_CLAUDE_CONFIG names another dir.
# Permissions are bypassed so the room does not watch approval prompts; the apps live in their own throwaway dir outside any real project.
here="$(cd "$(dirname "$0")" && pwd)"; v=${1:?plain|mate}; s=${2:-form}; dir="${DEMO_DIR:-$HOME/mate-demo-$s}"
pf=${3:-$here/../prompts/$s.txt}
cfg="${DEMO_CLAUDE_CONFIG:-$HOME/.claude-demo}"; [ -d "$cfg" ] && export CLAUDE_CONFIG_DIR="$cfg"
cd "$dir/$v" && exec claude --model haiku --strict-mcp-config --permission-mode bypassPermissions "$(cat "$pf")"
