<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProductRepository
{
    public function getList(int $perPage = 10): LengthAwarePaginator
    {
        return Product::query()->with('tags')->paginate($perPage);
    }

    public function getProduct(int $id): ?Product
    {
        $product = Product::query()
            ->with('tags')
            ->where('id', $id)
            ->first();

        return $product;
    }
}
