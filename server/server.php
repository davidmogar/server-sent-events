<?php

    define("SECONDS_BETWEEN_ALERTS", 10);

    date_default_timezone_set("Europe/Madrid");
    header("Content-Type: text/event-stream\n\n");

    while (true) {
        sendAlert("Test alert");

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
