<?php

declare(strict_types = 1);

use App\Controllers\CronController;
use App\Controllers\ReportController;
use App\Controllers\SensorController;
use App\Controllers\SensorDataController;
use App\Middleware\AddJsonResponseHeader;
use App\Middleware\AllowAllCors;
use Slim\Factory\AppFactory;
use DI\ContainerBuilder;
use Slim\Handlers\Strategies\RequestResponseArgs;
use Slim\Routing\RouteCollectorProxy;

define ('APP_ROOT', dirname(__DIR__));
require APP_ROOT . '/vendor/autoload.php';

#Container , DI
$builder = new ContainerBuilder;
$container = $builder->addDefinitions(APP_ROOT . '/config/defenitions.php')->build();

# App
AppFactory::setContainer($container);
$app = AppFactory::create();

# Middleware
$app->addMiddleware(new AddJsonResponseHeader);
$app->addBodyParsingMiddleware();

# Collector
$collector = $app->getRouteCollector();
$collector->setDefaultInvocationStrategy(new RequestResponseArgs);

# Error handling
$error_middleware = $app->addErrorMiddleware(true, true, true);
$error_handler = $error_middleware->getDefaultErrorHandler();
$error_handler->forceContentType('application/json');

$app->get("/", function ($req, $res) {
    $res->getBody()->write(json_encode("hi!" . time()));
    return $res;
});
$app->group("/api", function (RouteCollectorProxy $api) {

    $api->group("/jobs", function (RouteCollectorProxy $job) {
        $job->post('/add_sensor', [CronController::class, 'addSensorJob']);
        $job->get('/aggregate_data', [CronController::class, 'aggregateDataJob']);
        $job->get('/deviation_sensor_check', [CronController::class, 'checkSensorDeviationJob']);
        $job->get('/status_sensor_check', [CronController::class, 'checkSensorStatusJob']);
        $job->get('/send_sensor_data', [CronController::class, 'sendSensorData']);
    });

    $api->group("/sensors", function (RouteCollectorProxy $group) {
        $group->get('', [SensorController::class, 'show']);
        $group->get('/{id:[0-9]+}', [SensorController::class, 'showOne']);
        $group->post('/data', [SensorDataController::class, 'create']);
    });

    $api->group("/reports", function (RouteCollectorProxy $group) {
        $group->get('/face', [ReportController::class, 'getFaceWeeklyData']);
        $group->get('/sensor/{id:[0-9]+}', [ReportController::class, 'getSensorWeeklyData']);
        $group->get('/faulty', [ReportController::class, 'getFaultySensors']);
    });


    $api->get('/logs', [\App\Controllers\LogController::class, 'show']);

})
    ->add(AllowAllCors::class)
    ->addMiddleware(new AddJsonResponseHeader);

$app->run();