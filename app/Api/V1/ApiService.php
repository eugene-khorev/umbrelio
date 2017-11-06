<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Api\V1;

use Illuminate\Http\Request;
use Faker\Factory;
use App\Api\ApiServiceInterface;
use App\Models\{Author, Ip, Post, Rate};

/**
 * Description of Service
 *
 * @author eugene
 */
class ApiService implements ApiServiceInterface
{
    const VALIDATE_POST = [
        'title'     => 'required|string|between:1,255',
        'content'   => 'required|string|between:1,4095',
        'login'     => 'required|alpha_dash|between:3,50',
        'ip'        => 'ip',
    ];
    
    const VALIDATE_RATE = [
        'post_id'   => 'required|exists:posts,id',
        'rating'    => 'required|integer|min:1|max:5',
    ];
    
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
    
    /**
     * Creates a new post
     * @param Request $request
     * @return array
     */
    public function createPost(Request $request): array
    {
        // Find/create author
        $author = Author::firstOrNew([
            'login' => $request->get('login'),
        ]);
        $author->save();
        
        // Find/create author's IP
        $ip = Ip::firstOrNew([
            'author_id' => $author->id,
            'ip'        => $request->get('ip', $request->header('REMOTE_ADDR')),
        ]);
        $ip->save();
        
        // Create a new post
        $post = new Post;
        $post->fill([
            'author_id' => $author->id,
            'title'     => $request->get('title'),
            'content'   => $request->get('content'),
        ]);
        $post->save();
        
        // Return array of post attributes
        return $post->toArray();
    }
    
    /**
     * Saves rate and returns resulting rating
     * @param Request $request
     * @return float
     */
    public function ratePost(Request $request): float
    {
        // Find/create rate
        $rate = Rate::firstOrNew([
            'post_id' => $request->get('post_id')
        ]);
        
        // Increment sum and number of all ratings
        $rate->increment('total', $request->get('rating'));
        $rate->increment('num');
        $rate->save();
        
        // Return average rating
        return $rate->num
                ? round($rate->total / $rate->num, 1)
                : $request->get('rating');
    }
    
    public function getTopPostList(Request $request): array
    {
        return Author::all();
    }
    
    public function getIpList(Request $request): array
    {
        return Author::all();
    }
}
