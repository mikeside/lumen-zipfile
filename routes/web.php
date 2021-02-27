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

$router->get('/','FileController@index');

$router->post('/file/create','FileController@create');

$router->post('/file/del','FileController@delete');

$router->post('/file/upload_demo','FileController@uploadDemo');
