<?php

    define("SECONDS_BETWEEN_ALERTS", rand(5, 15));

    $alerts = array(
        "Coldest Air of the Season Sweeping Through Central and Southern States",
        "Belgium on 'high alert'",
        "Multiple raids in Brussels as police seek ISIS terrorists",
        "Syria fighters may be fueled by amphetamines",
        "Alien invasion? Strange sightings in Hong Kong",
        "Experts criticise WHO delay in sounding alarm over Ebola outbreak"
    );

    date_default_timezone_set("Europe/Madrid");
    header("Content-Type: text/event-stream\n\n");

    while (true) {
        sendAlert(rand(0, 5));

        ob_end_flush();
        flush();
        sleep(SECONDS_BETWEEN_ALERTS);
    }

    function sendAlert($message) {
        $date = date_create();
        $timestamp = date_timestamp_get($date);

        echo "data: { \"message\": \"$message\", \"timestamp\": $timestamp }\n\n";
    }

?>
