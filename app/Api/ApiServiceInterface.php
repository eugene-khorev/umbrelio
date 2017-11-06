<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Api;

use Illuminate\Http\Request;

/**
 *
 * @author eugene
 */
interface ApiServiceInterface
{
    public function seedDatabase();
    public function createPost(Request $attributes): array;
    public function ratePost(Request $request): int;
    public function getTopPostList(Request $request): array;
    public function getIpList(Request $request): array;
}
