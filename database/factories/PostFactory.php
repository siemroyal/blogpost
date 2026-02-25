<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Post::class;

    public function definition(): array
    {
        $title = $this->faker->unique()->sentence(4);

        $status = $this->faker->randomElement(['draft', 'published', 'archived']);

        return [
            'user_id' => User::factory(),
            'category_id' => Category::factory(),
            'title' => $title,
            'slug' => Str::slug($title),
            'post_image' => $this->faker->boolean(70)
                ? 'posts/' . $this->faker->image('public/storage/posts', 640, 480, null, false)
                : null,
            'excerpt' => $this->faker->optional()->paragraph(),
            'body' => $this->faker->paragraphs(6, true),
            'status' => $status,
            'published_at' => $status === 'published'
                ? $this->faker->dateTimeBetween('-1 year', 'now')
                : null,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
    public function published()
    {
        return $this->state(fn () => [
            'status' => 'published',
            'published_at' => now(),
        ]);
    }
}
