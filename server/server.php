<?php

    define("ALERTS_FILE_PATH", "./alerts.txt");
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

    $file_alerts_sent = 0;

    date_default_timezone_set("Europe/Madrid");
    header("Content-Type: text/event-stream\n\n");

    while (true) {
        sleep(SECONDS_BETWEEN_ALERTS);

        $file_alerts_sent = checkFileAlerts(ALERTS_FILE_PATH, $file_alerts_sent);

        sendAlert($alerts[rand(0, sizeof($alerts) - 1)],
                $alerts_levels[rand(0, sizeof($alerts_levels) - 1)]);

        ob_end_flush();
        flush();
    }

    function checkFileAlerts($path, $lines_to_skip) {
        $handle = @fopen($path, "r");
        if ($handle) {
            $lines_skipped = 0;
            while (($line = fgets($handle)) !== false) {
                if ($lines_skipped >= $lines_to_skip) {
                    $line = str_replace("\n", "", $line);
                    $fields = explode("\t", $line);
                    if (sizeof($fields) == 3) {
                        sendAlert($fields[2], $fields[1], floatval($fields[0]));
                    }
                }

                $lines_skipped++;
            }
            fclose($handle);
        }
        return $lines_skipped;
    }

    function sendAlert($message, $alert_level, $timestamp = null) {
        if ($timestamp == null) {
            $date = date_create();
            $timestamp = date_timestamp_get($date);
        }

        echo "data: { \"source\": \"" . SOURCE_NAME . "\", \"message\": \"$message\", " .
                "\"alertLevel\": \"$alert_level\", \"timestamp\": $timestamp }\n\n";
    }

?>
