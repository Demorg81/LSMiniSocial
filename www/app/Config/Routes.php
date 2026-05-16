<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'LandingController::index');

// Sign-up
$routes->get('/sign-up', 'AuthController::signUp');
$routes->post('/sign-up', 'AuthController::signUpPost');

// Sign-in
$routes->get('/sign-in', 'AuthController::signIn');
$routes->post('/sign-in', 'AuthController::signInPost');

// Sign-out
$routes->get('/sign-out', 'AuthController::signOut');
