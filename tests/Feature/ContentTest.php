<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class ContentTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test listing contents.
     */
    public function test_index(): void
    {
        $response = $this->get('/api/contents');

        $response->assertStatus(200)
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has(
                    'data.0',
                    fn (AssertableJson $json) =>
                    $json->whereAllType([
                        'name' => 'string',
                        'description' => 'string',
                        'image_path' => 'string',
                        'id' => 'integer'
                    ])->etc()
                )->etc()
            );
    }

    /**
     * Test listing contents with pagination.
     */
    public function test_index_pagination(): void
    {
        $response = $this->get('/api/contents?page=1&pageSize=10');

        $response->assertStatus(200)
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has(
                    'meta',
                    fn (AssertableJson $json) =>
                    $json->where('per_page', 10) // check the pageSize query
                        ->where('current_page', 1) // check the pageNo query
                        ->etc()
                )
                    ->has(
                        'data.0',
                        fn (AssertableJson $json) =>
                        $json->whereAllType([
                            'name' => 'string',
                            'description' => 'string',
                            'image_path' => 'string',
                            'id' => 'integer'
                        ])
                            ->etc()
                    )
                    ->etc()
            );
    }
}
