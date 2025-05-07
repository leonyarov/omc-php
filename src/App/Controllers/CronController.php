<?php
declare(strict_types=1);

namespace App\Controllers;

use App\DTO\SensorDataDTO;
use App\Repositories\LogRepository;
use App\Repositories\SensorDataAggregationRepository;
use App\Repositories\SensorDataRepository;
use App\Repositories\SensorsRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;

class CronController
{
    public function __construct(private SensorDataAggregationRepository $sensorDataAggregationRepository,
                                private SensorDataRepository            $sensorDataRepository,
                                private SensorsRepository               $sensorsRepository,
                                private LogRepository                   $logRepository,
    )
    {
    }

    public function addSensorJob(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();
        $result = $this->sensorsRepository->create($data);
        if (!$result) {
            throw new HttpBadRequestException($request, "Could not create sensor");
        }
        $response->getBody()->write(json_encode($result));
        return $response->withStatus(200);
    }

    public function aggregateDataJob(Request $request, Response $response): Response
    {
        $this->sensorDataAggregationRepository->aggregateLastHour();
        $this->sensorDataAggregationRepository->aggregateFace();
        return $response->withStatus(200);
    }

    public function checkSensorStatusJob(Request $request, Response $response): Response
    {
        $lines = $this->sensorDataRepository->checkData24Hours();
        $response->getBody()->write(json_encode("Lines changed: $lines"));
        return $response->withStatus(200);
    }

    public function checkSensorDeviationJob(Request $request, Response $response): Response
    {
        $data = $this->sensorDataRepository->checkSensorDeviation();
        $response->getBody()->write(json_encode($data));
        foreach ($data as $sensor) {
            $this->logRepository->create($sensor['sensor_id']);
        }
        return $response->withStatus(200);
    }

    # This controller is a mock of what 10000 sensors sending would be like
    public function sendSensorData(Request $request, Response $response): Response
    {
        $faulty_ids = [342, 6633, 4534, 3579];
        $removed_ids = [876, 8541];
        $sensors_data = [];
        for ($i = 1; $i <= 10000; $i++) {
            if (in_array($i, $removed_ids)) continue; //faulty

            # Temp
            $temp = mt_rand(390, 460) / 10;

            if (in_array($i, $faulty_ids) && mt_rand(1, 4) == 1) //DEVIATION
                $temp *= 1.5;

            $data = [
                'sensor_id' => $i,
                'timestamp' => time(),
                'temperature' => $temp,
                'face' => "north"
            ];
            $sensors_data[] = $data;
//            $result = $this->sensorDataRepository->insertSensorData($data);

        }

        $result = $this->sensorDataRepository->insertSensorDataBatch($sensors_data);
        if (!$result) {
            throw new HttpBadRequestException($request, "Could create sensor data");
        }

        return $response->withStatus(200);
    }
}