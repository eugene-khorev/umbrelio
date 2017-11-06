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
    
    const VALIDATE_IP_LIST = [
        'page'      => 'integer',
    ];
    
    const TOP_POST_LIMIT = 3;
    
    const IP_LIST_PAGE_SIZE = 3;
    
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
    
    /**
     * Returns top rated posts
     * @return array
     */
    public function getTopPostList(): array
    {
        // Find and return top rated posts
        return Rate::with('post')
                ->orderByDesc(\DB::raw('total / num'))
                ->limit(static::TOP_POST_LIMIT)
                ->get()
                ->toArray();
    }
    
    /**
     * Returns list of IPs used by more than one user
     * @param Request $request
     * @return array
     */
    public function getIpList(Request $request): array
    {
        // Calculate page offset
        $page = ($request->get('page', 1) - 1);
        $offset = static::IP_LIST_PAGE_SIZE * $page;
        
        // Get page of IP addresses
        $ips = Ip::select('ip')
                ->groupBy('ip')
                ->having(\DB::raw('COUNT(author_id)'), '>', 1)
                ->orderBY('ip')
                ->offset($offset)
                ->limit(static::IP_LIST_PAGE_SIZE)
                ->get();
        
        // Get authors
        $pairs = Ip::whereIn('ip', $ips)
                ->leftJoin('authors', 'authors.id', '=', 'ips.author_id')
                ->orderBY('ip')
                ->get();
        
        // Build resulting array of objects
        $result = [];
        foreach ($pairs as $author) {
            // Check if there is no data for the IP
            if (!isset($result[$author->ip])) {
                $result[$author->ip] = [
                    'ip' => $author->ip,
                    'authors' => [],
                ];
            }
            
            // Add a new author
            $result[$author->ip]['authors'][] = $author->login;
        }
        
        // Return non-assotiative array
        return array_values($result);
    }
}
