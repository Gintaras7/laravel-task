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
        $key = Product::CACHE_PREFIX.$id;

        if (Cache::has($key)) {
            return Cache::get($key);
        }

        $product = $this->productRepository->getProduct($id);

        if ($product !== null) {
            Cache::put($key, $product, 3600);
        }

        return $product;
    }
}
