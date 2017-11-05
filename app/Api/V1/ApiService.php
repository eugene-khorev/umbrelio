<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Api\V1;

use App\User;

/**
 * Description of Service
 *
 * @author eugene
 */
class ApiService
{
    public function __construct()
    {
        //
    }
    
    public function seedDatabase()
    {
//        factory(User::class, 100)->create()->each(function ($u) {
//            $u->posts()->save(factory(App\Post::class)->make());
//        });
    }
    
    public function getIpList()
    {
        return User::all();
    }
}
