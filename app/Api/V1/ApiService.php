<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Api\V1;

use Illuminate\Http\Request;
use App\Api\ApiServiceInterface;
use App\Models\{Author, Ip, Post};

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
    
    const TOP_POST_LIMIT = 20;
    
    const IP_LIST_PAGE_SIZE = 200000;
    
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
        $addr = $request->get('ip', $request->header('REMOTE_ADDR'));
        $ip = Ip::firstOrNew([
            'ip' => $addr,
        ]);
        $ip->save();
        
        Ip::whereRaw('array_position(logins, \'' . $author->login . '\') IS NULL')
                ->whereKey($addr)
                ->update(['logins' => \DB::raw('logins || ARRAY[\'' . $author->login . '\']')]);

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
        // Increment rating
        Post::where('id', $request->get('post_id'))
            ->update([
                'rating_total' => \DB::raw('rating_total + ' .  $request->get('rating')),
                'rating_count' => \DB::raw('rating_count + 1'),
            ]);
        
        // Find post
        $post = Post::find($request->get('post_id'));

        // Return average rating
        return $post->rating;
    }
    
    /**
     * Returns top rated posts
     * @return array
     */
    public function getTopPostList(): array
    {
        // Find and return top rated posts
        return Post::where('rating_count', '>', 0)
                ->orderByDesc(\DB::raw('rating_total / rating_count'))
                ->orderByDesc('rating_count')
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
        // Get ip-author pairs
        $pairs = \DB::table('ips')
                ->select(\DB::raw("ip, array_to_string(logins, ',') AS authors"))
                ->whereRaw('array_length(logins, 1) > 1')
                ->orderBy('ip')
                ->limit(static::IP_LIST_PAGE_SIZE)
                ->get();
        
        // Build resulting array of objects
        $result = [];
        foreach ($pairs as $item) {
            $result[] = [
                'ip' => $item->ip,
                'authors' => explode(',', $item->authors),
            ];
        }
        
        // Return non-assotiative array
        return $result;
    }
}
