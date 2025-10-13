<?php

declare(strict_types=1);

namespace App\Services;

use App\DTOs\StockDTO;
use App\Models\City;
use App\Models\Stock;

class ImportStockService
{
    public function import(StockDTO $stockDto): ?Stock
    {
        $city = City::firstOrCreate(['title' => $stockDto->city]);

        $stock = Stock::query()->where('sku', $stockDto->sku)->first();
        if (blank($stock)) {
            $stock = new Stock;
        }

        $stock->sku = $stockDto->sku;
        $stock->stock = $stockDto->stock;
        $stock->city_id = $city->id;
        $stock->save();

        return $stock;
    }
}
