<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class ApiRatingTest extends TestCase
{
    /**
     * Test request method validation
     *
     * @return void
     */
    public function testInvalidRequestMethod()
    {
        $response = $this->call('GET', '/api/v1/rate', ['post_id' => 1, 'rating' => 1]);
        $this->assertEquals(405, $response->status());
    }
    
    /**
     * Test validation
     *
     * @return void
     */
    public function testValidationNoParams()
    {
        // Check validation errors
        $response = $this->json('POST', '/api/v1/rate', []);
        $response->assertResponseStatus(422);
        $response->seeJsonEquals([
            'errors' => [
                'post_id' => [
                    'The post id field is required.'
                ],
                'rating' => [
                    'The rating field is required.'
                ]
            ]
        ]);
    }
    
    /**
     * Test validation
     *
     * @return void
     */
    public function testValidationInvalidParamTypes()
    {
        $response = $this->json('POST', '/api/v1/rate', ['post_id' => 'abc', 'rating' => 'xyz']);
        $response->assertResponseStatus(422);
        $response->seeJsonEquals([
            'errors' => [
                'post_id' => [
                    'The post id must be an integer.'
                ],
                'rating' => [
                    'The rating must be an integer.'
                ]
            ]
        ]);
    }
    
    /**
     * Test validation
     *
     * @return void
     */
    public function testValidationInvalidParamValues()
    {
        $response = $this->json('POST', '/api/v1/rate', ['post_id' => -1, 'rating' => -2]);
        $response->assertResponseStatus(422);
        $response->seeJsonEquals([
            'errors' => [
                'post_id' => [
                    'The selected post id is invalid.'
                ],
                'rating' => [
                    'The rating must be at least 1.'
                ]
            ]
        ]);
    }
    
    /**
     * Test validation
     *
     * @return void
     */
    public function testValidationInvalidRatingRange()
    {
        $response = $this->json('POST', '/api/v1/rate', ['post_id' => -1, 'rating' => 12]);
        $response->assertResponseStatus(422);
        $response->seeJsonEquals([
            'errors' => [
                'post_id' => [
                    'The selected post id is invalid.'
                ],
                'rating' => [
                    'The rating may not be greater than 5.'
                ]
            ]
        ]);
    }
    
    /**
     * Test post rating
     *
     * @return void
     */
    public function testValidRequest()
    {
        // Check correct answer
        $response = $this->json('POST', '/api/v1/rate', ['post_id' => 1, 'rating' => 1]);
        $response->assertResponseStatus(200);
        $response->seeJsonStructure([
            'rating'
        ]);
    }
}
