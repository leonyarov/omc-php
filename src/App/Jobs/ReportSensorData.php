<?php

declare(strict_types=1);
require 'vendor/autoload.php';

use Curl\Curl;

$url = "http://localhost/api/jobs/send_sensor_data";

$curl = new Curl();

$curl->get($url);

if ($curl->error) {
    echo "Error: " . $curl->error_code . ": " . $curl->error_message . "\n";
} else {
    echo "Response: " . $curl->response . $curl->http_status_code . "\n";
}

$curl->close();
