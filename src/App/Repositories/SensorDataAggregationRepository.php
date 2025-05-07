<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Database;
use PDO;

class SensorDataAggregationRepository
{
    private PDO $pdo;

    public function __construct(private Database $database)
    {
        $this->pdo = $database->getConnection(); // initialized once
    }

    public final function aggregateLastHour(): bool|int
    {
        $sql = "
            REPLACE INTO sensor_hourly_averages (sensor_id, hour, avg_temperature)
            SELECT
                sensor_id,
                DATE_FORMAT(NOW(), '%Y-%m-%d %H:00:00') AS hour,
                AVG(temperature)
            FROM sensor_data
            WHERE timestamp >= DATE_SUB(NOW(), INTERVAL 1 HOUR)
              AND timestamp < NOW()
            GROUP BY sensor_id
        ";
        $result = $this->pdo->exec($sql);

//        // Remove data and leave 2 hours worth of data
//        $deleteSql = "
//             DELETE FROM sensor_data
//            WHERE timestamp < DATE_SUB(NOW(), INTERVAL 2 HOUR)
//            ";
//        $this->pdo->exec($deleteSql);

        return $result;
    }

    public final function aggregateFace(): bool|int
    {
        $sql = "REPLACE INTO face_hourly_averages (face, time, avg_temperature)
                SELECT
                    s.face,
                    DATE_FORMAT(NOW(), '%Y-%m-%d %H:00:00') AS time,
                    AVG(sd.temperature) AS avg_temperature
                FROM sensor_data sd
                JOIN sensors s ON s.id = sd.sensor_id
                WHERE sd.timestamp >= DATE_SUB(NOW(), INTERVAL 1 HOUR)
                  AND sd.timestamp < NOW()
                GROUP BY s.face; 
                ";
        $result = $this->pdo->exec($sql);

        //remove all data and leave past 2 hours
//        $deleteSql = "
//                    DELETE FROM sensor_data
//                    WHERE timestamp < DATE_SUB(NOW(), INTERVAL 2 HOUR)
//                ";
//        $this->pdo->exec($deleteSql);
        return $result;
    }

    public final function getFaceTemperaturesPastWeek(): array
    {
        $sql = "
        SELECT fha.face, fha.time AS timestamp, fha.avg_temperature AS temperature
        FROM face_hourly_averages fha
        WHERE fha.time >= DATE_SUB(NOW(), INTERVAL 1 WEEK)
        ORDER BY fha.face, fha.time;
    ";

        $stmt = $this->pdo->query($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public final function getSensorTemperaturesPastWeek(int $sensorId): array
    {
        $sql = "
        SELECT sha.hour AS timestamp, sha.avg_temperature AS temperature
        FROM sensor_hourly_averages sha
        WHERE sha.sensor_id = :sensorId
          AND sha.hour >= DATE_SUB(NOW(), INTERVAL 1 WEEK)
        ORDER BY sha.hour;
    ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':sensorId', $sensorId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}