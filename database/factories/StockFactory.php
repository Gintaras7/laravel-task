<?php

namespace Database\Factories;

use App\Models\City;
use App\Models\Stock;
use Illuminate\Database\Eloquent\Factories\Factory;

class StockFactory extends Factory
{
    protected $model = Stock::class;

    public function definition(): array
    {
        return [
            'stock' => $this->faker->numberBetween(10, 100),
            'city_id' => City::factory()->create()->id,
        ];
    }
}
