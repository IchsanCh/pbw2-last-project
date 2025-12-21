<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/login', 'LoginController::index');
$routes->post('/login', 'LoginController::validateLogin');
$routes->get('/logout', 'LoginController::logout');
$routes->get('/dashboard', 'DashboardController::index');
