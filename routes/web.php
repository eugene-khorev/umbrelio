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

use App\Api\V1\ApiService;

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'api/v1'], function($app)
{
    // Creates a new post
    $app->post('post', 'ApiController@createPost');
    
    // Rates a post
    $app->get('rate', function(ApiService $api) {
        return response()->json(
            $api->getIpList()
        );
    });
    
    // Returns top rated posts
    $app->get('top', function(ApiService $api) {
        return response()->json(
            $api->getIpList()
        );
    });
    
    // Returns list of IPs used by more than one user
    $app->get('ips', function(ApiService $api) {
        return response()->json(
            $api->getIpList()
        );
    });
});