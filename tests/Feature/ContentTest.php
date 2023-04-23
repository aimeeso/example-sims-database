<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Content;
use App\Models\Pack;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
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

    public function test_store(): void
    {
        Sanctum::actingAs(
            Admin::factory()->create(),
            ['admin']
        );

        $input = [
            "pack_id" => 1,
            "name"  => "New Content",
            "description" => "New Description",
            "image_path" => null,
            "release_date" => "2023-05-18",
            "eu_release_date" => null,
            "console_release_date" => "2023-05-20",
        ];

        $response = $this->post(
            '/api/admin/contents',
            $input
        );

        $response->assertStatus(201)
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has(
                    'data',
                    fn (AssertableJson $json) =>
                    $json->whereAllType([
                        'id' => 'integer'
                    ])
                        ->where('pack.id', $input['pack_id'])
                        ->where('name', $input['name'])
                        ->where('description', $input['description'])
                        ->where('image_path', $input['image_path'])
                        ->where('release_date', $input['release_date'])
                        ->where('eu_release_date', $input['eu_release_date'])
                        ->where('console_release_date', $input['console_release_date'])
                        ->etc()
                )
                    ->etc()
            );

        // Check database
        $this->assertDatabaseHas('contents', $input);
    }

    public function test_update(): void
    {
        Sanctum::actingAs(
            Admin::factory()->create(),
            ['admin']
        );

        // create a new content and get the id
        /** @var \App\Models\Content */
        $content = Content::factory()->create();

        $input = [
            "pack_id" => 1,
            "name"  => "Updated Content",
            "description" => "Updated Description",
            "image_path" => "image_01.png",
            "release_date" => "2023-05-08",
            "eu_release_date" => "2023-05-10",
            "console_release_date" => "2023-05-13",
        ];

        $response = $this->put('/api/admin/contents/' . $content->id, $input);

        $response->assertStatus(204);

        // Check database
        $this->assertDatabaseHas('contents', [
            'id' => $content->id
        ] + $input);
    }

    public function test_destroy(): void
    {
        Sanctum::actingAs(
            Admin::factory()->create(),
            ['admin']
        );

        // create a new content and get the id
        /** @var \App\Models\Content */
        $content = Content::factory()->create();
        $id = $content->id;

        $response = $this->delete('/api/admin/contents/' . $content->id);

        $response->assertStatus(204);

        // Check database
        $this->assertDatabaseMissing('contents', [
            'id' => $id,
        ]);
    }
}
