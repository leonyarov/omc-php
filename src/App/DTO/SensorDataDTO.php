<?php
declare(strict_types=1);
namespace App\DTO;


class SensorDataDTO {
    public int $sensor_id;
    public int $timestamp;
    public float $temperature;
    public string $face;

    public function __construct(array $data) {
        $this->sensor_id = (int) $data['sensor_id'];
        $this->timestamp = (int) $data['timestamp'];
        $this->temperature = (float) $data['temperature'];
        $this->face = $data['face'];
    }

}