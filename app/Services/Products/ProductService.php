<?php

declare(strict_types=1);

namespace App\Services\Products;

use App\Models\Product;
use App\Repositories\ProductRepository;
use Illuminate\Support\Facades\Cache;

class ProductService
{
    public function __construct(private ProductRepository $productRepository)
    {
        //
    }

    public function getProductFromCache(int $id): ?Product
    {
        $cacheKey = "product_{$id}";

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $product = $this->productRepository->getProduct($id);

        if ($product !== null) {
            Cache::put($cacheKey, $product, 3600);
        }

        return $product;
    }
}
