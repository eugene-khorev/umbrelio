<?php

use Illuminate\Database\Seeder;

use App\Api\V1\ApiService;

class PostTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(ApiService $api)
    {
        $api->seedDatabase();
    }
}
