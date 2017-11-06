<?php

namespace App\Api\V1;

use Illuminate\Http\Request;
use App\Api\ApiServiceInterface;
use App\Models\{Author, Ip, Post};

/**
 * API service implementation
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
        $author = Author::findOrCreateByLogin($request->get('login'));

        // Find/create author's IP
        $addr = $request->get('ip', $request->header('REMOTE_ADDR'));
        Ip::findOrCreateByIp($addr);
        
        // Update login list for the IP
        Ip::updateLoginList($addr, $author->login);

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
        // Increment rating and return new average rating
        return Post::incrementRatings(
                $request->get('post_id'),
                $request->get('rating')
            );
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
        // Get shared IP authors
        $sharedIpAuthors = Ip::getSharedIpAuthors(2, ',');
        
        // Build resulting array of objects
        $result = [];
        foreach ($sharedIpAuthors as $item) {
            $result[] = [
                'ip' => $item->ip,
                'authors' => explode(',', $item->authors),
            ];
        }
        
        // Return non-assotiative array
        return $result;
    }
}
