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

//$router->get('/', function () use ($router) {
//    return $router->app->version();
//});

$router->post('/store', [
    'as' => 'store', 'uses' => 'VideoController@store'
]);

$router->get('/', 'VideoController@dashboard');
$router->get('/video', 'VideoController@index');
$router->get('/video/{id}', 'VideoController@show');
$router->get('/upload', 'VideoController@upload');
$router->get('/video/{id}/delete', 'VideoController@delete');
$router->get('/embed', 'VideoController@embed');
$router->get('/live', 'VideoController@live');
$router->get('/live/{channel}/record', 'VideoController@record');
$router->get('/ajax', 'VideoController@ajax');
$router->get('/progress', 'VideoController@progress');
$router->get('/recordings/index', 'VideoController@recordings');

$router->post('/store_video', [
    'as' => 'store', 'uses' => 'VideoController@store_video'
]);


