<?php

namespace Database\Factories;

use App\Models\Coupon;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Coupon>
 */
class CouponFactory extends Factory
{
    protected $model = Coupon::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $orderIds = Order::pluck("id");
        return [
            //
            'name' => $this->faker->sentence,
            'code' => "#SBC_CP" . $this->faker->text(5),
            'percent' => $this->faker->randomFloat(2, 0, 100),
            'start_date' => $this->faker->dateTimeBetween('now', '+1 week'),
            'end_date' => $this->faker->dateTimeBetween('+1 week', '+2 weeks'),
        ];
    }
}
