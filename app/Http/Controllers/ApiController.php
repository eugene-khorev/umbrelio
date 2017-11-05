<?php

namespace App\Http\Controllers;

use App\Api\V1\ApiService;

class ApiController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
    
    public function ipList(ApiService $api) 
    {
        $users = $api->getIpList();
        
        return response()->json($users);
    }
}
