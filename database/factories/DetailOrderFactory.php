<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Product;
use App\Models\DetailOrder;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class DetailOrderFactory extends Factory
{
    protected $model = DetailOrder::class;


    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */


    public function definition()
    {
        $orderIds = Order::pluck('id');
        $productIds = Product::pluck('id');

        $productId = $this->faker->randomElement($productIds);
        $product = Product::find($productId);
        $discount = $product->discount ?? 0;
        $price = $product->price;
        return [
            "quantity" => $this->faker->numberBetween(1, 10),
            "price_sale" => $discount ? ($price * (100 - $discount) / 100) : $price,
            "order_id" => $this->faker->randomElement($orderIds),
            "product_id" => $productId,
            "created_at" => now(),
            "updated_at" => now(),
        ];
    }
}