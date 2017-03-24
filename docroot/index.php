<?php
session_start();
date_default_timezone_set('Europe/Tallinn');

use Phroute\Phroute\RouteCollector;

require_once "../src/bootstrap.php";
require_once "../src/custom_functions.php";

$router = new RouteCollector();

$router->get('/', ['Controller\Welcome','defaultView']);
$router->get('/error', ['Controller\Welcome','errorView']);

// Login section
$router->get('/login', ['Controller\Login','defaultView']);
$router->post('/login', ['Controller\Login','defaultView']);
$router->get('/login/exit', ['Controller\Login','exitAction']);

// "Bookings" section
$router->get('/booking', ['Controller\Booking','defaultView']);
$router->get('/booking/add', ['Controller\Booking','addAction']);
$router->post('/booking/add', ['Controller\Booking','addAction']);
$router->get('/booking/edit/{id}', ['Controller\Booking','editAction']);
$router->post('/booking/edit/{id}', ['Controller\Booking','editAction']);
$router->get('/booking/remove/{id}', ['Controller\Booking','removeAction']);
$router->get('/booking/cancel/{id}', ['Controller\Booking','cancelAction']);

// "Garages" section
$router->get('/garage', ['Controller\Garage','defaultView']);
$router->get('/garage/add', ['Controller\Garage','addAction']);
$router->post('/garage/add', ['Controller\Garage','addAction']);
$router->get('/garage/edit/{id}', ['Controller\Garage','editAction']);
$router->post('/garage/edit/{id}', ['Controller\Garage','editAction']);
$router->get('/garage/remove/{id}', ['Controller\Garage','removeAction']);
$router->post('/garage/ajaxLinesByGarageID', ['Controller\Garage','ajaxLinesByGarageID']);

// "Statistics" section
$router->get('/stats', ['Controller\Stats','defaultView']);
$router->get('/stats/weather/mark/{id}', ['Controller\Stats','weatherAddWarning']);
$router->get('/stats/weather/unmark/{id}', ['Controller\Stats','weatherRemoveWarning']);

$dispatcher = new Phroute\Phroute\Dispatcher($router->getData());
$response = $dispatcher->dispatch($_SERVER['REQUEST_METHOD'], parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
echo $response;
