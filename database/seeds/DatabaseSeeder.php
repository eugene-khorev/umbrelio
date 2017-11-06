<?php

use Illuminate\Database\Seeder;
use Illuminate\Http\Request;
use Faker\Factory;
use App\Api\V1\ApiService;

class DatabaseSeeder extends Seeder
{
    const SEED_POST_COUNT = 200000;
    const SEED_LOGIN_COUNT = 100;
    const SEED_IP_COUNT = 50;
    
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(ApiService $api)
    {
         // Fake data factory
        $factory = Factory::create();
        
        // Generate IP list
        $ips = [];
        for ($i = 0; $i < static::SEED_IP_COUNT; $i++) {
            $ips[] = $factory->unique()->ipv4;
        }
        
        // Generate login list
        $logins = [];
        for ($i = 0; $i < static::SEED_LOGIN_COUNT; $i++) {
            $logins[] = $factory->unique()->firstName;
        }
        
        // Generate posts
        echo "Started...\n";
        $startedAt = microtime(true);
        for ($i = 0; $i < static::SEED_POST_COUNT; $i++) {
            // Post creation request
            $request = new Request([], [
                'title'     => $factory->sentence(3),
                'content'   => $factory->text,
                'login'     => $factory->randomElement($logins),
                'ip'        => $factory->randomElement($ips),
            ]);
            $attributes = $api->createPost($request);
            
            // Should we rate the post?
            $rates = $factory->numberBetween(1, 4);
            if ($rates % 4 == 0) {
                // Let's add a number of rates
                $rates = $factory->numberBetween(1, 4);
                while ($rates > 0) {
                    // Post rating request
                    $request = new Request([], [
                        'post_id'   => $attributes['id'],
                        'rating'    => $factory->numberBetween(1, 5),
                    ]);
                    $api->ratePost($request);
                    $rates--;
                }
            }
            
            // Display info each 1000 posts
            if ($i > 0 && $i % 1000 == 0) {
                $now = microtime(true);
                $soFar = $now - $startedAt;
                $avg = round(1000 * $soFar / $i, 1);
                echo "Done {$i} posts (avg: {$avg} ms)\n";
            }
        }
        
        // Display stats
        $finishedAt = microtime(true);
        $total = $finishedAt - $startedAt;
        $avg = round(1000 * $total / static::SEED_POST_COUNT, 1);
        
        echo "Done in {$total} sec. Average request time: {$avg} ms\n";
    }
}
