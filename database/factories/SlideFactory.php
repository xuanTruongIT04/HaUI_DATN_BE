<?php

namespace Database\Factories;

use App\Models\Slide;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Image;

class SlideFactory extends Factory
{
    protected $model = Slide::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $imageIds = Image::pluck('id')->toArray();

        return [
            'name' => $this->faker->name,
            'level' => $this->faker->numberBetween(0, 127),
            'status' => $this->faker->numberBetween(0, 2),
            'description' => $this->faker->sentence(),
            'link' => $this->faker->imageUrl(),
        ];
    }
}