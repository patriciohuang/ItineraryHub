<?php
session_start();
require __DIR__ . '/../vendor/autoload.php';

use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;

$dispatcher = simpleDispatcher(function (RouteCollector $r) {
    $r->addRoute('GET', '/', ['App\Controllers\TripController', 'home']);
    $r->addRoute('GET', '/trip/add', ['App\Controllers\TripController', 'showAddTrip']);
    $r->addRoute('POST', '/trip/add', ['App\Controllers\TripController', 'addTrip']);
    $r->addRoute('GET', '/trip/{id}', ['App\Controllers\TripController', 'seeTripDetail']);
    $r->addRoute('POST', '/trip/{id}', ['App\Controllers\TripController', 'editTripDetail']);

    $r->addRoute('GET', '/trip/{id}/item/add', ['App\Controllers\TripController', 'showAddTripItem']);
    $r->addRoute('POST', '/trip/{id}/item/add', ['App\Controllers\TripController', 'addTripItem']);
    //TODO: implement edit and view trip item details
    $r->addRoute('GET', '/trip/item/{id}', ['App\Controllers\TripController', 'showTripItemDetail']);
    
    $r->addRoute('POST', '/trip/item/{id}', ['App\Controllers\TripController', 'editTripItem']);

    $r->addRoute('GET', '/login', ['App\Controllers\AuthController', 'showLogin']);
    $r->addRoute('POST', '/login', ['App\Controllers\AuthController', 'login']);
    $r->addRoute('GET', '/logout', ['App\Controllers\AuthController', 'logout']);
    $r->addRoute('GET', '/register', ['App\Controllers\AuthController', 'showRegister']);
    $r->addRoute('POST', '/register', ['App\Controllers\AuthController', 'register']);
});

$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = strtok($_SERVER['REQUEST_URI'], '?');
$routeInfo = $dispatcher->dispatch($httpMethod, $uri);

switch ($routeInfo[0]) {
    // Handle not found routes
    case FastRoute\Dispatcher::NOT_FOUND:
        http_response_code(404);
        echo 'Not Found';
        break;
    // Handle routes that were invoked with the wrong HTTP method
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        http_response_code(405);
        echo 'Method Not Allowed';
        break;
    // Handle found routes
    case FastRoute\Dispatcher::FOUND:

        $class = $routeInfo[1][0];
        $method = $routeInfo[1][1];

        $controller = new $class();

        $vars = $routeInfo[2];

        $controller->$method($vars);

        break;
}
