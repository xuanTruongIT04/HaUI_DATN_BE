<?php

namespace Database\Factories;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class AdminFactory extends Factory
{
    protected $model = Admin::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            "name" => $this->faker->name(),
            "email" => $this->faker->unique()->email(),
            "email_verified_at" => now(),
            "password" => bcrypt("123123123"),
            "gender" => $this->faker->numberBetween(0, 2),
            "address" => $this->faker->address(),
            "phone" => $this->faker->phoneNumber(),
            "avatar" => $this->faker->imageUrl(),
            "role" => $this->faker->randomElement(['admin', 'editor', 'contributor']),
        ];
    }
}