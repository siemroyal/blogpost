<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 50 mixed-status posts
        Post::factory()->count(50)->create();

        // 10 published posts
        Post::factory()->count(10)->published()->create();
    }
}
