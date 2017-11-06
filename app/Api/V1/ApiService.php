<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Api\V1;

use App\Models\Author;

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
        factory(Author::class, 100)->create()/*->each(function ($u) {
            $u->posts()->save(factory(App\Post::class)->make());
        })*/;
    }
    
    public function getIpList()
    {
        return Author::all();
    }
}
