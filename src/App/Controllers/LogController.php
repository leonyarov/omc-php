<?php
declare(strict_types=1);
namespace App\Controllers;

use App\Repositories\LogRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class LogController
{

    public function __construct(private LogRepository $logRepository)
    {
    }

    public function show(Request $request, Response $response): Response
    {
        $data = $this->logRepository->getAllLogs();
        $response->getBody()->write(json_encode($data));
        return $response;
    }


}