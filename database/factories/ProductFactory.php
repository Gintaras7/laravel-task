<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'sku' => 'KF-'.$this->faker->unique()->numerify('###'),
            'description' => $this->faker->sentence(),
            'size' => $this->faker->randomElement(['XS', 'S', 'M', 'L', 'XL']),
            'photo' => $this->faker->imageUrl(192, 100, 'products', true),
            'external_updated_at' => $this->faker->date('Y-m-d'),
        ];
    }
}
