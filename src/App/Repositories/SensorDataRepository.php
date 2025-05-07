<?php
declare(strict_types=1);
namespace App\Repositories;

use App\Database;
use App\DTO\SensorDataDTO;
use PDO;

class SensorDataRepository
{
    private PDO $pdo;

    public function __construct(private Database $database)
    {
        $this->pdo = $this->database->getConnection();

    }


    public final function getSingleSensor(int $id) : array | bool
    {
        $stmt = $this->pdo->prepare("SELECT * FROM sensor_data WHERE sensor_id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public final function insertSensorData(array $postData) : bool
    {
        $dto = new SensorDataDTO($postData);

        $stmt = $this->pdo->prepare("INSERT INTO sensor_data (sensor_id, timestamp, temperature) VALUES (:id, FROM_UNIXTIME(:time), :temp)");
        $stmt->bindValue(":id", $dto->sensor_id, PDO::PARAM_INT);
        $stmt->bindValue(":time", $dto->timestamp, PDO::PARAM_INT);
        $stmt->bindValue(":temp", $dto->temperature);
        return $stmt->execute();
    }
    # Mock,
    public final function insertSensorDataBatch(array $dataBatch): bool
    {
        $placeholders = [];
        $values = [];

        foreach ($dataBatch as $data) {
            $dto = new SensorDataDTO($data);
            $placeholders[] = "(?, FROM_UNIXTIME(?), ?)";
            $values[] = $dto->sensor_id;
            $values[] = $dto->timestamp;
            $values[] = $dto->temperature;
        }

        $sql = "INSERT INTO sensor_data (sensor_id, timestamp, temperature) VALUES " . implode(", ", $placeholders);
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute($values);
    }

    public final function checkData24Hours() : int {
        $sql = "UPDATE sensors
                SET removed = 1
                WHERE id NOT IN (
                    SELECT DISTINCT sensor_id
                    FROM sensor_data
                    WHERE timestamp >= NOW() - INTERVAL 1 DAY
                );";

        $stmt = $this->pdo->query($sql);
        $stmt->execute();
        return $stmt->rowCount();
    }

    public final function checkSensorDeviation(): array
    {
        $sql = "
                SELECT sha.sensor_id, sha.hour, sha.avg_temperature
                FROM sensor_hourly_averages sha
                JOIN face_hourly_averages fha
                ON DATE_FORMAT(sha.hour, '%Y-%m-%d %H:00:00') = DATE_FORMAT(fha.time, '%Y-%m-%d %H:00:00')
                AND sha.sensor_id IN (
                    SELECT id FROM sensors WHERE face = fha.face
                )
                WHERE sha.avg_temperature > fha.avg_temperature;
                ";

        $stmt = $this->pdo->query($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}