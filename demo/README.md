# The live demo

This is the demo from the talk. A signup form fails and shows no error. The same question goes to the same
model twice: once in a plain Symfony app, once in the same app with Symfony Mate installed.

The question, from `prompts/form.txt`:

> I just filled out the signup form in my browser and hit save, but the page just reloaded and the account
> wasn't created. What went wrong? Be brief.

Without Mate the agent reads the code and lists what can fail. With Mate it asks the profiler what did fail
and names the field and the value.

## What is in here

```
prompts/      the question for each scenario
scenarios/    the files that turn the base app into a scenario: form (the talk) and slow
run/          the scripts, the two base apps and the Mate patch
```

Both apps are the same Symfony 7.3 app with Monolog and the web profiler. `run/app-mate` also has Symfony
Mate 1.0 BETA1 with the Symfony and Monolog extensions.

## Run it

You need PHP 8.2 or newer, Composer and the `claude` CLI.

```bash
cd run
MATE_PATCH=1 ./setup.sh form    # builds both apps under ~/mate-demo-form
./start.sh form                 # browser on :8100, plain app on :8130, app with Mate on :8131
```

Open http://127.0.0.1:8100/signup. The page on port 8100 sends every request to both apps, so both get the
same log and the same profiler data. Email and username are filled in. Type a password with at least 12
characters, tick the terms and save. The username has capital letters, so the form is rejected.

Then ask the agent, once per app:

```bash
./ask.sh plain form             # the agent in the plain app
./ask.sh mate form              # the agent in the app with Mate
```

Before the next round:

```bash
./reset.sh form                 # undoes agent edits, empties log and profiler, restarts the servers
./stop.sh form                  # stops the servers when you are done
```

`./trigger.sh form` submits the failing signup to both apps without a browser.

## A second scenario: the slow page

There is a second demo in here that was not shown in the talk. A small shop has one slow page. The question,
from `prompts/slow.txt`:

> I just clicked through the shop in my browser and one of the pages was really slow. Why? Be brief.

Without Mate the agent reads the code and guesses which page it might be. With Mate it reads the timings
that the profiler measured and names the page.

```bash
cd run
MATE_PATCH=1 ./setup.sh slow    # builds both apps under ~/mate-demo-slow
./start.sh slow
./trigger.sh slow               # clicks through the shop in both apps
./ask.sh plain slow
./ask.sh mate slow
./stop.sh slow
```

Both scenarios use the same ports, so stop one before you start the other.

## Good to know

- The model is Haiku, the small one, on purpose. A small model explores less, so the difference shows.
- `MATE_PATCH=1` adds one line to the generated block in `AGENTS.md` and one sentence to a tool description.
  That is symfony/ai pull request 2785. It is merged, but not part of 1.0 BETA1. Without it Haiku does not
  call Mate on this prompt.
- The plain app is not a strawman. Sometimes the agent digs the answer out of the raw profiler files, with
  many more steps. Mate hands the same fact over in one call.
- `ask.sh` bypasses permission prompts. The apps live in their own folder outside any real project. Do not
  point the scripts at a project you care about.
