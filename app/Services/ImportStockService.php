<?php

declare(strict_types=1);

namespace App\Services;

use App\DTOs\StockDTO;
use App\Models\Product;
use App\Models\Stock;
use Illuminate\Support\Facades\Log;

class ImportStockService
{
    public function import(StockDTO $stockDto): ?Stock
    {
        $product = Product::query()
            ->where('sku', $stockDto->sku)
            ->select('id')->first();

        if (blank($product)) {
            Log::debug('There is no associated product with stock '.$stockDto->sku);

            return null;
        }

        $stock = Stock::query()->where('product_id', $product->id)->first();
        if (blank($stock)) {
            $stock = new Stock;
        }

        $stock->stock = $stockDto->stock;
        $stock->city = $stockDto->city;
        $stock->product_id = $product->id;
        $stock->save();

        return $stock;
    }
}
