<?php

declare(strict_types=1);

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class AllowAllCors implements MiddlewareInterface
{
    public function process(Request $request,  RequestHandler $handler) : Response
    {
        //DISABLE THIS MIDDLEWARE IN PRODUCTION!
        $response = $handler->handle($request);

        return $response
            ->withHeader('Access-Control-Allow-Origin', '*') // Replace '*' with your frontend's URL
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Credentials', 'true'); // If credentials are needed
    }
}
