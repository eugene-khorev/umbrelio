<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Api\V1;

use Faker\Factory;
use App\Models\{Author, Ip, Post, Rate};

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
        $factory = Factory::create();
        
        $author = new Author;
        $author->login = $factory->unique()->firstName;
        $author->save();
        
        $ip = new Ip;
        $ip->fill([
            'author_id' => $author->id,
            'ip' => $factory->ipv4,
        ]);
        $ip->save();
        
        $post = new Post;
        $post->fill([
            'author_id' => $author->id,
            'title' => $factory->sentence(3),
            'content' => $factory->text,
        ]);
        $post->save();
        
        $rate = new Rate;
        $rate->fill([
            'post_id' => $post->id,
            'total' => 0,
            'num' => 0,
        ]);
        $rate->save();
    }
    
    public function getIpList()
    {
        return Author::all();
    }
}
