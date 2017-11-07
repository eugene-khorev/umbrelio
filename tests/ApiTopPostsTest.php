<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class ApiTopPostsTest extends TestCase
{
    /**
     * Test request method validation
     *
     * @return void
     */
    public function testInvalidRequestMethod()
    {
        $response = $this->call('POST', '/api/v1/top');
        $this->assertEquals(405, $response->status());
    }
    
    /**
     * Test top post list
     *
     * @return void
     */
    public function testValidRequest()
    {
        // Check correct JSON structure
        $response = $this->json('GET', '/api/v1/top');
        $response->assertResponseStatus(200);
        $response->seeJsonStructure([
            [
                'id',
                'author_id',
                'ip',
                'title',
                'content',
                'rating_total',
                'rating_count',
                'rating',
            ]
        ]);
    }
}
