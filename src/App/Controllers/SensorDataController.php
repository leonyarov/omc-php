<?php
declare(strict_types=1);
namespace App\Controllers;
use App\Repositories\SensorDataRepository;

use App\Repositories\SensorsRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;
use Valitron\Validator;

class SensorDataController
{
    public function __construct(private SensorDataRepository $sensorData,
                                private SensorsRepository $sensorsRepository,)
    {
    }

    public function create(Request $request, Response $response): Response
    {
        $postData = $request->getParsedBody();

        $sensor = $this->sensorsRepository->getOne((int)$postData['sensor_id']);
        if ($sensor['deactivated'] === 1 || $sensor['removed'] === 1)
            throw new HttpBadRequestException($request, 'Sensor is removed or deactivated');

        $v = new Validator($postData);
        $v->rules([
            'required' => ['sensor_id', 'timestamp', 'temperature', 'face'],
            'integer' => ['sensor_id', 'timestamp'],
            'numeric' => ['temperature'],
        ]);
        $v->rule('in', 'face', ['north', 'south', 'east', 'west']);


        if (!$v->validate()) {
            $response->getBody()->write(json_encode(["data" => $postData, "errors"=>$v->errors()]));
            return $response->withStatus(402);
        }

        $data = $this->sensorData->insertSensorData($postData);

        $response->getBody()->write(json_encode($data));
        return $response;
    }



}