<?php
declare(strict_types=1);
namespace App\DTO;


class NewSensorDTO {
    public int $sensor_id;
    public string $face;

    public function __construct(array $data) {
        $this->sensor_id = (int) $data['sensor_id'];
        $this->face = $data['face'];
    }
}