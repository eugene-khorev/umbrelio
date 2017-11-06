<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Api\V1\ApiService;

class ApiController extends Controller
{
    /**
     * Creates a new post
     * @param ApiService $api
     * @param Request $request
     * @return type
     */
    public function createPost(ApiService $api, Request $request)
    {
        // Validation
        $this->validate($request, $api::VALIDATE_POST);
        
        // API service call
        $attributes = $api->createPost($request);
        
        // Return result
        return $attributes;
    }
}
