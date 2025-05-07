<?php
declare(strict_types=1);
namespace App\Controllers;

use App\Repositories\SensorsRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;

class SensorController
{

    public function __construct(private SensorsRepository $sensors)
    {
    }

    public function show(Request $request, Response $response): Response
    {
        $data = $this->sensors->getAll();

        $response->getBody()->write(json_encode($data));
        return $response;
    }

    public function showOne(Request $request, Response $response, string $id): Response
    {
        $data = $this->sensors->getOne((int)$id);

        if (!$data) {
            throw new HttpNotFoundException($request, message: 'sensor not found');
        }
        $response->getBody()->write(json_encode($data));
        return $response;
    }
}