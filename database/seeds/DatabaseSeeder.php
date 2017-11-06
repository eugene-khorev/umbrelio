<?php

use Illuminate\Database\Seeder;

use App\Api\V1\ApiService;

class DatabaseSeeder extends Seeder
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
