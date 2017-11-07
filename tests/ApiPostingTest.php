<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class ApiPostingTest extends TestCase
{
    /**
     * Test request method validation
     *
     * @return void
     */
    public function testInvalidRequestMethod()
    {
        $response = $this->call('GET', '/api/v1/post', ['post_id' => -11, 'rating' => 12]);
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
        $response = $this->json('POST', '/api/v1/post', []);
        $response->assertResponseStatus(422);
        $response->seeJsonEquals([
            'errors' => [
                'content' => [
                    'The content field is required.'
                ],
                'login' => [
                    'The login field is required.'
                ],
                'title' => [
                    'The title field is required.'
                ],
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
        // Check validation errors
        $response = $this->json('POST', '/api/v1/post', [
                'title'     => [1 ,2, 3], 
                'content'   => false,
                'login'     => -123.45,
                'ip'        => '321.321.321.321',
            ]);
        $response->assertResponseStatus(422);
        $response->seeJsonEquals([
            'errors' => [
                'content' => [
                    'The content must be a string.',
                    'The content must be between 1 and 4095 characters.'
                ],
                'ip' => [
                    'The ip must be a valid IP address.'
                ],
                'login' => [
                    'The login may only contain letters, numbers, and dashes.'
                ],
                'title' => [
                    'The title must be a string.'
                ],
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
        // Check validation errors
        $response = $this->json('POST', '/api/v1/post', [
                'title'     => str_repeat('Too long title', 30), 
                'content'   => str_repeat('Too long content', 1000),
                'login'     => 'Incorrect Login',
                'ip'        => 'Incorrect IP address',
            ]);
        $response->assertResponseStatus(422);
        $response->seeJsonEquals([
            'errors' => [
                'content' => [
                    'The content must be between 1 and 4095 characters.'
                ],
                'ip' => [
                    'The ip must be a valid IP address.'
                ],
                'login' => [
                    'The login may only contain letters, numbers, and dashes.'
                ],
                'title' => [
                    'The title must be between 1 and 255 characters.'
                ],
            ]
        ]);
    }
    
    /**
     * Test post creation
     *
     * @return void
     */
    public function testValidRequest()
    {
        // Check correct answer
        $response = $this->json('POST', '/api/v1/post', [
                'title'     => 'Correct title', 
                'content'   => 'Correct content',
                'login'     => 'Correct_author',
                'ip'        => '123.123.123.123',
            ]);
        $response->assertResponseStatus(200);
        $response->seeJsonStructure([
                'rating_total',
                'rating_count',
                'author_id',
                'content',
                'id',
                'rating',
            ]
        );
    }
}
