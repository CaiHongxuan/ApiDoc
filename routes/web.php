<?php

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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group([], function () use ($router) {
    $router->get('test', ['as' => 'index', 'uses' => 'HomeController@index']);
});

$router->group(['namespace' => 'V1', 'prefix' => 'v1', 'as' => 'v1.'], function () use ($router) {

    // 项目管理
    $router->group(['as' => 'projects.', 'prefix' => 'projects'], function () use ($router) {
        $router->get('/', ['as' => 'index', 'uses' => 'ProjectController@index']);
        $router->post('/', ['as' => 'store', 'uses' => 'ProjectController@store']);
        $router->get('{id}', ['as' => 'show', 'uses' => 'ProjectController@show']);
        $router->put('{id}', ['as' => 'update', 'uses' => 'ProjectController@update']);
        $router->delete('{id}', ['as' => 'destroy', 'uses' => 'ProjectController@destroy']);
    });

    // 文章管理
    $router->group(['as' => 'docs.', 'prefix' => 'docs'], function () use ($router) {
        $router->get('/', ['as' => 'index', 'uses' => 'DocController@index']);
        $router->post('/', ['as' => 'store', 'uses' => 'DocController@store']);
        $router->get('{id}', ['as' => 'show', 'uses' => 'DocController@show']);
        $router->put('{id}', ['as' => 'update', 'uses' => 'DocController@update']);
        $router->delete('{id}', ['as' => 'destroy', 'uses' => 'DocController@destroy']);
    });

});