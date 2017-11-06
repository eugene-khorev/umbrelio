<?php

namespace App\Api;

use Illuminate\Http\Request;

/**
 * API service interface
 * @author eugene
 */
interface ApiServiceInterface
{
    /**
     * Creates a new post
     * @param Request $request
     * @return array
     */
    public function createPost(Request $attributes): array;
    
    /**
     * Saves rate and returns resulting rating
     * @param Request $request
     * @return float
     */
    public function ratePost(Request $request): float;
    
    /**
     * Returns top rated posts
     * @return array
     */
    public function getTopPostList(): array;
    
    /**
     * Returns list of IPs used by more than one user
     * @param Request $request
     * @return array
     */
    public function getIpList(Request $request): array;
}
