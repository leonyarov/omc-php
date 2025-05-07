<?php
declare (strict_types = 1);

namespace App\Repositories;

use App\Database;
use App\DTO\NewSensorDTO;
use PDO;
use Valitron\Validator;

class SensorsRepository
{
    private PDO $pdo;

    public function __construct(private Database $database)
    {
        $this->pdo = $this->database->getConnection();
    }

    public final function getAll() : array
    {
        $stmt = $this->pdo->query("SELECT * FROM sensors");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public final function getOne(int $id) : array| bool
    {
        $stmt = $this->pdo->prepare("SELECT * FROM sensors WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public final function getAllFaulty() : array
    {
        $stmt = $this->pdo->prepare(
        "SELECT sha.sensor_id, sha.hour, sha.avg_temperature AS hourly_avg, fwa.avg_temperature AS weekly_avg
        FROM sensor_hourly_averages sha
        JOIN sensors s ON sha.sensor_id = s.id
        JOIN (
            SELECT face, AVG(avg_temperature) AS avg_temperature
            FROM face_hourly_averages
            WHERE time >= DATE_SUB(NOW(), INTERVAL 1 WEEK)
            GROUP BY face
        ) fwa ON s.face = fwa.face
        WHERE sha.avg_temperature > fwa.avg_temperature;
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public final function create(array $postData) : bool {
        $sensor = new NewSensorDTO($postData);

        $stmt = $this->pdo->prepare("
        INSERT INTO sensors (id, face, deactivated, removed) 
        VALUES (:id, :face, 0, 0)
        ");
        $stmt->bindParam(':id', $sensor->sensor_id, PDO::PARAM_INT);
        $stmt->bindParam(':face', $sensor->face, PDO::PARAM_STR);

        return $stmt->execute();
    }
    public final function deactivate(int $id) : bool {
        $stmt = $this->pdo->prepare("
            UPDATE sensors
            SET deactivated = 1
            WHERE id = :id
        ");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }
}