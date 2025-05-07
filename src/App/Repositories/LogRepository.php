<?php
declare(strict_types=1);
namespace App\Repositories;

use App\Database;
use PDO;

class LogRepository
{
    private PDO $pdo;

    public function __construct(private Database $database)
    {
        $this->pdo = $database->getConnection(); // initialized once
    }

    public final function create(int $sensor_id): bool
    {
        $stmt = $this->pdo->prepare("
           INSERT INTO sensor_log (message, date)
           VALUES (':message', NOW())
       ");
        $message = "Sensor $sensor_id is malfunctioning. 20% temperature deviation detected!";
        $stmt->bindParam(':message', $message);

        return $stmt->execute();
    }

    public final function getAllLogs(): array
    {
        $stmt = $this->pdo->query("
            SELECT id, message, date
            FROM sensor_log
            ORDER BY date DESC
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}