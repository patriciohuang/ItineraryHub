<?php
session_start();
require __DIR__ . '/../vendor/autoload.php';

use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;
use App\Core\Container;

$container = new Container();

$container->bind('App\Services\IUserService', 'App\Services\UserService');
$container->bind('App\Services\ITripService', 'App\Services\TripService');
$container->bind('App\Services\IMembershipService', 'App\Services\MembershipService');
$container->bind('App\Services\ITripItemService', 'App\Services\TripItemService');
$container->bind('App\Repositories\IUserRepository', 'App\Repositories\UserRepository');
$container->bind('App\Repositories\ITripRepository', 'App\Repositories\TripRepository');
$container->bind('App\Repositories\IMembershipRepository', 'App\Repositories\MembershipRepository');
$container->bind('App\Repositories\ITripItemRepository', 'App\Repositories\TripItemRepository');

$dispatcher = simpleDispatcher(function (RouteCollector $r) {
    $r->addRoute('GET', '/', ['App\Controllers\HomeController', 'homeView']);
    $r->addRoute('GET', '/trip/shared', ['App\Controllers\HomeController', 'sharedTripsView']);
    $r->addRoute('GET', '/trip/following', ['App\Controllers\HomeController', 'followingTripsView']);
    $r->addRoute('GET', '/notifications', ['App\Controllers\HomeController', 'notificationsView']);

    $r->addRoute('GET', '/trip/join', ['App\Controllers\MembershipController', 'joinConfirmationView']);
    $r->addRoute('POST', '/trip/join/confirm', ['App\Controllers\MembershipController', 'processJoinDecision']);
    $r->addRoute('GET', '/api/trip/generate-invite', ['App\Controllers\MembershipController', 'getInviteLinkAPI']);
    
    $r->addRoute('GET', '/trip/add', ['App\Controllers\TripController', 'addTripView']);
    $r->addRoute('POST', '/trip/add', ['App\Controllers\TripController', 'addTrip']);
    $r->addRoute('GET', '/trip/{id}', ['App\Controllers\TripController', 'tripDetailView']);
    $r->addRoute('POST', '/trip/{id}', ['App\Controllers\TripController', 'editTripDetail']);
    $r->addRoute('POST', '/trip/delete/{id}', ['App\Controllers\TripController', 'deleteTrip']);
    
    $r->addRoute('POST', '/trip/{id}/item/review', ['App\Controllers\TripItemController', 'reviewItem']);
    $r->addRoute('POST', '/trip/{id}/item/suggest', ['App\Controllers\TripItemController', 'suggestItem']);
    $r->addRoute('POST', '/trip/item/{id:\d+}/process', ['App\Controllers\TripItemController', 'processSuggestedItem']);
    $r->addRoute('POST', '/trip/item/{id}/participant/add', ['App\Controllers\TripItemController', 'addParticipantToItem']);
    $r->addRoute('POST', '/trip/item/{id}/participant/remove', ['App\Controllers\TripItemController', 'removeParticipantFromItem']);
    $r->addRoute('POST', '/trip/{id}/item/add', ['App\Controllers\TripItemController', 'addTripItem']);
    $r->addRoute('GET', '/trip/item/{id}', ['App\Controllers\TripItemController', 'itemDetailView']);
    $r->addRoute('POST', '/trip/item/{id}', ['App\Controllers\TripItemController', 'editTripItem']);
    $r->addRoute('POST', '/trip/item/delete/{id}', ['App\Controllers\TripItemController', 'deleteTripItem']);

    $r->addRoute('GET', '/login', ['App\Controllers\AuthController', 'loginView']);
    $r->addRoute('POST', '/login', ['App\Controllers\AuthController', 'login']);
    $r->addRoute('GET', '/logout', ['App\Controllers\AuthController', 'logout']);
    $r->addRoute('GET', '/register', ['App\Controllers\AuthController', 'registerView']);
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
        $vars = $routeInfo[2];

        try {
            $controller = $container->get($class);
            $controller->$method($vars);
        } catch (Exception $e) {
            echo "Dependency Error: " . $e->getMessage();
            exit;
        }
        break;
}
