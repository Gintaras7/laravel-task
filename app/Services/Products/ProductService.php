<?php

declare(strict_types=1);

namespace App\Services\Products;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class ProductService
{
    public function getProductFromCache(int $id): ?Product
    {
        $key = Product::CACHE_PREFIX.$id;
        $cachedProduct = Cache::get($key);

        if (! blank($cachedProduct)) {
            return $cachedProduct;
        }

        $product = Product::query()->with('tags')->where('id', $id)->first();

        if ($product !== null) {
            Cache::put($key, $product, 3600);
        }

        return $product;
    }

    public function getList(int $perPage = 10): LengthAwarePaginator
    {
        return Product::query()->with('tags')->paginate($perPage);
    }
}
