<?php

declare(strict_types=1);


// ======================
// Bootstrap
// ======================
require_once __DIR__ . '/../vendor/autoload.php';

use App\Controller\GraphQL;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;

// ======================
// CORS Configuration
// ======================
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json; charset=UTF-8");


if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// ======================
// Route Configuration
// ======================
$dispatcher = simpleDispatcher(
    function (RouteCollector $r) {
        $r->addRoute('POST', '/graphql', [GraphQL::class, 'handle']);
    }
);

// ======================
// Request Handling
// ======================
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);

// ======================
// Response Handling
// ======================
switch ($routeInfo[0]) {
    case Dispatcher::NOT_FOUND:
        http_response_code(404);
        echo json_encode(['error' => 'Not Found']);
        break;

    case Dispatcher::METHOD_NOT_ALLOWED:
        http_response_code(405);
        echo json_encode(['error' => 'Method Not Allowed']);
        break;

    case Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2] ?? [];

        try {
            $response = call_user_func($handler, $vars);
            echo $response;
        } catch (Throwable $e) {
            http_response_code(500);
            error_log('Handler Error: ' . $e->getMessage());
            echo json_encode(['error' => 'Internal Server Error']);
        }
        break;
}