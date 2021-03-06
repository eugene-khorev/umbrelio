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

$router->group(['prefix' => 'api/v1'], function($app)
{
    // Creates a new post
    $app->post('post', 'ApiController@createPost');
    
    // Rates a post
    $app->post('rate', 'ApiController@ratePost');
    
    // Returns top rated posts
    $app->get('top', 'ApiController@topPosts');
    
    // Returns list of IPs used by more than one user
    $app->get('ips', 'ApiController@ipList');
});