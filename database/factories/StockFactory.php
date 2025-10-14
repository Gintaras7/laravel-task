<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Stock;
use Illuminate\Database\Eloquent\Factories\Factory;

class StockFactory extends Factory
{
    protected $model = Stock::class;

    public function definition(): array
    {
        $cities = ['Staré Město', 'Charlotte', 'Puttalam', 'Napanee Downtown', 'Thongwa', 'Berlin', 'New York'];

        return [
            'product_id' => Product::inRandomOrder()->first()?->id ?? Product::factory(),
            'city' => $this->faker->randomElement($cities),
            'stock' => $this->faker->numberBetween(10, 100),
        ];
    }
}
