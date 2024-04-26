<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Favorite;
use App\Models\Product;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class FavoriteFactory extends Factory
{
    protected $model = Favorite::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $userIds = User::pluck('id');
        $productIds = Product::pluck('id');

        return [
            'user_id' => $this->faker->randomElement($userIds),
            'product_id' => $this->faker->randomElement($productIds),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];

    }
}