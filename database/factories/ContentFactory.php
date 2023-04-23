<?php

namespace Database\Factories;

use App\Models\Pack;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Content>
 */
class ContentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            "pack_id" => Pack::all()->random()->id,
            "name" => fake()->name(),
            "description" => fake()->text(),
            "image_path" => null,
            "release_date" => fake()->date(),
            "eu_release_date" => fake()->date(),
            "console_release_date" => fake()->date(),
        ];
    }
}
