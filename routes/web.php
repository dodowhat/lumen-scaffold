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
    $router->post('auth', 'AdminController@login');
});

$groupAttributes['middleware'] = 'auth:admin_api';

$router->group($groupAttributes, function () use ($router) {
    $router->delete('auth', 'AdminController@logout');
    $router->get('auth', 'AdminController@profile');
    $router->patch('auth/update_password', 'AdminController@updatePassword');

    $router->get('admin_users', 'AdminUserController@index');
    $router->post('admin_users', 'AdminUserController@store');
    $router->delete('admin_users/{id}', 'AdminUserController@destroy');
    $router->patch('admin_users/{id}/reset_password', 'AdminUserController@resetPassword');
    $router->patch('admin_users/{id}/assign_roles', 'AdminUserController@assignRoles');

    $router->get('admin_roles', 'AdminRoleController@index');
    $router->post('admin_roles', 'AdminRoleController@store');
    $router->delete('admin_roles/{id}', 'AdminRoleController@destroy');
    $router->patch('admin_roles/{id}/assign_actions', 'AdminRoleController@assignActions');

    $router->get('admin_actions', 'AdminActionController@index');
});

$groupAttributes = ['middleware' => 'auth:app_api'];

$router->group($groupAttributes, function () use ($router) {
    $router->post('/auth', 'AppController@login');
    $router->delete('/auth', 'AppController@logout');
    $router->get('/auth', 'AppController@profile');
});
