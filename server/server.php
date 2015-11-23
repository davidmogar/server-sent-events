<?php

    define("SECONDS_BETWEEN_ALERTS", rand(10, 60));
    define("SOURCE_NAME", "David's Alerts");

    $alerts = array(
        "Coldest Air of the Season Sweeping Through Central and Southern States",
        "Belgium on 'high alert'",
        "Multiple raids in Brussels as police seek ISIS terrorists",
        "Syria fighters may be fueled by amphetamines",
        "Alien invasion? Strange sightings in Hong Kong",
        "Experts criticise WHO delay in sounding alarm over Ebola outbreak"
    );

    $alerts_levels = array("low", "medium", "high");

    date_default_timezone_set("Europe/Madrid");
    header("Content-Type: text/event-stream\n\n");

    while (true) {
        sleep(SECONDS_BETWEEN_ALERTS);

        sendAlert($alerts[rand(0, sizeof($alerts) - 1)],
                $alerts_levels[rand(0, sizeof($alerts_levels) - 1)]);

        ob_end_flush();
        flush();
    }

    function sendAlert($message, $alert_level) {
        $date = date_create();
        $timestamp = date_timestamp_get($date);

        echo "data: { \"source\": \"" . SOURCE_NAME . "\", \"message\": \"$message\", " .
                "\"alertLevel\": \"$alert_level\", \"timestamp\": $timestamp }\n\n";
    }

?>
