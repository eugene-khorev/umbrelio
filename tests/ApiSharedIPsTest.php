<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class ApiSharedIpsTest extends TestCase
{
    /**
     * Test request method validation
     *
     * @return void
     */
    public function testInvalidRequestMethod()
    {
        $response = $this->call('POST', '/api/v1/ips');
        $this->assertEquals(405, $response->status());
    }
    
    /**
     * Test shared IP list
     *
     * @return void
     */
    public function testSharedIps()
    {
        // Check correct JSON structure
        $response = $this->json('GET', '/api/v1/ips');
        $response->assertResponseStatus(200);
        $response->seeJsonStructure([
            [
                'ip',
                'authors' => [],
            ]
        ]);
    }
}
