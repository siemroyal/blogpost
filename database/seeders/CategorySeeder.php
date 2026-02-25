<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 5 main categories
        $mainCategories = Category::factory()->count(5)->create();

        // Create 10 subcategories (children)
        foreach (Category::factory()->count(10)->make() as $subcategory) {
            $subcategory->parent_id = $mainCategories->random()->id;
            $subcategory->save();
        }
    }
}
