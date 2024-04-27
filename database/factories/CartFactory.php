<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Order;
use App\Models\Cart;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cart>
 */
class CartFactory extends Factory
{
    protected $model = Cart::class;


    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */


    public function definition()
    {
        $userIds = User::pluck('id');

        return [
            "total_item" => $this->faker->numberBetween(0, 2),
            "total_price" => $this->faker->numberBetween(0, 1000),
            "created_at" => now(),
            "updated_at" => now(),
            "user_id" => $this->faker->randomElement($userIds),
        ];
    }
}