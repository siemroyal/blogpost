<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    protected $model = \App\Models\Category::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    public function definition(): array
    {
        $name = $this->faker->unique()->words(2, true); // e.g., "E Sports"

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'parent_id' => null, // can later assign parent manually
            'description' => $this->faker->optional()->paragraph(),
            'image' => $this->faker->boolean(50)
                ? 'categories/' . $this->faker->image('public/storage/categories', 640, 480, null, false)
                : null,
            'status' => $this->faker->boolean(90), // mostly active
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
