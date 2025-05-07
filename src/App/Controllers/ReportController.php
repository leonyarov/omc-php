<?php
declare(strict_types=1);
namespace App\Controllers;
use App\Repositories\SensorDataAggregationRepository;
use App\Repositories\SensorDataRepository;
use App\Repositories\SensorsRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ReportController
{
    public function __construct(private SensorDataAggregationRepository $sensorData,
    private SensorsRepository $sensorsRepository,)
    {
    }

    public final function getFaceWeeklyData(Request $request, Response $response): Response
    {
        $data = $this->sensorData->getFaceTemperaturesPastWeek();
        $response->getBody()->write(json_encode($data));
        return $response;
    }

    public final function getSensorWeeklyData(Request $request, Response $response, string $id): Response
    {
        $data = $this->sensorData->getSensorTemperaturesPastWeek((int)$id);
        $response->getBody()->write(json_encode($data));
        return $response;
    }

    public final function getFaultySensors(Request $request, Response $response): Response
    {
        $data = $this->sensorsRepository->getAllFaulty();
        $response->getBody()->write(json_encode($data));
        return $response;
    }
}