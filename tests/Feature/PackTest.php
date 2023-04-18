<?php

namespace Tests\Feature;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PackTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test listing packs.
     */
    public function test_index(): void
    {
        $response = $this->get('/api/packs');

        $response->assertStatus(200)
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has(
                    'data.0',
                    fn (AssertableJson $json) =>
                    $json->whereAllType([
                        'name' => 'string',
                        'id' => 'integer'
                    ])
                )->etc()
            );
    }

    /**
     * Test listing packs with pagination.
     */
    public function test_index_pagination(): void
    {
        $response = $this->get('/api/packs?page=1&pageSize=10');

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
                            'id' => 'integer'
                        ])
                            ->etc()
                    )
                    ->etc()
            );
    }

    public function test_store(): void
    {
        Sanctum::actingAs(
            Admin::factory()->create(),
            ['admin']
        );

        $response = $this->post('/api/admin/packs', ['name' => 'New Pack']);

        $response->assertStatus(201)
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has(
                    'data',
                    fn (AssertableJson $json) =>
                    $json->whereAllType([
                        'name' => 'string',
                        'id' => 'integer'
                    ])
                    ->where('name', 'New Pack')
                    ->etc()
                )
                ->etc()
            );
    }
}
