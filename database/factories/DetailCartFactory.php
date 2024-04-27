<?php

namespace Database\Factories;

use App\Models\Cart;
use App\Models\Product;
use App\Models\DetailCart;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cart>
 */
class DetailCartFactory extends Factory
{
    protected $model = DetailCart::class;


    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */


    public function definition()
    {
        $cartIds = Cart::pluck('id');
        $productIds = Product::pluck('id');

        $productId = $this->faker->randomElement($productIds);
        $product = Product::find($productId);
        $discount = $product->discount ?? 0;
        $price = $product->price;
        return [
            "quantity" => $this->faker->numberBetween(1, 10),
            "price_sale" => $discount ? ($price * (100 - $discount) / 100) : $price,
            "cart_id" => $this->faker->randomElement($cartIds),
            "product_id" => $productId,
            "created_at" => now(),
            "updated_at" => now(),
        ];
    }
}