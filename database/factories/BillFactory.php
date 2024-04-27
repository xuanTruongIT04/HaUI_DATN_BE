<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Bill;
use App\Models\Order;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Bill>
 */
class BillFactory extends Factory
{
    protected $model = Bill::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $orderIds = Order::pluck('id');
        $userIds = User::pluck("id");
        
        return [
            "order_id" => $this->faker->randomElement($orderIds),
            "user_id" => $this->faker->randomElement($userIds),
        ];
    }
}