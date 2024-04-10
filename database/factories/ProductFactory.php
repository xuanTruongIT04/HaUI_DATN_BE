<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $ean8 = $this->faker->ean8;
        $code = "#SBC-" . $ean8;
        return [
            'name' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'category_id' => $this->faker->numberBetween(1, 10),
            'brand_id' => $this->faker->numberBetween(1, 5),
            'code' => $code,
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'discount' => $this->faker->optional(0.1)->numberBetween(1, 50),
            'qty_import' => $this->faker->optional(0.5)->numberBetween(1, 100),
            'qty_sold' => $this->faker->numberBetween(1, 50),
            'detail' => $this->faker->paragraphs(3, true),
            'slug' => $this->faker->slug,
            'rate' => $this->faker->optional(0.4)->numberBetween(1, 5),
        ];
    }
}
