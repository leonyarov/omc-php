<?php

declare(strict_types=1);

require 'vendor/autoload.php';

use Curl\Curl;

$url = "http://localhost/api/jobs/aggregate_data";

// Initialize Curl instance
$curl = new Curl();

// Set options and make the GET request
$curl->get($url);

// Check for errors
if ($curl->error) {
    echo "Error: " . $curl->error_code . ": " . $curl->error_message . "\n";
} else {
    echo "Response: " . $curl->response . $curl->http_status_code . "\n";
}

// Close the Curl instance
$curl->close();