<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

// $router->get('/', function () use ($router) {
//     return $router->app->version();
// });

$groupAttributes = [
    'prefix' => 'admin',
    'namespace' => 'Admin',
];

$router->group($groupAttributes, function () use ($router) {
    $router->post('login', 'AdminController@login');
});

$groupAttributes['middleware'] = 'auth:admin_api';

$router->group($groupAttributes, function () use ($router) {
    $router->delete('logout', 'AdminController@logout');
    $router->get('profile', 'AdminController@profile');

    $router->get('admin_users', 'AdminUserController@index');
    $router->get('admin_roles', 'AdminRoleController@index');
    $router->get('admin_actions', 'AdminActionController@index');
});

$groupAttributes = ['middleware' => 'auth:app_api'];

$router->group($groupAttributes, function () use ($router) {
    $router->post('/login', 'AppController@login');
    $router->delete('/logout', 'AppController@logout');
    $router->get('/profile', 'AppController@profile');
});
