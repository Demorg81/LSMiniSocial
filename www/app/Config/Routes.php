<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Route-Map-LSM-Core

$routes->get('/', 'LandingController::index');

// Sign-up
$routes->get('/sign-up', 'AuthController::signUp');
$routes->post('/sign-up', 'AuthController::signUpPost');

// Sign-in
$routes->get('/sign-in', 'AuthController::signIn');
$routes->post('/sign-in', 'AuthController::signInPost');

// Sign-out
$routes->get('/sign-out', 'AuthController::signOut');

// Protected routes
$routes->get('/home', 'HomeController::index', ['filter' => 'auth:You must be logged in to access the feed.']);

$routes->get('/post/create', 'PostController::create', ['filter' => 'auth']);
$routes->get('/post/edit/(:num)', 'PostController::edit/$1', ['filter' => 'auth']);
$routes->post('/post/create', 'PostController::store', ['filter' => 'auth']);
$routes->post('/post/edit/(:num)', 'PostController::update/$1', ['filter' => 'auth']);
$routes->post('/post/delete/(:num)', 'PostController::delete/$1', ['filter' => 'auth']);

$routes->get('/profile', 'ProfileController::index', ['filter' => 'auth']);
$routes->post('/profile', 'ProfileController::update', ['filter' => 'auth']);

// API routes
$routes->get('/posts', 'Api\PostApiController::index', ['filter' => 'auth']);
$routes->post('/posts', 'Api\PostApiController::create', ['filter' => 'auth']);
$routes->get('/posts/(:num)', 'Api\PostApiController::show/$1', ['filter' => 'auth']);
$routes->put('/posts/(:num)', 'Api\PostApiController::update/$1', ['filter' => 'auth']);
$routes->delete('/posts/(:num)', 'Api\PostApiController::delete/$1', ['filter' => 'auth']);

$routes->post('/posts/(:num)/like', 'Api\InteractionApiController::addLike/$1', ['filter' => 'auth']);
$routes->delete('/posts/(:num)/like', 'Api\InteractionApiController::removeLike/$1', ['filter' => 'auth']);
$routes->get('/posts/(:num)/comments', 'Api\InteractionApiController::getComments/$1', ['filter' => 'auth']);
$routes->post('/posts/(:num)/comments', 'Api\InteractionApiController::addComment/$1', ['filter' => 'auth']);
$routes->delete('/comments/(:num)', 'Api\InteractionApiController::deleteComment/$1', ['filter' => 'auth']);

$routes->post('/ai/improve', 'AiController::improve', ['filter' => 'auth']);