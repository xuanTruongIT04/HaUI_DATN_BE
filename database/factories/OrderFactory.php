<?php

namespace Database\Factories;

use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cart>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;


    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */


    public function definition()
    {
        $ean8 = $this->faker->ean8;
        $code = "#sabc-" . $ean8;

        $couponIds = Coupon::pluck("id");
        $cartIds = Cart::pluck('id');
        return [
            'code' => $code,
            "address_delivery" => $this->faker->text(30),
            "payment_method" => $this->faker->numberBetween(0, 1),
            "total_mount" => $this->faker->numberBetween(1, 1000),
            "order_date" => now(),
            "coupon_id" => $this->faker->randomElement($couponIds),
            "cart_id" => $this->faker->randomElement($cartIds),
            "created_at" => now(),
            "updated_at" => now(),
        ];
    }
}