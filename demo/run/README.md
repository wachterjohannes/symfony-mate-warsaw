# Mate live-demo kit (demo search, Oct 2026)

Two copies of the same Symfony 7.3 app (Monolog dev.log at debug, web profiler), one with Mate 1.0 BETA1
(symfony + monolog extensions). Scenario overlays in `../scenarios/<s>`, prompts in `../prompts/<s>.txt`.

    ./setup.sh form            # builds ~/mate-demo-form/{plain,mate}; MATE_PATCH=1 adds the proposed Mate changes
    ./start.sh form            # browser :8100 (tee.php, feeds both copies), plain :8130, mate :8131 (FRONT_PORT/PLAIN_PORT/MATE_PORT to change)
    ./trigger.sh form          # replays "what the developer did in the browser" on both (or click it yourself)
    ./ask.sh plain form        # Haiku without Mate
    ./ask.sh mate form         # Haiku with Mate
    ./reset.sh form            # undo agent edits, empty log + profiler, restart (then trigger again)
    ./stop.sh form             # stop the servers (api/apit also stop the fake carrier API)

Scenarios: form (signup rejected), slow (one slow page), api / apit (carrier API answer).
Do not run this while a measurement campaign uses the fake carrier API ports 8229/8239.
