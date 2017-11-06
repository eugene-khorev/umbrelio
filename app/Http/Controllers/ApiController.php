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
    
    /**
     * Rates a post
     * @param ApiService $api
     * @param Request $request
     * @return type
     */
    public function ratePost(ApiService $api, Request $request)
    {
        // Validation
        $this->validate($request, $api::VALIDATE_RATE);
        
        // API service call
        $rating = $api->ratePost($request);
        
        // Return result
        return ['rating' => $rating];
    }
    
    /**
     * Returns top rated posts
     * @param ApiService $api
     * @param Request $request
     * @return type
     */
    public function topPosts(ApiService $api, Request $request)
    {
        // API service call
        $top = $api->getTopPostList();
        
        // Return result
        return $top;
    }
    
    /**
     * Returns list of IPs used by more than one user
     * @param ApiService $api
     * @param Request $request
     * @return type
     */
    public function ipList(ApiService $api, Request $request)
    {
        // Validation
        $this->validate($request, $api::VALIDATE_IP_LIST);
        
        // API service call
        $list = $api->getIpList($request);
        
        // Return result
        return $list;
    }
}
