# Symfony Mate: From Hallway Idea to Daily Driver

Talk given at **SymfonyCon Warsaw, November 2026** by Johannes Wachter.

A year ago Symfony Mate was a hallway conversation in Amsterdam. Today it is part of
[symfony/ai](https://github.com/symfony/ai) and gives coding agents access to what a
Symfony application already knows: the profiler, the logs, the container. The talk
tells how it got there, what the measurements said, and why the MCP server was removed
in favour of a CLI and skills.

**[View the slides](https://johanneswachter.dev/symfony-mate-warsaw/)**

## What is in this repo

```
index.html     the slide deck
assets/        images used by the slides
demo/          the live demo: two copies of a signup app, one with Mate
```

## The demo

A signup form fails without showing an error. The same question goes to the same
model twice: once in the plain app, once in the app with Mate installed.

Without Mate the agent reads the code and lists what can fail. With Mate it asks the
profiler what did fail and names the field and the value.

The scripts live in `demo/run/`:

```bash
cd demo/run
MATE_PATCH=1 ./setup.sh form    # builds both apps under ~/mate-demo-form
./start.sh form                 # browser on :8100 (feeds both copies), plain on :8130, with Mate on :8131
./trigger.sh form               # submits the failing signup to both apps
./ask.sh plain form             # asks the agent in the plain app
./ask.sh mate form              # asks the agent in the app with Mate
./stop.sh form
```

Requirements: PHP 8.2 or newer, Composer and the `claude` CLI.

The demo folder holds a second scenario that was not shown in the talk: a shop with one slow page.
See [demo/README.md](demo/README.md).

## Links

- Mate docs: https://symfony.com/doc/current/ai/components/mate.html
- The Mate Lab: https://johanneswachter.dev/blog/mate-lab
- Kill the MCP: https://johanneswachter.dev/blog/kill-the-mcp
- Symfony Skills prototype: https://github.com/wachterjohannes/symfony-skills

## Podcast

**[Friends with Commits](https://friendswithcommits.dev)**: the people behind the code.
A new podcast, starting in January 2027. The trailer is already out.
