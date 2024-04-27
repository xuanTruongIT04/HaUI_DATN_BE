<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;
use App\Models\Tag;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Image>
 */
class ProductTagFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $productIds = Product::pluck('id');
        $tagIds = Tag::pluck('id');
        return [
            'product_id' => $this->faker->randomElement($productIds),
            'tag_id' => $this->faker->randomElement($tagIds),
            'created_at' => now(),
            'updated_at' => now()
        ];
    }
}