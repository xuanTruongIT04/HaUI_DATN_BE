<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;
use App\Models\Color;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Image>
 */
class ImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $productIds = Product::pluck('id');
        $colorIds = Color::pluck('id');
        return [
            'product_id' => $this->faker->randomElement($productIds),
            'color_id' => $this->faker->randomElement($colorIds),
            'link' => $this->faker->imageUrl(),
            'level' => $this->faker->randomElement([0, 1]),
            'description' => $this->faker->sentence(),
        ];
    }
}
