<?php

namespace App\Http\Controllers;

use App\User;

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
    
    public function ipList() 
    {
        $users = User::all();
        
        return response()->json($users);
    }
}
